@extends('layouts.public')
@section('title', 'Checkout - ' . $product->title)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ $product->title }}</h2>
            <p class="text-gray-600 text-sm mt-1">{{ Str::limit($product->description, 100) }}</p>
            <div class="mt-4 text-2xl font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        </div>

        @if(request()->cookie('ref'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                Kode referral terdeteksi: <strong>{{ request()->cookie('ref') }}</strong>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.process', $product->slug) }}">
            @csrf
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-bold text-lg">
                Bayar Sekarang
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-4">Anda akan diarahkan ke halaman pembayaran Xendit</p>
    </div>
</div>
@endsection
