<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('landingPage')->where('is_active', true)->get();
        $user = $request->user();

        return view('dashboard.products', compact('products', 'user'));
    }
}
