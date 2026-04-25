@extends('layouts.public')
@section('title', 'Checkout - ' . $product->title)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>

        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900">{{ $product->title }}</h2>
            <p class="text-gray-600 text-sm mt-1">{{ Str::limit($product->description, 100) }}</p>
            <div class="mt-4 text-2xl font-bold text-indigo-600" id="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        </div>

        @if(request()->cookie('ref'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
                Kode referral terdeteksi: <strong>{{ request()->cookie('ref') }}</strong>
            </div>
        @endif

        <div class="border border-gray-200 rounded-lg p-4 mb-6">
            <label for="coupon_input" class="block text-sm font-medium text-gray-700 mb-2">Punya kode kupon?</label>
            <div class="flex gap-2">
                <input type="text" id="coupon_input" placeholder="Masukkan kode kupon" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase text-sm">
                <button type="button" id="apply-coupon" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 font-medium text-sm whitespace-nowrap">Terapkan</button>
            </div>
            <div id="coupon-message" class="mt-2 text-sm hidden"></div>
            <div id="coupon-summary" class="mt-3 hidden">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Harga asli</span>
                    <span class="text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span class="text-red-600">Diskon (<span id="coupon-name"></span>)</span>
                    <span class="text-red-600" id="discount-value"></span>
                </div>
                <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between">
                    <span class="text-sm font-semibold text-gray-900">Total bayar</span>
                    <span class="text-lg font-bold text-indigo-600" id="final-price"></span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.process', $product->slug) }}" id="checkout-form">
            @csrf
            <input type="hidden" name="coupon_code" id="coupon_code_hidden" value="">
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-bold text-lg">
                Bayar Sekarang
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-4">Anda akan diarahkan ke halaman pembayaran Xendit</p>
    </div>
</div>

<script>
document.getElementById('apply-coupon').addEventListener('click', function() {
    const code = document.getElementById('coupon_input').value.trim();
    if (!code) return;

    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    fetch('{{ route("checkout.apply-coupon", $product->slug) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ coupon_code: code })
    })
    .then(response => response.json())
    .then(data => {
        const msgEl = document.getElementById('coupon-message');
        const summaryEl = document.getElementById('coupon-summary');
        msgEl.classList.remove('hidden');

        if (data.success) {
            msgEl.className = 'mt-2 text-sm text-green-600';
            msgEl.textContent = data.message;
            summaryEl.classList.remove('hidden');
            document.getElementById('coupon-name').textContent = data.coupon_name;
            document.getElementById('discount-value').textContent = '-' + data.discount_formatted;
            document.getElementById('final-price').textContent = data.final_price_formatted;
            document.getElementById('coupon_code_hidden').value = code.toUpperCase();
        } else {
            msgEl.className = 'mt-2 text-sm text-red-600';
            msgEl.textContent = data.message;
            summaryEl.classList.add('hidden');
            document.getElementById('coupon_code_hidden').value = '';
        }
    })
    .catch(() => {
        const msgEl = document.getElementById('coupon-message');
        msgEl.classList.remove('hidden');
        msgEl.className = 'mt-2 text-sm text-red-600';
        msgEl.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Terapkan';
    });
});
</script>
@endsection
