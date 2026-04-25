<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('checkout', compact('product'));
    }

    public function process(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $user = $request->user();

        $affiliateId = null;
        $uplineId = null;
        $refCode = $request->cookie('ref');

        if ($refCode) {
            $affiliate = User::where('referral_code', $refCode)->first();
            if ($affiliate && $affiliate->id !== $user->id) {
                $affiliateId = $affiliate->id;
                $uplineId = $affiliate->upline_id;
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'affiliate_id' => $affiliateId,
            'upline_id' => $uplineId,
            'amount' => $product->price,
            'status' => 'pending',
            'download_token' => Str::uuid()->toString(),
        ]);

        $xendit = new XenditService();
        $invoice = $xendit->createInvoice([
            'external_id' => 'ORDER-' . $order->id,
            'amount' => $product->price,
            'payer_email' => $user->email,
            'description' => 'Pembelian: ' . $product->title,
            'success_redirect_url' => route('checkout.success', $order->id),
            'failure_redirect_url' => route('product.show', $product->slug),
        ]);

        if (isset($invoice['invoice_url'])) {
            $order->update(['xendit_id' => $invoice['id']]);
            return redirect($invoice['invoice_url']);
        }

        return back()->with('error', 'Gagal membuat invoice pembayaran. Silakan coba lagi.');
    }

    public function success(Order $order)
    {
        return view('checkout-success', compact('order'));
    }
}
