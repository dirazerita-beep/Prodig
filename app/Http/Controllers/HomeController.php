<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->latest()->get();
        return view('home', compact('products'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $landingPage = $product->landingPage;

        if ($landingPage && $landingPage->is_published) {
            $product->load([
                'landingPageImages',
                'landingPageTestimonials' => function ($query) {
                    $query->where('is_active', true);
                },
            ]);

            return view('product.landing', compact('product', 'landingPage'));
        }

        return view('product.show', compact('product'));
    }
}
