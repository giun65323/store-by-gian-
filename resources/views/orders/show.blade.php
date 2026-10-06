@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Detail Pesanan — ' . $order->order_number . ' | gian games')

@section('content')
<div class="max-w-xl mx-auto px-4 py-6 space-y-4">

    <!-- Top Navigation Header -->
    <div class="flex items-center justify-between pb-2">
        <div class="flex items-center gap-3">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('orders.index') }}"
               class="p-2 -ml-1 text-gray-700 dark:text-gray-200 hover:text-gray-950 dark:hover:text-white rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
               title="Kembali ke Daftar Transaksi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-base font-black text-gray-900 dark:text-white leading-tight">Detail Transaksi</h1>
                <p class="text-[11px] font-mono text-gray-400">{{ $order->order_number }}</p>
            </div>
        </div>

        <button type="button"
                onclick="openOrderChatModal()"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950 text-[#0050ae] dark:text-sky-400 hover:bg-[#0050ae] hover:text-white rounded-xl font-extrabold text-xs transition-all shadow-xs cursor-pointer">
            <span>💬 Live Chat</span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- 1. VISUAL 4-STAGE STEPPER TRACKER CARD -->
    <!-- ========================================================================= -->
    @php
        $status = $order->status;
        $isPending = ($status === 'pending');
        $isProcessing = ($status === 'processing');
        $isCompleted = ($status === 'completed');
        $isCancelled = ($status === 'cancelled');

        // Progress percentage for stepper line
        $progressPct = 0;
        if ($isPending) $progressPct = 12;
        elseif ($isProcessing) $progressPct = 60;
        elseif ($isCompleted) $progressPct = 100;
    @endphp

    <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-gray-400">Status Tracking</span>
            @if($isCompleted)
                <span class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 text-xs font-black px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Pesanan Selesai
                </span>
            @elseif($isProcessing)
                <span class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 text-xs font-black px-3 py-1 rounded-full border border-blue-200 dark:border-blue-800 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    Sedang Diproses
                </span>
            @elseif($isPending)
                <span class="inline-flex items-center gap-1.5 bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 text-xs font-black px-3 py-1 rounded-full border border-amber-200 dark:border-amber-800 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    Menunggu Pembayaran
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 text-xs font-black px-3 py-1 rounded-full">
                    Dibatalkan
                </span>
            @endif
        </div>

        <!-- Multi-Step Progress Tracker Bar -->
        <div class="relative pt-2 pb-1">
            <!-- Background Line -->
            <div class="absolute top-5 left-6 right-6 h-1 bg-gray-200 dark:bg-gray-800 -translate-y-1/2 z-0 rounded-full"></div>
            <!-- Active Filled Progress Line -->
            <div class="absolute top-5 left-6 h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-emerald-500 -translate-y-1/2 z-0 rounded-full transition-all duration-500"
                 style="width: calc({{ $progressPct }}% - 24px);"></div>

            <!-- 4 Stage Circles Grid -->
            <div class="relative z-10 grid grid-cols-4 text-center">
                <!-- Step 1: Bayar -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs shadow-md transition-all {{ !$isPending ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white ring-4 ring-amber-100 dark:ring-amber-900/50' }}">
                        {{ !$isPending ? '✓' : '1' }}
                    </div>
                    <span class="text-[10px] font-black text-gray-800 dark:text-gray-200 mt-2">Bayar</span>
                    <span class="text-[9px] text-gray-400 font-medium">{{ $isPending ? 'Menunggu' : 'Lunas' }}</span>
                </div>

                <!-- Step 2: Proses -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs shadow-md transition-all {{ $isCompleted ? 'bg-emerald-500 text-white' : ($isProcessing ? 'bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/50' : 'bg-gray-200 dark:bg-gray-800 text-gray-400') }}">
                        {{ $isCompleted ? '✓' : '2' }}
                    </div>
                    <span class="text-[10px] font-black text-gray-800 dark:text-gray-200 mt-2">Proses</span>
                    <span class="text-[9px] text-gray-400 font-medium">{{ $isProcessing ? 'Diproses' : ($isCompleted ? 'Selesai' : 'Pending') }}</span>
                </div>

                <!-- Step 3: Kirim -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs shadow-md transition-all {{ $isCompleted ? 'bg-emerald-500 text-white' : ($isProcessing ? 'bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-sky-400' : 'bg-gray-200 dark:bg-gray-800 text-gray-400') }}">
                        {{ $isCompleted ? '✓' : '3' }}
                    </div>
                    <span class="text-[10px] font-black text-gray-800 dark:text-gray-200 mt-2">Kirim</span>
                    <span class="text-[9px] text-gray-400 font-medium">{{ $order->delivery_type === 'Pengiriman BIASA' ? 'Biasa' : 'Instan' }}</span>
                </div>

                <!-- Step 4: Selesai -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs shadow-md transition-all {{ $isCompleted ? 'bg-emerald-500 text-white ring-4 ring-emerald-100 dark:ring-emerald-900/50' : 'bg-gray-200 dark:bg-gray-800 text-gray-400' }}">
                        {{ $isCompleted ? '✓' : '4' }}
                    </div>
                    <span class="text-[10px] font-black text-gray-800 dark:text-gray-200 mt-2">Selesai</span>
                    <span class="text-[9px] text-gray-400 font-medium">{{ $isCompleted ? 'Diterima' : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== PENDING PAYMENT CALLOUT ===== -->
    @if($isPending)
    <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-white rounded-3xl p-5 shadow-lg space-y-3 relative overflow-hidden">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-white/20 flex items-center justify-center text-2xl shrink-0">
                    ⏳
                </div>
                <div>
                    <h3 class="font-black text-sm leading-tight">Menunggu Pembayaran</h3>
                    <p class="text-xs text-amber-100 mt-0.5">Selesaikan pembayaran untuk membuka kode voucher instan.</p>
                </div>
            </div>
            <span class="bg-white/20 text-white font-mono text-[11px] font-black px-3 py-1 rounded-xl shrink-0 shadow-xs">
                {{ $order->payment_method ?: 'QRIS' }}
            </span>
        </div>
        <a href="{{ route('orders.payment', $order->order_number) }}"
           class="w-full py-3 bg-white hover:bg-gray-50 text-gray-950 font-black text-xs rounded-2xl shadow-md transition-all active:scale-[0.99] flex items-center justify-center gap-2 hover:scale-[1.01]">
            <span>Lanjutkan Pembayaran (DANA / GoPay / QRIS)</span>
            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 2. SELLER FULFILLMENT BANNER (For Regular Orders in Processing status) -->
    <!-- ========================================================================= -->
    @php
        $isSeller = (auth()->id() === $order->seller_id || auth()->user()->isAdmin());
        $isRegular = ($order->delivery_type === 'Pengiriman BIASA');
    @endphp

    @if($isProcessing && $isSeller)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/40 border-2 border-blue-200 dark:border-blue-800 rounded-3xl p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                        📦
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900 dark:text-white">Pesanan Masuk Perlu Dikirim</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pembeli telah membayar. Silakan proses item dan kirimkan konfirmasi.</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-blue-600 text-white font-black text-[10px] rounded-lg uppercase tracking-wider shadow-xs">
                    Perlu Dikirim
                </span>
            </div>

            <form method="POST" action="{{ route('orders.deliver', $order->order_number) }}" class="space-y-3 pt-1">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                        Catatan / Data Pengiriman ke Pembeli (Opsional):
                    </label>
                    <textarea name="delivery_note" rows="2"
                              placeholder="Contoh: Akun telah dikirim via chat / Data Akun: username & password / Top up telah berhasil..."
                              class="w-full text-xs p-3.5 border border-gray-300 dark:border-gray-700 rounded-2xl bg-white dark:bg-gray-800 focus:outline-none focus:border-[#0050ae] focus:ring-2 focus:ring-[#0050ae]/15"></textarea>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-xs transition-all active:scale-95 cursor-pointer">
                        <span>✓</span>
                        <span>Konfirmasi & Selesaikan Pengiriman</span>
                    </button>
                    <button type="button"
                            onclick="openOrderChatModal()"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs rounded-xl hover:bg-gray-50 transition-all cursor-pointer shadow-xs">
                        <span>💬 Chat Pembeli</span>
                    </button>
                </div>
            </form>
        </div>
    @elseif($isProcessing && !$isSeller)
        <div class="bg-blue-50/80 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 rounded-3xl p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-blue-100 dark:bg-blue-900 text-[#0050ae] dark:text-sky-300 flex items-center justify-center text-xl shrink-0">
                    ⏳
                </div>
                <div>
                    <h3 class="text-xs font-black text-gray-900 dark:text-white">Pesanan Sedang Diproses Penjual</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Penjual sedang menyiapkan pesanan Anda. Gunakan chat jika perlu mengirimkan User ID / Nickname game.</p>
                </div>
            </div>
            <button type="button"
                    onclick="openOrderChatModal()"
                    class="px-4 py-2 bg-[#0050ae] text-white font-black text-xs rounded-xl shadow-xs hover:bg-[#003d87] transition-all shrink-0 cursor-pointer">
                💬 Hubungi Penjual
            </button>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 3. CYBERPUNK / PREMIUM VOUCHER CODE BOX -->
    <!-- ========================================================================= -->
    <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 shadow-xs space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-xs font-black text-gray-900 dark:text-white">
                {{ $isRegular ? 'Data / Catatan Pengiriman' : 'Kode Voucher Game (Instan)' }}
            </h2>
            @if($isRegular)
                <span class="text-[10px] font-black text-blue-600 bg-blue-50 dark:bg-blue-950 px-2 py-0.5 rounded-lg">📦 PESANAN BIASA</span>
            @else
                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 dark:bg-emerald-950 px-2 py-0.5 rounded-lg">⚡ INSTANT ACCESS</span>
            @endif
        </div>

        @if($order->voucher_code)
            <div class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 dark:from-[#1e293b] dark:to-[#0f172a] border-2 border-blue-300 dark:border-blue-700/60 rounded-2xl p-4 flex items-center justify-between gap-3 shadow-inner">
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold text-blue-600 dark:text-sky-400 block mb-0.5">KODE / SERIAL KEY:</span>
                    <div class="text-base sm:text-lg font-black text-gray-950 dark:text-white font-mono tracking-wider select-all break-all" id="voucher-code-text">
                        {{ $order->voucher_code }}
                    </div>
                </div>
                <button type="button"
                        onclick="copyToClipboard('{{ $order->voucher_code }}', 'Kode Voucher')"
                        class="px-4 py-2.5 bg-[#0050ae] hover:bg-[#003d87] text-white font-black text-xs rounded-xl shadow-md transition-all hover:scale-105 active:scale-95 flex items-center gap-1.5 shrink-0 cursor-pointer"
                        title="Salin Kode Voucher">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Salin Kode</span>
                </button>
            </div>
        @elseif($isProcessing)
            <div class="bg-blue-50/50 dark:bg-blue-950/30 border border-dashed border-blue-300 dark:border-blue-800 rounded-2xl p-5 text-center space-y-1.5">
                <div class="text-2xl">📦</div>
                <p class="text-xs font-black text-gray-800 dark:text-gray-200">Menunggu Pengiriman dari Penjual</p>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">Penjual akan mengisi data atau konfirmasi pesanan setelah selesai.</p>
            </div>
        @else
            <div class="bg-gray-50 dark:bg-gray-800/40 border border-dashed border-gray-300 dark:border-gray-700 rounded-2xl p-5 text-center space-y-2">
                <div class="text-2xl">🔒</div>
                <p class="text-xs font-black text-gray-800 dark:text-gray-200">Kode / Data Pengiriman Terkunci</p>
                <p class="text-[11px] text-gray-400">Kode voucher akan otomatis muncul setelah pembayaran terkonfirmasi.</p>
                <a href="{{ route('orders.payment', $order->order_number) }}"
                   class="inline-block px-5 py-2 bg-[#0050ae] hover:bg-[#003d87] text-white text-xs font-black rounded-xl transition-all shadow-md">
                    Bayar Sekarang
                </a>
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- 4. ORDER INFORMATION & ITEM SUMMARY -->
    <!-- ========================================================================= -->
    <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 shadow-xs space-y-4">
        <h2 class="text-xs font-black text-gray-900 dark:text-white">Rincian Produk</h2>

        <div class="border border-gray-100 dark:border-gray-800 rounded-2xl p-4 space-y-3 bg-gray-50/50 dark:bg-gray-800/30">
            <div class="flex items-start gap-3.5">
                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-900 shrink-0 border border-gray-200 dark:border-gray-700 shadow-xs">
                    @if ($order->product_image && Storage::disk('public')->exists($order->product_image))
                        <img src="{{ asset('storage/' . $order->product_image) }}" alt="{{ $order->product_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-950 to-blue-900 flex items-center justify-center text-white font-black text-xl">
                            🎮
                        </div>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="font-black text-sm text-gray-900 dark:text-white leading-snug truncate">{{ $order->product_name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $order->category_name }}</p>
                    <div class="text-sm font-black text-[#ff5e00] mt-1">
                        Rp {{ number_format($order->price, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Store & Chat Action -->
            <div class="pt-3 border-t border-gray-200/60 dark:border-gray-700 flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-300 truncate">
                    <span>🏬</span>
                    <span class="font-bold truncate">
                        {{ $isSeller ? 'Pembeli: ' . ($order->buyer?->name ?? 'Pembeli') : ($order->store ? $order->store->name : ($order->seller?->name ?? 'Toko Penjual')) }}
                    </span>
                </div>

                <button type="button"
                        onclick="openOrderChatModal()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#0050ae] text-white hover:bg-[#003d87] font-black text-xs transition-all shadow-xs shrink-0 cursor-pointer hover:scale-105">
                    <span>💬 Chat</span>
                </button>
            </div>
        </div>

        <!-- Payment & Total Breakdown -->
        <div class="pt-2 border-t border-gray-100 dark:border-gray-800 space-y-2 text-xs">
            <div class="flex justify-between text-gray-500 dark:text-gray-400">
                <span>Metode Pembayaran</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ $order->payment_method ?? 'DANA / QRIS' }}</span>
            </div>
            <div class="flex justify-between text-gray-500 dark:text-gray-400">
                <span>Waktu Transaksi</span>
                <span class="font-bold text-gray-900 dark:text-white">{{ $order->created_at->translatedFormat('d M Y, H:i:s') }}</span>
            </div>
            <div class="flex justify-between text-sm font-black text-gray-950 dark:text-white pt-2 border-t border-gray-100 dark:border-gray-800">
                <span>Total Tagihan</span>
                <span class="text-[#ff5e00]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- ===== REVIEW BUTTON ===== -->
    @if($isCompleted)
    <div class="pt-2">
        <button type="button"
                onclick="openReviewModal()"
                class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-[#0050ae] to-indigo-700 hover:from-[#003d87] hover:to-indigo-800 text-white font-black text-xs shadow-md hover:shadow-lg transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
            <span>⭐</span>
            <span>Beri Ulasan Kepuasan Transaksi</span>
        </button>
    </div>
    @endif

</div>

<!-- ========================================================================= -->
<!-- LIVE ORDER CHAT SLIDE-OVER / MODAL WINDOW -->
<!-- ========================================================================= -->
<div id="chat-modal-backdrop"
     onclick="closeOrderChatModal()"
     class="fixed inset-0 bg-black/60 backdrop-blur-xs z-[120] opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center p-4">

    <div id="chat-modal-box"
         onclick="event.stopPropagation()"
         class="bg-white dark:bg-[#111827] rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden flex flex-col h-[520px] max-h-[90vh] transform scale-95 opacity-0 transition-all duration-300 border border-gray-100 dark:border-gray-800">

        <!-- Chat Header -->
        <div class="bg-[#0050ae] text-white p-4 flex items-center justify-between shrink-0 shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center text-lg font-bold border border-white/20">
                    💬
                </div>
                <div>
                    <h3 class="font-extrabold text-sm leading-tight">Live Chat Pesanan</h3>
                    <p class="text-[11px] text-blue-100 truncate">
                        {{ $isSeller ? 'Dengan Pembeli: ' . ($order->buyer?->name ?? 'Pembeli') : 'Dengan Toko: ' . ($order->store ? $order->store->name : ($order->seller?->name ?? 'Penjual')) }}
                    </p>
                </div>
            </div>
            <button type="button"
                    onclick="closeOrderChatModal()"
                    class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer">
                ✕
            </button>
        </div>

        <!-- Chat Messages List -->
        <div id="chat-messages-container" class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#f8fafd] dark:bg-[#0b0f19] text-xs">
            <div class="text-center py-8 text-gray-400">
                <span class="text-3xl block mb-1">💬</span>
                <p>Memuat pesan obrolan...</p>
            </div>
        </div>

        <!-- Quick Reply Chips -->
        <div class="bg-white dark:bg-[#111827] border-t border-gray-100 dark:border-gray-800 px-3 py-2 flex items-center gap-1.5 overflow-x-auto scrollbar-hide text-[11px] shrink-0">
            <span class="text-gray-400 shrink-0 text-[10px] font-bold">⚡ Cepat:</span>
            @if($isSeller)
                <button type="button" onclick="sendQuickReply('Halo! Pesanan Anda sedang kami proses ya 👍')" class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950 text-gray-700 dark:text-gray-200 rounded-full shrink-0 transition-colors font-semibold">
                    Sedang diproses 👍
                </button>
                <button type="button" onclick="sendQuickReply('Data pesanan / voucher sudah kami kirimkan ya 🙏')" class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950 text-gray-700 dark:text-gray-200 rounded-full shrink-0 transition-colors font-semibold">
                    Pesanan sudah dikirim 🙏
                </button>
            @else
                <button type="button" onclick="sendQuickReply('Halo kak, apakah pesanan ini ready?')" class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950 text-gray-700 dark:text-gray-200 rounded-full shrink-0 transition-colors font-semibold">
                    Apakah ready?
                </button>
                <button type="button" onclick="sendQuickReply('Pesanan sudah saya terima, terima kasih banyak! ⭐')" class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-950 text-gray-700 dark:text-gray-200 rounded-full shrink-0 transition-colors font-semibold">
                    Sudah diterima ⭐
                </button>
            @endif
        </div>

        <!-- Chat Input Form -->
        <form id="chat-send-form" onsubmit="handleSendChatMessage(event)" class="bg-white dark:bg-[#111827] border-t border-gray-100 dark:border-gray-800 p-3 flex items-center gap-2 shrink-0">
            @csrf
            <input type="text"
                   id="chat-message-input"
                   placeholder="Ketik pesan..."
                   autocomplete="off"
                   required
                   class="flex-1 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-2.5 text-xs text-gray-800 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0050ae] transition-all">
            <button type="submit"
                    id="chat-send-btn"
                    class="bg-[#0050ae] hover:bg-[#003d87] text-white p-2.5 rounded-2xl font-bold transition-all shadow-sm flex items-center justify-center shrink-0 cursor-pointer hover:scale-105 active:scale-95"
                    title="Kirim Pesan">
                <svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
            </button>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- REVIEW MODAL -->
<!-- ========================================================================= -->
<div id="review-modal" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-[120] hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#111827] rounded-3xl p-6 max-w-sm w-full shadow-2xl space-y-4 border border-gray-100 dark:border-gray-800 text-center">
        <div class="text-4xl">⭐</div>
        <div>
            <h3 class="font-black text-base text-gray-900 dark:text-white">Beri Ulasan Pesanan</h3>
            <p class="text-xs text-gray-400 mt-0.5">Bagikan pengalaman belanja Anda untuk toko ini</p>
        </div>
        <div class="flex justify-center gap-2 text-2xl cursor-pointer py-1">
            <span class="hover:scale-125 transition-transform">⭐</span>
            <span class="hover:scale-125 transition-transform">⭐</span>
            <span class="hover:scale-125 transition-transform">⭐</span>
            <span class="hover:scale-125 transition-transform">⭐</span>
            <span class="hover:scale-125 transition-transform">⭐</span>
        </div>
        <textarea placeholder="Tulis kepuasan transaksi Anda..." class="w-full p-3 text-xs bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#0050ae]"></textarea>
        <div class="flex gap-2">
            <button onclick="document.getElementById('review-modal').classList.add('hidden')" class="flex-1 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 rounded-xl font-bold text-xs">Batal</button>
            <button onclick="submitReview()" class="flex-1 py-2.5 bg-[#0050ae] hover:bg-[#003d87] text-white rounded-xl font-bold text-xs">Kirim Ulasan</button>
        </div>
    </div>
</div>

<script>
const ORDER_NUMBER = '{{ $order->order_number }}';
let chatPollInterval = null;

function openReviewModal() {
    document.getElementById('review-modal').classList.remove('hidden');
}

function submitReview() {
    document.getElementById('review-modal').classList.add('hidden');
    if (window.showToast) {
        window.showToast('Terima kasih atas ulasan bintang 5 Anda! ⭐', 'success');
    }
}

/* ── Live Chat Logic ── */
function openOrderChatModal() {
    const backdrop = document.getElementById('chat-modal-backdrop');
    const box = document.getElementById('chat-modal-box');
    if (!backdrop || !box) return;

    backdrop.classList.remove('opacity-0', 'pointer-events-none');
    backdrop.classList.add('opacity-100');
    box.classList.remove('scale-95', 'opacity-0');
    box.classList.add('scale-100', 'opacity-100');

    fetchOrderMessages();
    if (!chatPollInterval) {
        chatPollInterval = setInterval(fetchOrderMessages, 3500);
    }
    setTimeout(() => {
        const inp = document.getElementById('chat-message-input');
        if (inp) inp.focus();
    }, 300);
}

function closeOrderChatModal() {
    const backdrop = document.getElementById('chat-modal-backdrop');
    const box = document.getElementById('chat-modal-box');
    if (!backdrop || !box) return;

    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0', 'pointer-events-none');
    box.classList.remove('scale-100', 'opacity-100');
    box.classList.add('scale-95', 'opacity-0');

    if (chatPollInterval) {
        clearInterval(chatPollInterval);
        chatPollInterval = null;
    }
}

function fetchOrderMessages() {
    const container = document.getElementById('chat-messages-container');
    if (!container) return;

    fetch(`/orders/${ORDER_NUMBER}/chat/messages`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                renderChatMessages(data.messages);
            }
        })
        .catch(err => console.error('Error fetching chat messages:', err));
}

function renderChatMessages(messages) {
    const container = document.getElementById('chat-messages-container');
    if (!container) return;

    if (!messages || messages.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-400">
                <span class="text-3xl block mb-1">💬</span>
                <p>Belum ada obrolan. Mulai percakapan di bawah!</p>
            </div>
        `;
        return;
    }

    let html = '';
    messages.forEach(msg => {
        if (msg.is_me) {
            html += `
                <div class="flex flex-col items-end">
                    <div class="max-w-[78%] bg-[#0050ae] text-white px-3.5 py-2 rounded-2xl rounded-tr-xs shadow-xs leading-relaxed">
                        ${escapeHtml(msg.message)}
                    </div>
                    <span class="text-[10px] text-gray-400 mt-0.5 mr-1">${msg.created_time} ✓✓</span>
                </div>
            `;
        } else {
            html += `
                <div class="flex flex-col items-start">
                    <span class="text-[10px] font-bold text-gray-500 mb-0.5 ml-1">${escapeHtml(msg.sender_name)}</span>
                    <div class="max-w-[78%] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-800 dark:text-gray-100 px-3.5 py-2 rounded-2xl rounded-tl-xs shadow-xs leading-relaxed">
                        ${escapeHtml(msg.message)}
                    </div>
                    <span class="text-[10px] text-gray-400 mt-0.5 ml-1">${msg.created_time}</span>
                </div>
            `;
        }
    });

    const isNearBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 120;
    container.innerHTML = html;
    if (isNearBottom) {
        container.scrollTop = container.scrollHeight;
    }
}

function handleSendChatMessage(e) {
    e.preventDefault();
    const input = document.getElementById('chat-message-input');
    const sendBtn = document.getElementById('chat-send-btn');
    if (!input || !input.value.trim()) return;

    const text = input.value.trim();
    input.value = '';
    if (sendBtn) sendBtn.disabled = true;

    fetch(`/orders/${ORDER_NUMBER}/chat/send`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: text })
    })
    .then(res => res.json())
    .then(data => {
        if (sendBtn) sendBtn.disabled = false;
        fetchOrderMessages();
    })
    .catch(err => {
        if (sendBtn) sendBtn.disabled = false;
        console.error('Error sending chat message:', err);
    });
}

function escapeHtml(str) {
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

function sendQuickReply(text) {
    const input = document.getElementById('chat-message-input');
    if (!input) return;
    input.value = text;
    const form = document.getElementById('chat-send-form');
    if (form) {
        if (typeof form.requestSubmit === 'function') {
            form.requestSubmit();
        } else {
            handleSendChatMessage({ preventDefault: () => {} });
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('open_chat') === '1') {
        setTimeout(openOrderChatModal, 300);
    }
});
</script>
@endsection
