@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Tahap Pembayaran — ' . $order->order_number)

@section('content')
<div class="max-w-xl mx-auto px-4 py-6 space-y-4">

    <!-- Top Navigation -->
    <div class="flex items-center justify-between pb-2">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="p-1.5 -ml-1 text-gray-700 hover:text-gray-900 rounded-lg hover:bg-gray-100 transition-colors"
               title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">Tahap Pembayaran</h1>
                <p class="text-xs text-gray-400">Pilih metode pembayaran favorit Anda</p>
            </div>
        </div>
        <!-- Countdown Badge -->
        <div class="bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 shrink-0">
            <span class="animate-pulse">⏳</span>
            <span id="payment-timer">23:59:59</span>
        </div>
    </div>

    <!-- ===== CARD 1: ORDER SUMMARY ===== -->
    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3.5">
        <div class="flex items-center justify-between text-xs pb-2.5 border-b border-gray-100">
            <span class="text-gray-400 font-medium">Nomor Pesanan</span>
            <span class="font-mono font-bold text-gray-900">{{ $order->order_number }}</span>
        </div>

        <div class="flex items-center gap-3.5">
            <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-900 shrink-0 border border-gray-200">
                @if ($order->product_image && Storage::disk('public')->exists($order->product_image))
                    <img src="{{ asset('storage/' . $order->product_image) }}" alt="{{ $order->product_name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-indigo-900 to-blue-900 flex items-center justify-center text-white font-bold text-lg">
                        🎮
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-sm text-gray-900 truncate">{{ $order->product_name }}</h3>
                <p class="text-xs text-gray-400 truncate">{{ $order->store ? $order->store->name : 'Toko Gian Games' }} • Qty: {{ $order->quantity }}</p>
                <div class="font-black text-sm text-[#ff5e00] mt-0.5">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="bg-[#f8fafd] rounded-xl p-3 space-y-1.5 text-xs text-gray-600 border border-gray-100">
            <div class="flex justify-between">
                <span>Harga Produk ({{ $order->quantity }}x)</span>
                <span class="font-medium text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Biaya Layanan & Penanganan</span>
                <span class="font-bold text-emerald-600">Gratis (Rp 0)</span>
            </div>
            <div class="flex justify-between pt-1.5 border-t border-gray-200 text-sm font-extrabold text-gray-900">
                <span>Total Pembayaran</span>
                <span class="text-[#ff5e00]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- ===== CARD 2: PILIH METODE PEMBAYARAN ===== -->
    <form action="{{ route('orders.pay', $order->order_number) }}" method="POST" id="payment-form" class="space-y-4">
        @csrf

        <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
            <h2 class="text-sm font-bold text-gray-900 flex items-center justify-between">
                <span>Metode Pembayaran</span>
                <span class="text-[11px] text-gray-400 font-normal">Pilih salah satu</span>
            </h2>

            <div class="space-y-2.5">
                <!-- 1. QRIS (Rekomendasi / Instan) -->
                <label class="payment-method-card flex items-start gap-3.5 p-3.5 rounded-xl border-2 cursor-pointer transition-all border-[#0050ae] bg-blue-50/40">
                    <input type="radio" name="payment_method" value="QRIS" checked class="mt-1 text-[#0050ae] focus:ring-[#0050ae]" onchange="selectPaymentMethod('QRIS')">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-xs text-gray-900 flex items-center gap-1.5">
                                <span class="bg-red-600 text-white font-mono font-black text-[9px] px-1.5 py-0.5 rounded">QRIS</span>
                                <span>QRIS (DANA, GoPay, OVO, ShopeePay, BCA)</span>
                            </span>
                            <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full">Instan</span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Scan kode QR langsung via aplikasi dompet digital atau m-banking apa saja.</p>

                        <!-- Dynamic QRIS Display Box -->
                        <div id="qris-box" class="mt-3 bg-white p-3.5 rounded-xl border border-gray-200 text-center space-y-2">
                            <div class="inline-block p-2 bg-white rounded-lg shadow-sm border border-gray-200">
                                <!-- Styled QR Code visual placeholder -->
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=GIANGAMES-{{ $order->order_number }}"
                                     alt="QRIS Code"
                                     class="w-40 h-40 mx-auto object-contain">
                            </div>
                            <p class="text-[11px] text-gray-400 font-mono">NMID: ID102003881920 • GIAN GAMES</p>
                        </div>
                    </div>
                </label>

                <!-- 2. DANA -->
                <label class="payment-method-card flex items-start gap-3.5 p-3.5 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-blue-200">
                    <input type="radio" name="payment_method" value="DANA" class="mt-1 text-[#0050ae] focus:ring-[#0050ae]" onchange="selectPaymentMethod('DANA')">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-xs text-gray-900 flex items-center gap-1.5">
                                <span class="bg-[#108ee9] text-white font-bold text-[9px] px-2 py-0.5 rounded">DANA</span>
                                <span>DANA E-Wallet</span>
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Bayar langsung dengan saldo DANA Anda.</p>
                        <div id="dana-input-box" class="mt-2.5 hidden">
                            <input type="tel" placeholder="Nomor DANA (08xxxxxxxxxx)" value="{{ auth()->user()->phone ?? '081234567890' }}" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-xs text-gray-900 font-mono focus:outline-none focus:ring-1 focus:ring-[#108ee9]">
                        </div>
                    </div>
                </label>

                <!-- 3. GoPay -->
                <label class="payment-method-card flex items-start gap-3.5 p-3.5 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-cyan-200">
                    <input type="radio" name="payment_method" value="GoPay" class="mt-1 text-[#0050ae] focus:ring-[#0050ae]" onchange="selectPaymentMethod('GoPay')">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-xs text-gray-900 flex items-center gap-1.5">
                                <span class="bg-[#00aed6] text-white font-bold text-[9px] px-2 py-0.5 rounded">GoPay</span>
                                <span>GoPay / Gojek</span>
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Pembayaran mudah terhubung dengan GoPay & Gojek App.</p>
                    </div>
                </label>

                <!-- 4. ShopeePay / OVO -->
                <label class="payment-method-card flex items-start gap-3.5 p-3.5 rounded-xl border-2 border-gray-200 cursor-pointer transition-all hover:border-orange-200">
                    <input type="radio" name="payment_method" value="ShopeePay" class="mt-1 text-[#0050ae] focus:ring-[#0050ae]" onchange="selectPaymentMethod('ShopeePay')">
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-xs text-gray-900 flex items-center gap-1.5">
                                <span class="bg-[#ee4d2d] text-white font-bold text-[9px] px-1.5 py-0.5 rounded">ShopeePay</span>
                                <span>ShopeePay & OVO</span>
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Bayar dengan saldo ShopeePay atau OVO Anda.</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Submit & Pay Button -->
        <button type="submit"
                id="btn-pay-now"
                class="w-full py-3.5 bg-gradient-to-r from-blue-600 via-[#0050ae] to-blue-700 hover:from-blue-700 hover:to-[#003d87] text-white font-black text-sm rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-[0.99] flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span id="btn-pay-text">Bayar & Konfirmasi Sekarang (Rp {{ number_format($order->total_price, 0, ',', '.') }})</span>
        </button>
    </form>

</div>

<!-- Payment Processing Modal Simulation -->
<div id="payment-loading-modal" class="fixed inset-0 bg-black/70 backdrop-blur-xs z-[200] flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center space-y-4 shadow-2xl">
        <div class="w-16 h-16 mx-auto rounded-full bg-blue-50 text-[#0050ae] flex items-center justify-center animate-spin">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        </div>
        <div class="space-y-1">
            <h3 class="text-base font-extrabold text-gray-900">Memverifikasi Pembayaran...</h3>
            <p class="text-xs text-gray-500" id="loading-method-text">Menghubungkan ke gateway pembayaran</p>
        </div>
    </div>
</div>

<script>
function selectPaymentMethod(method) {
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.classList.remove('border-[#0050ae]', 'bg-blue-50/40');
        card.classList.add('border-gray-200');
    });

    const selectedRadio = document.querySelector(`input[name="payment_method"][value="${method}"]`);
    if (selectedRadio) {
        const card = selectedRadio.closest('.payment-method-card');
        if (card) {
            card.classList.add('border-[#0050ae]', 'bg-blue-50/40');
            card.classList.remove('border-gray-200');
        }
    }

    const qrisBox = document.getElementById('qris-box');
    const danaInput = document.getElementById('dana-input-box');

    if (qrisBox) qrisBox.style.display = method === 'QRIS' ? 'block' : 'none';
    if (danaInput) danaInput.style.display = method === 'DANA' ? 'block' : 'none';

    const btnText = document.getElementById('btn-pay-text');
    if (btnText) {
        btnText.textContent = `Bayar via ${method} (Rp {{ number_format($order->total_price, 0, ',', '.') }})`;
    }
}

// Payment Timer Countdown
(function() {
    let seconds = 86399; // 23:59:59
    const timerElem = document.getElementById('payment-timer');
    if (!timerElem) return;

    setInterval(function() {
        if (seconds <= 0) return;
        seconds--;
        const hrs = String(Math.floor(seconds / 3600)).padStart(2, '0');
        const mins = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
        const secs = String(seconds % 60).padStart(2, '0');
        timerElem.textContent = `${hrs}:${mins}:${secs}`;
    }, 1000);
})();

// Intercept submit to show instant verification animation
document.getElementById('payment-form').addEventListener('submit', function(e) {
    const selected = document.querySelector('input[name="payment_method"]:checked')?.value || 'Pembayaran';
    const modal = document.getElementById('payment-loading-modal');
    const loadingText = document.getElementById('loading-method-text');
    if (modal) {
        modal.classList.remove('hidden');
        if (loadingText) loadingText.textContent = `Memproses pembayaran via ${selected}...`;
    }
});
</script>
@endsection
