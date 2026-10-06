@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Marketplace Game & Produk Digital Terpercaya | gian.games')

@section('head')
<style>
    .banner-slide {
        display: none;
        transition: opacity 0.3s ease;
    }
    .banner-slide.active {
        display: block;
    }
    .carousel-indicator.active {
        width: 24px;
        background-color: #00e5c7;
    }
    .product-card {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .product-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    @php
        $products = $myProducts ?? (auth()->user()->products ?? collect());
        $totalProducts = $products->count();
        $totalStock = $products->sum('stock');
        $totalValue = $products->sum(fn($p) => $p->price * $p->stock);
    @endphp

    <div class="relative overflow-hidden rounded-xl border border-teal-900/50 shadow-md bg-gradient-to-r from-[#03222a] via-[#053d45] to-[#044c54] text-white group" id="hero-carousel">
        <div class="banner-slide active p-6 sm:p-8 relative">
            <div class="relative z-10 max-w-2xl space-y-3">
                <div class="flex items-center gap-2.5 text-xs text-teal-200">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-teal-400/10 border border-teal-400/20 text-teal-300 font-semibold tracking-wide uppercase text-[10px]">
                        Flash Deals
                    </span>
                    <span class="text-teal-100/70">Berakhir dalam:</span>
                    <span id="flash-sale-timer" class="font-mono font-bold text-white bg-black/25 px-2 py-0.5 rounded-md border border-white/10">02 : 45 : 12</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white leading-tight font-brand">
                    Diskon s/d 50% Top Up Game & Produk Digital
                </h1>
                <p class="text-xs sm:text-sm text-teal-100/80 leading-relaxed font-sans font-normal max-w-xl">
                    Beli Robux Instan, Diamond Mobile Legends, Steam Key, dan item game terpopuler dengan harga terbaik dan proses transaksi terverifikasi.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-3.5">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center bg-[#00e5c7] hover:bg-[#00c9ad] text-gray-950 font-bold text-xs px-4 py-2.5 rounded-md transition-colors">
                        Lihat Katalog Promo
                    </a>
                    <span class="text-xs text-teal-100/80">
                        Garansi pengiriman instan dan perlindungan transaksi resmi
                    </span>
                </div>
            </div>
        </div>

        <div class="banner-slide p-6 sm:p-8 relative">
            <div class="relative z-10 max-w-2xl space-y-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-cyan-400/10 border border-cyan-400/20 text-cyan-300 font-semibold tracking-wide uppercase text-[10px]">
                    Pengiriman Otomatis
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white leading-tight font-brand">
                    Kode Voucher Langsung Diterima
                </h2>
                <p class="text-xs sm:text-sm text-teal-100/80 leading-relaxed font-sans font-normal max-w-xl">
                    Sistem pemrosesan pesanan berjalan 24 jam. Selesaikan pembayaran melalui QRIS, transfer bank, atau e-wallet untuk menerima kode seketika.
                </p>
                <div class="pt-2">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center bg-white hover:bg-gray-100 text-gray-950 font-bold text-xs px-4 py-2.5 rounded-md transition-colors">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        </div>

        <div class="banner-slide p-6 sm:p-8 relative">
            <div class="relative z-10 max-w-2xl space-y-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-400/10 border border-amber-400/20 text-amber-300 font-semibold tracking-wide uppercase text-[10px]">
                    Mitra Penjual
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white leading-tight font-brand">
                    Buka Lapak Game dan Mulai Berjualan
                </h2>
                <p class="text-xs sm:text-sm text-teal-100/80 leading-relaxed font-sans font-normal max-w-xl">
                    Bergabung dengan platform seller resmi. Verifikasi cepat, kelola produk secara mandiri, dan tarik hasil penjualan setiap hari.
                </p>
                <div class="pt-2">
                    <a href="{{ route('become-seller') }}" class="inline-flex items-center bg-[#00e5c7] hover:bg-[#00c9ad] text-gray-950 font-bold text-xs px-4 py-2.5 rounded-md transition-colors">
                        Daftar Jadi Penjual
                    </a>
                </div>
            </div>
        </div>

        <button type="button" onclick="prevBannerSlide()" aria-label="Slide sebelumnya" class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-md bg-black/40 hover:bg-black/70 text-white flex items-center justify-center text-sm opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" onclick="nextBannerSlide()" aria-label="Slide berikutnya" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-md bg-black/40 hover:bg-black/70 text-white flex items-center justify-center text-sm opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20" id="carousel-dots-container">
            <button type="button" onclick="setBannerSlide(0)" aria-label="Navigasi slide 1" class="carousel-indicator active w-6 h-1 rounded-sm bg-[#00e5c7] transition-all cursor-pointer"></button>
            <button type="button" onclick="setBannerSlide(1)" aria-label="Navigasi slide 2" class="carousel-indicator w-2 h-1 rounded-sm bg-white/40 hover:bg-white/70 transition-all cursor-pointer"></button>
            <button type="button" onclick="setBannerSlide(2)" aria-label="Navigasi slide 3" class="carousel-indicator w-2 h-1 rounded-sm bg-white/40 hover:bg-white/70 transition-all cursor-pointer"></button>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-5 lg:grid-cols-10 gap-2.5 sm:gap-3">
        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Top Up</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Game Key</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Akun Game</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Voucher</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 p-1.5 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 transition-colors shrink-0">
                <img src="{{ asset('images/categories/roblox.png') }}" alt="Roblox" class="w-full h-full object-contain">
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Roblox</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Item Game</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Investasi</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">RPG</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 p-1.5 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 transition-colors shrink-0">
                <img src="{{ asset('images/categories/steam.png') }}" alt="Steam Key" class="w-full h-full object-contain">
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Steam Key</span>
        </a>

        <a href="{{ route('products.index') }}" class="group flex flex-col items-center justify-center p-3 rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 hover:border-blue-500/40 dark:hover:border-teal-500/40 hover:bg-gray-50/70 dark:hover:bg-[#15232d] transition-all text-center">
            <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800/90 text-gray-700 dark:text-gray-300 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-teal-950/60 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
            </div>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 group-hover:text-[#0050ae] dark:group-hover:text-[#00e5c7] transition-colors truncate w-full mt-2">Simulasi</span>
        </a>
    </div>

    @if(isset($recentOrders) && $recentOrders->isNotEmpty())
    <div class="bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 rounded-lg p-5 space-y-4">
        <div class="flex items-center justify-between pb-1">
            <div class="flex items-center gap-2">
                <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white leading-tight font-brand">
                    Pesanan Terkini
                </h2>
                <span class="text-[10px] bg-emerald-50 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300 font-semibold px-2 py-0.5 rounded-md border border-emerald-200/50 dark:border-emerald-800/40">
                    Terupdate
                </span>
            </div>
            <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-[#0050ae] dark:text-[#00e5c7] hover:underline">
                Lihat Semua Pesanan
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($recentOrders as $order)
                <div class="bg-white dark:bg-[#0b161c] border border-gray-200 dark:border-gray-800 rounded-lg p-3.5 flex items-center justify-between gap-3 hover:border-gray-300 dark:hover:border-gray-700 transition-colors">
                    <div class="min-w-0 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                        <div class="min-w-0 space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-mono text-gray-500 dark:text-gray-400">{{ $order->order_number }}</span>
                                @if($order->status === 'completed')
                                    <span class="inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-200/40">
                                        COMPLETED
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 dark:bg-blue-950/70 dark:text-blue-300 border border-blue-200/40">
                                        PROCESSING
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-[10px] font-semibold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 dark:bg-amber-950/70 dark:text-amber-300 border border-amber-200/40">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $order->product_name }}</h3>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $order->store ? $order->store->name : 'Penjual' }} • <strong class="text-gray-900 dark:text-gray-100 font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></p>
                        </div>
                    </div>
                    <a href="{{ route('orders.show', $order->order_number) }}"
                       class="inline-flex items-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 text-xs font-semibold px-3 py-1.5 rounded-md transition-colors shrink-0">
                        Detail
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(isset($marketplaceProducts) && $marketplaceProducts->isNotEmpty())
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white leading-tight font-brand">Produk Game Terpopuler</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Pengiriman instan dan kode voucher resmi 24 jam</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-semibold text-[#0050ae] dark:text-[#00e5c7] hover:underline">
                Lihat Katalog
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
            @foreach($marketplaceProducts as $item)
                <div class="product-card p-3 flex flex-col justify-between rounded-lg bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 relative group">
                    <div>
                        <div class="relative w-full aspect-square rounded-md overflow-hidden bg-gray-100 dark:bg-gray-800 mb-2.5">
                            @if ($item->image && Storage::disk('public')->exists($item->image))
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-800 flex items-center justify-center text-white font-bold text-2xl">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            @endif

                            @if($item->delivery_type === 'regular')
                                <span class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                    Reguler
                                </span>
                            @else
                                <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                    Instan
                                </span>
                            @endif

                            <div class="absolute bottom-2 left-2 bg-gray-950/80 text-white text-[10px] font-medium px-1.5 py-0.5 rounded-md flex items-center gap-1 border border-white/10">
                                <span class="text-amber-400">★</span>
                                <span>4.9</span>
                            </div>
                        </div>

                        <h3 class="font-bold text-xs text-gray-900 dark:text-white truncate" title="{{ $item->name }}">{{ $item->name }}</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate mt-0.5">
                            {{ $item->store ? $item->store->name : ($item->user?->name ?? 'Toko') }}
                        </p>
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-[#ff5e00]">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded-md">Stok: {{ $item->stock }}</span>
                        </div>

                        @if($item->user_id !== auth()->id())
                            <div class="flex items-center gap-1.5 pt-0.5">
                                <button type="button"
                                        data-product-id="{{ $item->id }}"
                                        onclick="addToCart(Number(this.dataset.productId), 1)"
                                        class="p-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-md transition-colors shrink-0 cursor-pointer"
                                        title="Tambah ke Keranjang Belanja">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>
                                <form action="{{ route('orders.buy', $item) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full py-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-semibold text-xs rounded-md transition-colors flex items-center justify-center cursor-pointer">
                                        Beli Langsung
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('products.edit', $item) }}"
                               class="w-full py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-xs rounded-md text-center block transition-colors">
                                Kelola Produk
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
    <div class="bg-white dark:bg-[#111c24] border border-gray-200 dark:border-gray-800 rounded-lg p-5 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100 dark:border-gray-800">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white font-brand">
                        Ringkasan Lapak dan Inventaris
                    </h2>
                    <span class="bg-emerald-50 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 text-[10px] font-semibold px-2 py-0.5 rounded-md border border-emerald-200/50 dark:border-emerald-800/40">
                        Seller Aktif
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kelola stok dan produk jualan digital Anda secara real-time</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 bg-[#0050ae] hover:bg-[#003d87] text-white text-xs font-semibold px-3.5 py-2 rounded-md transition-colors">
                    <span>+</span>
                    <span>Tambah Produk</span>
                </a>
                <a href="{{ route('products.index') }}" class="inline-flex items-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-semibold px-3.5 py-2 rounded-md transition-colors">
                    Kelola Semua
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="bg-gray-50/70 dark:bg-[#0b161c] border border-gray-200 dark:border-gray-800 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Produk Terdaftar</span>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $totalProducts }}</div>
                </div>
                <div class="w-10 h-10 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#0050ae] dark:text-blue-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>

            <div class="bg-gray-50/70 dark:bg-[#0b161c] border border-gray-200 dark:border-gray-800 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Unit Stok</span>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ number_format($totalStock) }}</div>
                </div>
                <div class="w-10 h-10 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>

            <div class="bg-gray-50/70 dark:bg-[#0b161c] border border-gray-200 dark:border-gray-800 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Estimasi Nilai Inventaris</span>
                    <div class="text-2xl font-bold text-[#ff5e00] mt-0.5">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
                </div>
                <div class="w-10 h-10 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-gradient-to-r from-[#03222a] via-[#053d45] to-[#044c54] border border-teal-500/20 rounded-lg p-6 sm:p-7 text-white flex flex-col sm:flex-row items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-md bg-white/10 border border-white/10 flex items-center justify-center text-teal-300 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold font-brand">Mulai Berjualan di gian.games</h2>
                <p class="text-xs sm:text-sm text-teal-100/80 mt-0.5">Daftarkan akun penjual dan jangkau pembeli produk game di seluruh Indonesia.</p>
            </div>
        </div>
        <a href="{{ route('become-seller') }}"
           class="shrink-0 inline-flex items-center bg-[#00e5c7] hover:bg-[#00c9ad] text-gray-950 font-bold text-xs px-5 py-2.5 rounded-md transition-colors">
            Daftar Jadi Penjual
        </a>
    </div>
    @endif

</div>

<script>
var currentSlide = 0;
var bannerSlides = document.querySelectorAll('.banner-slide');
var carouselDots = document.querySelectorAll('.carousel-indicator');
var autoSlideTimer = null;

function showSlide(index) {
    if (!bannerSlides.length) return;
    if (index >= bannerSlides.length) index = 0;
    if (index < 0) index = bannerSlides.length - 1;

    bannerSlides.forEach(function(slide, i) {
        if (i === index) {
            slide.classList.add('active');
        } else {
            slide.classList.remove('active');
        }
    });

    carouselDots.forEach(function(dot, i) {
        if (i === index) {
            dot.classList.add('active');
            dot.classList.remove('bg-white/40');
            dot.classList.add('bg-[#00e5c7]');
        } else {
            dot.classList.remove('active');
            dot.classList.add('bg-white/40');
            dot.classList.remove('bg-[#00e5c7]');
        }
    });

    currentSlide = index;
}

function nextBannerSlide() {
    showSlide(currentSlide + 1);
}

function prevBannerSlide() {
    showSlide(currentSlide - 1);
}

function setBannerSlide(index) {
    showSlide(index);
    resetAutoSlide();
}

function startAutoSlide() {
    autoSlideTimer = setInterval(nextBannerSlide, 5000);
}

function resetAutoSlide() {
    clearInterval(autoSlideTimer);
    startAutoSlide();
}

var carouselEl = document.getElementById('hero-carousel');
if (carouselEl) {
    carouselEl.addEventListener('mouseenter', function() {
        clearInterval(autoSlideTimer);
    });
    carouselEl.addEventListener('mouseleave', function() {
        startAutoSlide();
    });
}

startAutoSlide();

(function() {
    var timerEl = document.getElementById('flash-sale-timer');
    if (!timerEl) return;

    var totalSeconds = 2 * 3600 + 45 * 60 + 12;
    setInterval(function() {
        totalSeconds--;
        if (totalSeconds < 0) totalSeconds = 3 * 3600;
        var h = Math.floor(totalSeconds / 3600);
        var m = Math.floor((totalSeconds % 3600) / 60);
        var s = totalSeconds % 60;
        timerEl.textContent = 
            (h < 10 ? '0' + h : h) + ' : ' + 
            (m < 10 ? '0' + m : m) + ' : ' + 
            (s < 10 ? '0' + s : s);
    }, 1000);
})();
</script>
@endsection
