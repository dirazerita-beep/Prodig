@extends('layouts.dashboard')
@section('title', 'Produk')

@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Produk</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($products as $product)
        @php
            $lp = $product->landingPage;
            $heroImage = $lp && $lp->hero_image ? asset('storage/' . $lp->hero_image) : null;
            $affiliateLink = url('/p/' . $product->slug . '?ref=' . $user->referral_code);
            $commissionAmount = $product->price * $product->commission_percent / 100;
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Thumbnail --}}
            <div class="h-40 bg-gray-100">
                @if($heroImage)
                    <img src="{{ $heroImage }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                        <svg class="w-12 h-12 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-900 truncate mb-1">{{ $product->title }}</h3>
                <p class="text-lg font-bold text-indigo-600 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-green-600 font-medium mb-4">Komisi kamu: Rp {{ number_format($commissionAmount, 0, ',', '.') }} per penjualan</p>

                {{-- Buttons --}}
                <div class="space-y-2">
                    <a href="{{ $affiliateLink }}" target="_blank" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Lihat Landing Page
                    </a>
                    <button onclick="copyLink('{{ $affiliateLink }}', this)" class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        Salin Link Afiliasi
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
function copyLink(link, btn) {
    navigator.clipboard.writeText(link).then(function() {
        var originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Link berhasil disalin!';
        btn.classList.remove('bg-gray-100', 'text-gray-700');
        btn.classList.add('bg-green-100', 'text-green-700');
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-100', 'text-green-700');
            btn.classList.add('bg-gray-100', 'text-gray-700');
        }, 2000);
    });
}
</script>
@endsection
