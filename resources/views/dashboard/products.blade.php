@extends('layouts.dashboard')
@section('title', 'Link Afiliasi')

@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Link Afiliasi</h1>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komisi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Link Afiliasi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($products as $product)
            <tr>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-green-600 font-medium">Rp {{ number_format($product->price * $product->commission_percent / 100, 0, ',', '.') }} ({{ $product->commission_percent }}%)</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ url('/p/' . $product->slug . '?ref=' . $user->referral_code) }}" class="text-xs bg-gray-50 border border-gray-200 rounded px-3 py-1.5 w-80">
                        <button onclick="navigator.clipboard.writeText('{{ url('/p/' . $product->slug . '?ref=' . $user->referral_code) }}')" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium whitespace-nowrap">Salin</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
