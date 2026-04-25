@extends('layouts.admin')
@section('title', 'Kelola Produk')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Kelola Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium">Tambah Produk</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komisi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($products as $product)
            <tr>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                    <div class="text-xs text-gray-500">{{ $product->slug }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->commission_percent }}% / {{ $product->upline_percent }}%</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
