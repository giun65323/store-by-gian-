@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Marketplace Game & Produk Digital Terpercaya | gian games')

@section('head')
<style>
    /* Carousel transitions */
    .banner-slide {
        display: none;
        opacity: 0;
        transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .banner-slide.active {
        display: flex;
        opacity: 1;
    }
    .carousel-indicator.active {
        width: 28px;
        background-color: #ffb800;
    }

    /* Product Card elevation */
    .product-card {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.25s ease,
                    border-color 0.2s ease;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px -6px rgba(0, 80, 174, 0.12), 0 4px 12px -2px rgba(0, 0, 0, 0.05);
    }

    /* Category card elevation */
    .category-chip {
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.2s ease,
                    border-color 0.2s ease;
    }
    .category-chip:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -4px rgba(0, 80, 174, 0.15);
    }

</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">


    <!-- ========================================================================= -->
    <!-- 1. HERO PROMO BANNER (STATIS, ELEGAN & BERSIH)                             -->
    <!-- ========================================================================= -->
    <div class="relative overflow-hidden rounded-2xl border border-purple-500/25 shadow-xl bg-gradient-to-br from-[#12072b] via-[#240e4f] to-[#0c051d] text-white group" id="hero-carousel">
        <!-- Ambient Background Light Orbs (Statis tanpa animasi denyut) -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 left-1/3 w-80 h-80 bg-violet-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-12 w-64 h-64 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <!-- SLIDE 1: PROMO UTAMA (STATIS TANPA FLASH DEAL) -->
        <div class="banner-slide active p-6 sm:p-8 lg:p-10 flex items-center justify-start relative z-10">
            <!-- Left Info Column -->
            <div class="max-w-2xl space-y-4 text-left">
                <!-- Badge Platform Resmi -->
                <div class="flex flex-wrap items-center gap-2.5 text-xs">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/20 border border-purple-400/30 text-purple-200 font-extrabold text-[11px] tracking-wider uppercase">
                        <span>🎮</span>
                        <span>Marketplace Game Terpercaya</span>
                    </span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight font-brand">
                    Top Up Game &amp; Produk Digital Resmi
                </h1>

                <!-- Subtitle / Value Prop -->
                <p class="text-xs sm:text-sm text-purple-100/85 leading-relaxed font-sans max-w-lg">
                    Beli Robux Instan, Diamond Mobile Legends, Steam Key, dan item game terpopuler dengan harga termurah dan proses transaksi otomatis 24 jam.
                </p>

                <!-- Value Props Micro-Pills -->
                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <span class="inline-flex items-center gap-1 text-[11px] bg-white/10 border border-white/10 px-2.5 py-1 rounded-md text-purple-200 font-semibold">
                        <span>⚡</span>
                        <span>Pengiriman Cepat</span>
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] bg-white/10 border border-white/10 px-2.5 py-1 rounded-md text-emerald-200 font-semibold">
                        <span>🛡️</span>
                        <span>Garansi Saldo 100%</span>
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] bg-white/10 border border-white/10 px-2.5 py-1 rounded-md text-amber-200 font-semibold">
                        <span>💳</span>
                        <span>QRIS &amp; E-Wallet</span>
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="pt-3 flex flex-wrap items-center gap-3">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 via-amber-300 to-[#ffb800] hover:from-amber-300 hover:to-amber-500 text-gray-950 font-black text-xs sm:text-sm px-5 py-3 rounded-xl shadow-lg shadow-amber-500/20 hover:shadow-amber-500/35 transition-all hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                        <span>🛍️</span>
                        <span>Lihat Semua Produk</span>
                    </a>
                    <a href="{{ route('orders.index') }}"
                       class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/15 border border-white/20 text-white font-bold text-xs sm:text-sm px-4 py-3 rounded-xl transition-all cursor-pointer">
                        <span>Lacak Pesanan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- SLIDE 2: PROSES OTOMATIS 24 JAM -->
        <div class="banner-slide p-6 sm:p-8 lg:p-10 flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <div class="max-w-xl space-y-4 text-left">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-purple-400/20 border border-purple-400/40 text-purple-200 font-bold text-xs uppercase tracking-wider">
                    <span>⚡</span>
                    <span>Proses Otomatis 24 Jam</span>
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight font-brand">
                    Kode Voucher dan Item Langsung Diterima
                </h2>
                <p class="text-xs sm:text-sm text-purple-100/90 leading-relaxed font-sans max-w-lg">
                    Sistem pemrosesan transaksi otomatis siap melayani pesanan Anda tanpa henti. Pembayaran melalui QRIS, Bank Transfer, dan E-Wallet terverifikasi seketika.
                </p>
                <div class="pt-2">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-gray-950 font-bold text-xs sm:text-sm px-5 py-3 rounded-xl transition-all shadow-md">
                        <span>Mulai Belanja Sekarang</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Slide 2 Visual Showcase -->
            <div class="w-full md:w-auto flex justify-center items-center">
                <div class="bg-gradient-to-b from-[#240e4f] to-[#12072b] border border-white/10 rounded-2xl p-5 shadow-2xl space-y-3 w-64 sm:w-72">
                    <div class="text-xs font-bold text-white flex items-center justify-between">
                        <span>Metode Pembayaran Instan</span>
                        <span class="text-emerald-400 text-[10px]">Aktif</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-center text-[11px] font-bold">
                        <div class="bg-white/10 p-2.5 rounded-lg border border-white/10 text-white">QRIS Bebas Biaya</div>
                        <div class="bg-white/10 p-2.5 rounded-lg border border-white/10 text-white">Transfer Bank 24J</div>
                        <div class="bg-white/10 p-2.5 rounded-lg border border-white/10 text-white">DANA / OVO</div>
                        <div class="bg-white/10 p-2.5 rounded-lg border border-white/10 text-white">ShopeePay</div>
                    </div>
                    <p class="text-[10px] text-gray-300 text-center">Verifikasi otomatis tanpa perlu upload struk manual</p>
                </div>
            </div>
        </div>

        <!-- SLIDE 3: MITRA PENJUAL (SELLER PARTNER) -->
        <div class="banner-slide p-6 sm:p-8 lg:p-10 flex-col md:flex-row items-center justify-between gap-8 relative z-10">
            <div class="max-w-xl space-y-4 text-left">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-400/15 border border-amber-400/30 text-amber-300 font-bold text-xs uppercase tracking-wider">
                    <span>🏬</span>
                    <span>Mitra Penjual Resmi</span>
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white leading-tight font-brand">
                    Buka Lapak Game dan Mulai Berjualan Hari Ini
                </h2>
                <p class="text-xs sm:text-sm text-purple-100/90 leading-relaxed font-sans max-w-lg">
                    Jangkau ribuan gamers di seluruh Indonesia. Verifikasi identitas mudah, kelola stok fleksibel, dan nikmati pencairan dana harian langsung ke rekening.
                </p>
                <div class="pt-2">
                    <a href="{{ route('become-seller') }}"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 to-[#ffb800] hover:from-amber-300 hover:to-amber-500 text-gray-950 font-black text-xs sm:text-sm px-5 py-3 rounded-xl shadow-lg transition-all">
                        <span>Daftar Jadi Penjual</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <!-- Slide 3 Visual Showcase -->
            <div class="w-full md:w-auto flex justify-center items-center">
                <div class="bg-gradient-to-b from-[#240e4f] to-[#12072b] border border-white/10 rounded-2xl p-5 shadow-2xl space-y-3 w-64 sm:w-72 text-left">
                    <div class="flex items-center gap-2.5 pb-2 border-b border-white/10">
                        <span class="text-2xl">📈</span>
                        <div>
                            <h4 class="text-xs font-bold text-white">Keuntungan Penjual</h4>
                            <p class="text-[10px] text-purple-200">gian games Partner</p>
                        </div>
                    </div>
                    <ul class="space-y-1.5 text-[11px] text-gray-200">
                        <li class="flex items-center gap-1.5"><span>✓</span> <span>0% Biaya Registrasi Toko</span></li>
                        <li class="flex items-center gap-1.5"><span>✓</span> <span>Pencairan Dana Setiap Hari</span></li>
                        <li class="flex items-center gap-1.5"><span>✓</span> <span>Notifikasi Chat & Pesanan Real-Time</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Arrow Controls -->
        <button type="button"
                onclick="prevBannerSlide()"
                aria-label="Slide sebelumnya"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/40 hover:bg-black/75 border border-white/15 text-white flex items-center justify-center text-sm opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-20 backdrop-blur-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button"
                onclick="nextBannerSlide()"
                aria-label="Slide berikutnya"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/40 hover:bg-black/75 border border-white/15 text-white flex items-center justify-center text-sm opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-20 backdrop-blur-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Carousel Indicators (Pills) -->
        <div class="absolute bottom-3.5 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20" id="carousel-dots-container">
            <button type="button" onclick="setBannerSlide(0)" aria-label="Navigasi promo 1" class="carousel-indicator active w-7 h-1.5 rounded-full bg-amber-400 transition-all cursor-pointer"></button>
            <button type="button" onclick="setBannerSlide(1)" aria-label="Navigasi promo 2" class="carousel-indicator w-2.5 h-1.5 rounded-full bg-white/40 hover:bg-white/70 transition-all cursor-pointer"></button>
            <button type="button" onclick="setBannerSlide(2)" aria-label="Navigasi promo 3" class="carousel-indicator w-2.5 h-1.5 rounded-full bg-white/40 hover:bg-white/70 transition-all cursor-pointer"></button>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- 2. QUICK FILTER GAME CATEGORIES (TACTILE, DISTINCT ACCENTS, RESPONSIVE)   -->
    <!-- ========================================================================= -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-lg">🎮</span>
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white font-brand tracking-tight">
                        Kategori & Game Pilihan
                    </h2>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Pilih kategori item game dan voucher favorit Anda</p>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-[#7c3aed] dark:text-[#ffb800] hover:underline flex items-center gap-1">
                <span>Lihat Semua</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-5 lg:grid-cols-10 gap-2.5 sm:gap-3">
            <!-- 1. Top Up Game -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/50 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <span class="absolute top-2 right-2 text-[8px] bg-[#581c87] text-white font-black px-1.5 py-0.5 rounded-full shadow-xs uppercase tracking-wider">POPULER</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-950/70 dark:to-violet-950/50 text-[#581c87] dark:text-purple-300 flex items-center justify-center p-2.5 my-1 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Top Up Game</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Instan 24J</span>
            </a>

            <!-- 2. Roblox -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/50 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <span class="absolute top-2 right-2 text-[8px] bg-rose-600 text-white font-black px-1.5 py-0.5 rounded-full shadow-xs uppercase tracking-wider">HOT</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-slate-800 dark:to-purple-950/70 p-2 my-1 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                    <img src="{{ asset('images/categories/roblox.png') }}" alt="Roblox" class="w-full h-full object-contain">
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Roblox</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Robux 5 Hari</span>
            </a>

            <!-- 3. Steam Key -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 p-2 my-1 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                    <img src="{{ asset('images/categories/steam.png') }}" alt="Steam" class="w-full h-full object-contain">
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Steam Key</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Wallet & Game</span>
            </a>

            <!-- 4. Akun Game -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-950/60 dark:to-indigo-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center p-2.5 my-1 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Akun Game</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Sultan & Polosan</span>
            </a>

            <!-- 5. Item Game -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-50 to-orange-50 dark:from-rose-950/60 dark:to-orange-950/60 p-2 my-1 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                    <img src="{{ asset('images/categories/sword.png') }}" alt="Item Game" class="w-full h-full object-contain">
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Item Game</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Skin & Senjata</span>
            </a>

            <!-- 6. Voucher Digital -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/60 dark:to-teal-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center p-2.5 my-1 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Voucher</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Google & Razer</span>
            </a>

            <!-- 7. Game Key -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-50 to-yellow-100 dark:from-amber-950/60 dark:to-yellow-950/60 p-2 my-1 flex items-center justify-center group-hover:scale-110 transition-transform shadow-xs">
                    <img src="{{ asset('images/categories/game-key.png') }}" alt="Game Key" class="w-full h-full object-contain">
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Game Key</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">PC & Konsol</span>
            </a>

            <!-- 8. Growtopia / Investasi -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-yellow-50 to-amber-100 dark:from-yellow-950/60 dark:to-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center p-2.5 my-1 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Investasi</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Growtopia DL</span>
            </a>

            <!-- 9. Mobile Legends WDP -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <span class="absolute top-2 right-2 text-[8px] bg-emerald-600 text-white font-black px-1.5 py-0.5 rounded-full shadow-xs uppercase tracking-wider">HEMAT</span>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-50 to-indigo-100 dark:from-purple-950/60 dark:to-indigo-950/60 flex items-center justify-center p-1 my-1 group-hover:scale-110 transition-transform shadow-xs">
                    <img src="{{ asset('images/categories/mlbb.png') }}" alt="MLBB" class="w-full h-full object-contain">
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">MLBB WDP</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Weekly Pass</span>
            </a>

            <!-- 10. Free Fire -->
            <a href="{{ route('products.index') }}" class="category-chip group relative flex flex-col items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 hover:border-[#581c87]/60 dark:hover:border-purple-400/50 shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-200 text-center overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-50 to-amber-100 dark:from-amber-100 dark:to-orange-100 flex items-center justify-center p-1.5 my-1 group-hover:scale-110 transition-transform shadow-xs">
                    <img src="{{ asset('images/categories/free-fire.png') }}" alt="Free Fire" class="w-full h-full object-contain">
                </div>
                <span class="text-xs font-bold text-gray-900 dark:text-gray-100 group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors truncate w-full mt-1">Free Fire</span>
                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full">Diamond Kilat</span>
            </a>
        </div>
    </div>


    <!-- ========================================================================= -->
    <!-- 3. PESANAN TERKINI (JIKA ADA RIWAYAT TRANSAKSI USER)                       -->
    <!-- ========================================================================= -->
    @if(isset($recentOrders) && $recentOrders->isNotEmpty())
    <div class="bg-white dark:bg-[#101422] border border-gray-200/90 dark:border-gray-800 rounded-2xl p-5 sm:p-6 space-y-4 shadow-sm">
        <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-sm">
                    📦
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white font-brand">
                        Pesanan Terkini Anda
                    </h2>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Pantau progres pengiriman dan status transaksi akun Anda</p>
                </div>
            </div>
            <a href="{{ route('orders.index') }}" class="text-xs font-bold text-[#7c3aed] dark:text-[#ffb800] hover:underline flex items-center gap-1">
                <span>Lihat Semua Pesanan</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
            @foreach($recentOrders as $order)
                <div class="bg-gray-50/70 dark:bg-[#0c101d] border border-gray-200/80 dark:border-gray-800 rounded-xl p-3.5 flex items-center justify-between gap-3 hover:border-purple-400/50 dark:hover:border-purple-500/40 transition-colors">
                    <div class="min-w-0 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 flex items-center justify-center shrink-0 border border-gray-200 dark:border-gray-700 shadow-xs">
                            <svg class="w-5 h-5 text-[#581c87] dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                        <div class="min-w-0 space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-mono font-bold text-gray-600 dark:text-gray-400">{{ $order->order_number }}</span>
                                @if($order->status === 'completed')
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-200/50">
                                        SELESAI
                                    </span>
                                @elseif($order->status === 'processing')
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 dark:bg-purple-950/70 dark:text-purple-300 border border-purple-200/50">
                                        DIPROSES
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 dark:bg-amber-950/70 dark:text-amber-300 border border-amber-200/50">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $order->product_name }}</h3>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                                Toko: <strong class="text-gray-800 dark:text-gray-200">{{ $order->store ? $order->store->name : 'Penjual' }}</strong> • Total: <strong class="text-[#ff5e00] font-black">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('orders.show', $order->order_number) }}"
                       class="inline-flex items-center bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100 text-xs font-bold px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 transition-all shrink-0">
                        Detail
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif


    <!-- ========================================================================= -->
    <!-- 4. PRODUK GAME TERPOPULER (MARKETPLACE SHOWCASE LEVEL CODASHOP / ITEMKU)  -->
    <!-- ========================================================================= -->
    @if(isset($marketplaceProducts) && $marketplaceProducts->isNotEmpty())
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-1">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-lg">🔥</span>
                    <h2 class="text-base sm:text-lg font-extrabold text-gray-900 dark:text-white font-brand">
                        Produk Game Terpopuler
                    </h2>
                    <span class="bg-rose-500/10 text-rose-600 dark:text-rose-400 text-[10px] font-extrabold px-2 py-0.5 rounded-md border border-rose-500/20">
                        Paling Laris
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pengiriman instan bergaransi dan kode voucher resmi 24 jam</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-[#581c87] dark:text-[#c084fc] hover:underline flex items-center gap-1 self-start sm:self-auto">
                <span>Lihat Semua Katalog</span>
                <span>→</span>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
            @foreach($marketplaceProducts as $item)
                <div class="product-card p-3 flex flex-col justify-between rounded-2xl bg-white dark:bg-[#101422] border border-gray-200/90 dark:border-gray-800 relative group">
                    <div>
                        <!-- Thumbnail Image -->
                        <!-- Product Image -->
                        <a href="{{ route('products.show', $item) }}" class="block relative w-full aspect-square rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 mb-2.5 group/img">
                            @if ($item->image && Storage::disk('public')->exists($item->image))
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#2e1065] to-purple-900 flex items-center justify-center text-white font-black text-2xl group-hover:scale-105 transition-transform duration-300">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                            @endif

                            <!-- Delivery Type Badge -->
                            @if($item->delivery_type === 'regular')
                                <span class="absolute top-2 left-2 bg-purple-900/90 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs backdrop-blur-xs flex items-center gap-1">
                                    <span>⏱️</span>
                                    <span>Reguler</span>
                                </span>
                            @else
                                <span class="absolute top-2 left-2 bg-emerald-600/90 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs backdrop-blur-xs flex items-center gap-1">
                                    <span>⚡</span>
                                    <span>Instan</span>
                                </span>
                            @endif

                            <!-- Rating Badge -->
                            <div class="absolute bottom-2 left-2 bg-black/75 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md flex items-center gap-1 border border-white/15 backdrop-blur-xs shadow-xs">
                                <span class="text-amber-400">★</span>
                                <span>{{ number_format($item->averageRating(), 1) }}</span>
                            </div>
                        </a>

                        <!-- Product Info -->
                        <h3 class="font-extrabold text-xs text-gray-900 dark:text-white truncate group-hover:text-[#581c87] dark:group-hover:text-purple-300 transition-colors" title="{{ $item->name }}">
                            <a href="{{ route('products.show', $item) }}" class="hover:underline">
                                {{ $item->name }}
                            </a>
                        </h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate mt-0.5 flex items-center gap-1.5">
                            @if($item->user?->avatar_url)
                                <img src="{{ $item->user->avatar_url }}" alt="{{ $item->user->name }}" class="w-3.5 h-3.5 rounded-full object-cover shrink-0" onerror="this.style.display='none'">
                            @else
                                <span class="text-purple-500 text-[10px]">✓</span>
                            @endif
                            <span class="truncate">{{ $item->store ? $item->store->name : ($item->user?->name ?? 'Toko Resmi') }}</span>
                        </p>
                    </div>

                    <!-- Price & Action Row -->
                    <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-800/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-black text-sm text-[#ff5e00] dark:text-[#ff802b]">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded font-mono">
                                Stok {{ $item->stock }}
                            </span>
                        </div>

                        @if($item->user_id !== auth()->id())
                            <div class="flex items-center gap-1.5 pt-0.5">
                                <button type="button"
                                        data-product-id="{{ $item->id }}"
                                        onclick="addToCart(Number(this.dataset.productId), 1)"
                                        class="p-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-xl transition-colors shrink-0 cursor-pointer"
                                        title="Tambah ke Keranjang Belanja">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>
                                <form action="{{ route('orders.buy', $item) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full py-2 bg-[#581c87] hover:bg-[#4c1d95] text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors flex items-center justify-center cursor-pointer">
                                        Beli
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 pt-0.5">
                                <a href="{{ route('products.show', $item) }}"
                                   class="flex-1 py-2 bg-purple-50 dark:bg-purple-950/60 hover:bg-[#581c87] hover:text-white text-[#581c87] dark:text-purple-300 font-bold text-xs rounded-xl text-center block transition-colors">
                                    Buka Detail
                                </a>
                                <a href="{{ route('products.edit', $item) }}"
                                   class="py-2 px-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs rounded-xl text-center block transition-colors">
                                    Edit
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif


    @if(!auth()->user()->isSeller() && !auth()->user()->isAdmin())
    <!-- Invitation Card for Non-Sellers -->
    <div class="bg-gradient-to-br from-[#1a0833] via-[#2d0e57] to-[#120424] border border-purple-500/25 rounded-2xl p-6 sm:p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl relative overflow-hidden">
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-amber-400/15 border border-amber-400/30 flex items-center justify-center text-amber-300 text-3xl shrink-0 shadow-inner">
                🏬
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-black font-brand text-white">Mulai Berjualan di gian games</h2>
                <p class="text-xs sm:text-sm text-purple-100/90 mt-1 max-w-xl">
                    Daftarkan tokomu dalam hitungan menit, nikmati 0% biaya registrasi, dan dapatkan penghasilan dari penjualan produk game digital.
                </p>
                <div class="flex flex-wrap items-center gap-3 mt-2 text-[11px] text-purple-200">
                    <span class="flex items-center gap-1"><span>✓</span> <span>Verifikasi Cepat</span></span>
                    <span class="flex items-center gap-1"><span>✓</span> <span>Pencairan Harian</span></span>
                    <span class="flex items-center gap-1"><span>✓</span> <span>Jutaan Pembeli Potensial</span></span>
                </div>
            </div>
        </div>
        <a href="{{ route('become-seller') }}"
           class="shrink-0 inline-flex items-center gap-2 bg-gradient-to-r from-amber-400 to-[#ffb800] hover:from-amber-300 hover:to-amber-500 text-gray-950 font-black text-xs sm:text-sm px-6 py-3 rounded-xl shadow-lg transition-all hover:scale-105 cursor-pointer z-10">
            <span>Daftar Jadi Penjual</span>
            <span>→</span>
        </a>
    </div>
    @endif

</div>

<!-- CAROUSEL SCRIPT (MANUAL SAJA, DIEM TANPA GERAK AUTO-SLIDE & TANPA FLASH DEAL) -->
<script>
var currentSlide = 0;
var bannerSlides = document.querySelectorAll('.banner-slide');
var carouselDots = document.querySelectorAll('.carousel-indicator');

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
            dot.classList.add('bg-amber-400');
        } else {
            dot.classList.remove('active');
            dot.classList.add('bg-white/40');
            dot.classList.remove('bg-amber-400');
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
}
</script>
@endsection
