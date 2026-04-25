@extends('layouts.public')
@section('title', 'PRODIG - Marketplace Produk Digital')

@section('content')
<div class="bg-indigo-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-4">Marketplace Produk Digital</h1>
        <p class="text-xl text-indigo-100 mb-8">Temukan produk digital berkualitas dan dapatkan komisi sebagai affiliator!</p>
        @guest
            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-indigo-50 inline-block">Daftar Sekarang</a>
        @endguest
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-8">Produk Digital</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($products as $product)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 h-48 flex items-center justify-center">
                <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->title }}</h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <a href="{{ route('product.show', $product->slug) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm font-medium">Detail</a>
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    Komisi Affiliator: {{ $product->commission_percent }}% | Bonus Upline: {{ $product->upline_percent }}%
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
