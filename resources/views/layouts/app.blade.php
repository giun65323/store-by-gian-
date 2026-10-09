<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Inline theme init: apply before paint to prevent flash -->
    <script>
    (function(){
        try{
            var t = localStorage.getItem('gg-theme');
            if(t === 'dark') {
                document.documentElement.setAttribute('data-theme','dark');
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.removeAttribute('data-theme');
                document.documentElement.classList.remove('dark');
            }
        }catch(e){}
    })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'gian games') | Platform Jual Beli Game & Produk Digital Terpercaya</title>
    <meta name="description" content="Tempat jual beli item, akun game, voucher, dan top up game termurah dan terpercaya di Indonesia.">
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        brand: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        itemku: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            500: '#0050ae',
                            600: '#003d87',
                            700: '#072a60',
                            dark: '#051e46',
                            subnav: '#08326e',
                            orange: '#ff5e00',
                            yellow: '#ffb800',
                            gold: '#f59e0b',
                        },
                    },
                },
            },
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/itemku-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fold-text.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dock.css') }}">
    <link rel="stylesheet" href="{{ asset('css/peek-rating.css') }}">
    <style>
        .glass-toast {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.2);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-toast-enter {
            opacity: 0;
            transform: translateX(100%) scale(0.95);
        }
        .glass-toast-show {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        .glass-toast-leave {
            opacity: 0;
            transform: translateX(100%) scale(0.9);
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
    @yield('head')
</head>
<body class="bg-[#f7f9fb] dark:bg-[#090d16] text-gray-900 dark:text-gray-100 min-h-screen flex flex-col antialiased selection:bg-blue-200">

    @auth
    <!-- ===== HEADER ITEMKU STYLE ===== -->
    <header class="itemku-header sticky top-0 z-50 shadow-md">
        <!-- Main Nav Bar -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-4 lg:gap-8">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 shrink-0 group">
                    <div class="flex items-baseline">
                        <span id="logo-fold-text" class="font-brand font-extrabold text-2xl text-white tracking-tight"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-[#ffb800] ml-0.5 shrink-0"></span>
                    </div>
                </a>

                <!-- Search Bar & Tags -->
                <div class="flex-1 max-w-2xl hidden md:block">
                    <form action="{{ route('orders.search') }}" method="POST" class="relative flex items-center w-full bg-white dark:bg-[#101422] rounded-xl border border-white/25 dark:border-gray-800 shadow-sm overflow-hidden focus-within:ring-2 focus-within:ring-[#ffb800] focus-within:border-transparent transition-all">
                        @csrf
                        <div class="pl-3.5 pr-1 text-gray-400 pointer-events-none flex items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text"
                               name="order_number"
                               placeholder="Cari game, voucher, item, atau no. order (OD...)"
                               class="flex-1 bg-transparent text-gray-800 dark:text-gray-100 placeholder-gray-400 text-xs px-2.5 py-2.5 focus:outline-none font-sans">
                        <button type="submit" class="bg-gradient-to-r from-[#ffb800] to-amber-500 hover:from-amber-400 hover:to-amber-500 text-gray-950 font-bold px-4 py-2.5 flex items-center justify-center transition-all shrink-0 text-xs gap-1.5 shadow-xs cursor-pointer active:scale-95" title="Cari Pesanan & Game">
                            <span>Cari</span>
                        </button>
                    </form>
                    <!-- Quick Tags -->
                    <div class="flex items-center gap-1.5 mt-2 overflow-x-auto scrollbar-hide text-[11px]">
                        <span class="text-white/60 font-semibold shrink-0 flex items-center gap-1 mr-0.5">
                            <span>🔥</span>
                            <span>Tren:</span>
                        </span>
                        <a href="{{ route('products.index') }}" class="px-2.5 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white/90 hover:text-white transition-colors border border-white/10 shrink-0 font-medium">Robux 5 Hari</a>
                        <a href="{{ route('products.index') }}" class="px-2.5 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white/90 hover:text-white transition-colors border border-white/10 shrink-0 font-medium">Blox Fruit</a>
                        <a href="{{ route('products.index') }}" class="px-2.5 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white/90 hover:text-white transition-colors border border-white/10 shrink-0 font-medium">Diamond MLBB</a>
                        <a href="{{ route('products.index') }}" class="px-2.5 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white/90 hover:text-white transition-colors border border-white/10 shrink-0 font-medium">Steam Key</a>
                        <a href="{{ route('products.index') }}" class="px-2.5 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white/90 hover:text-white transition-colors border border-white/10 shrink-0 font-medium">Growtopia DL</a>
                        <a href="{{ route('products.index') }}" class="px-2.5 py-0.5 rounded-md bg-white/10 hover:bg-white/20 text-white/90 hover:text-white transition-colors border border-white/10 shrink-0 font-medium">Free Fire</a>
                    </div>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                    <!-- Message Icon with Unread Badge & Dropdown -->
                    <div class="relative" id="header-messages-container">
                        <button type="button"
                                id="header-messages-btn"
                                onclick="toggleChatDropdown()"
                                class="p-2 text-white/90 hover:text-white relative hover:bg-white/10 rounded-xl transition-colors cursor-pointer border border-white/10"
                                title="Pesan & Obrolan Transaksi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <!-- Chat Notification Badge -->
                            <span id="chat-unread-badge"
                                  class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center shadow-xs border border-white/20 transition-all scale-100 hidden animate-pulse">
                                0
                            </span>
                        </button>

                        <!-- Messages Dropdown -->
                        <div id="header-chat-dropdown"
                             class="hidden absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-[#101422] rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 z-[120] overflow-hidden text-gray-900 dark:text-gray-100">
                            <!-- Dropdown Header -->
                            <div class="px-4 py-3 bg-[#0050ae] text-white flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">💬</span>
                                    <h4 class="font-bold text-xs">Pesan & Obrolan Transaksi</h4>
                                </div>
                                <span id="chat-unread-count-text" class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-bold">0 Baru</span>
                            </div>

                            <!-- Messages List -->
                            <div id="chat-dropdown-list" class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800 text-xs">
                                <div class="p-6 text-center text-gray-400">
                                    <span class="inline-block animate-spin text-base">⏳</span>
                                    <p class="text-[11px] mt-1">Memuat obrolan...</p>
                                </div>
                            </div>

                            <!-- Dropdown Footer -->
                            <div class="p-2.5 bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 text-center">
                                <a href="{{ route('orders.index') }}" class="text-[11px] font-bold text-[#0050ae] dark:text-[#ffb800] hover:underline flex items-center justify-center gap-1">
                                    <span>Lihat Semua Pesanan & Transaksi</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Bell -->
                    <button type="button" class="p-2 text-white/90 hover:text-white relative hover:bg-white/10 rounded-xl transition-colors border border-white/10" title="Notifikasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>

                    <!-- Cart -->
                    <button type="button"
                            id="header-cart-btn"
                            onclick="openCartDrawer()"
                            class="p-2 text-white/90 hover:text-white relative hover:bg-white/10 rounded-xl transition-colors border border-white/10 cursor-pointer"
                            title="Keranjang Belanja">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <!-- Cart Notification Badge -->
                        <span id="cart-badge"
                              class="absolute -top-1 -right-1 bg-[#ff5e00] text-white text-[10px] font-black min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center shadow-xs border border-white/20 transition-all scale-100 hidden">
                            0
                        </span>
                    </button>

                    <!-- Theme Toggle Button -->
                    <button type="button"
                            id="theme-toggle"
                            class="theme-toggle cursor-pointer border border-white/10 rounded-xl"
                            title="Ganti Mode Terang / Gelap"
                            aria-label="Toggle dark/light mode">
                        <!-- Moon (shown in light mode -> click to go dark) -->
                        <svg class="icon-moon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                        </svg>
                        <!-- Sun (shown in dark mode -> click to go light) -->
                        <svg class="icon-sun w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                        </svg>
                    </button>

                    @auth
                        @if(auth()->user()->isAdmin())
                        <!-- Admin Panel Button -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-400 to-[#ffb800] hover:from-amber-300 hover:to-amber-400 text-gray-950 text-xs font-black px-3.5 py-2 rounded-xl shadow-sm transition-all hover:scale-105">
                            <span>🛡️</span>
                            <span>Admin</span>
                        </a>
                        @endif

                        <!-- Start Selling / Tambah Produk Button -->
                        @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
                        <a href="{{ route('products.create') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 text-white text-xs font-bold px-3.5 py-2 rounded-xl border border-white/20 shadow-sm transition-all hover:scale-[1.02]">
                            <svg class="w-4 h-4 text-[#ffb800]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Produk</span>
                        </a>
                        @else
                        <a href="{{ route('become-seller') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-400 to-[#ffb800] hover:from-amber-300 hover:to-[#ffa500] text-gray-950 text-xs font-extrabold px-3.5 py-2 rounded-xl shadow-md transition-all hover:scale-[1.02]">
                            <span>🏬</span>
                            <span>Buka Lapak</span>
                        </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 text-white text-xs font-bold px-3.5 py-2 rounded-xl border border-white/20 shadow-sm transition-all">
                            <span>Masuk</span>
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-1.5 bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-black px-3.5 py-2 rounded-xl shadow-sm transition-all">
                            <span>Daftar</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Sub-Nav Categories Bar -->
        <div class="itemku-subnav px-4 sm:px-6 lg:px-8 border-t border-white/10">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 py-2 overflow-x-auto scrollbar-hide text-xs font-medium">
                <!-- Group 1: Core Nav -->
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/15 text-white font-bold hover:bg-white/25 transition-all shrink-0 border border-white/10">
                        <svg class="w-3.5 h-3.5 text-[#ffb800]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Kategori Game</span>
                    </a>
                    @auth
                    <a href="{{ route('orders.index') }}" class="px-3 py-1.5 rounded-lg text-white/85 hover:text-white hover:bg-white/10 transition-all shrink-0 {{ request()->routeIs('orders.*') ? 'bg-white/20 text-[#ffb800] font-bold' : '' }}">
                        Riwayat Transaksi
                    </a>
                    <a href="{{ route('products.index') }}" class="px-3 py-1.5 rounded-lg text-white/85 hover:text-white hover:bg-white/10 transition-all shrink-0 {{ request()->routeIs('products.*') ? 'bg-white/20 text-[#ffb800] font-bold' : '' }}">
                        {{ auth()->user()->isAdmin() ? 'Moderasi Produk' : 'Lapak Saya' }}
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg bg-[#ffb800]/20 border border-[#ffb800]/40 text-[#ffb800] hover:bg-[#ffb800]/30 font-extrabold shrink-0 flex items-center gap-1">
                        <span>🛡️</span>
                        <span>Kontrol Admin</span>
                    </a>
                    @endif
                    @endauth
                </div>

                <!-- Separator -->
                <span class="h-4 w-px bg-white/20 shrink-0 hidden lg:block" aria-hidden="true"></span>

                <!-- Group 2: Quick Trending Game Shortcuts -->
                <div class="flex items-center gap-2 text-white/80 shrink-0">
                    <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 px-2.5 py-1 rounded-md hover:bg-white/10 hover:text-white transition-colors shrink-0">
                        <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                        <span>Roblox</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 px-2.5 py-1 rounded-md hover:bg-white/10 hover:text-white transition-colors shrink-0">
                        <span class="w-2 h-2 rounded-full bg-pink-400"></span>
                        <span>Mobile Legends</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 px-2.5 py-1 rounded-md hover:bg-white/10 hover:text-white transition-colors shrink-0">
                        <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                        <span>Free Fire</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 px-2.5 py-1 rounded-md hover:bg-white/10 hover:text-white transition-colors shrink-0">
                        <span class="w-2 h-2 rounded-full bg-sky-400"></span>
                        <span>Steam Key</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-1.5 px-2.5 py-1 rounded-md hover:bg-white/10 hover:text-white transition-colors shrink-0">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Growtopia</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
    @endauth

    <!-- ===== FLASH MESSAGE ===== -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-xs font-semibold shadow-sm">
                <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 text-sm">✕</button>
            </div>
        </div>
    @endif

    <!-- ===== MAIN CONTENT ===== -->
    <main class="flex-1">
        @yield('content')
    </main>


    <!-- ===== FOOTER ===== -->
    <footer class="bg-white border-t border-gray-200 mt-16 text-xs text-gray-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-bold text-gray-900 mb-3 text-sm">Tentang gian games</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-[#0050ae]">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-[#0050ae]">Aturan Penggunaan</a></li>
                        <li><a href="#" class="hover:text-[#0050ae]">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-[#0050ae]">Karir</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-3 text-sm">Panduan Pembeli</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('help.index') }}?tab=buyer" class="hover:text-[#0050ae]">Cara Membeli</a></li>
                        <li><a href="{{ route('help.index') }}?tab=buyer" class="hover:text-[#0050ae]">Garansi 100%</a></li>
                        <li><a href="{{ route('help.index') }}?tab=buyer" class="hover:text-[#0050ae]">Metode Pembayaran</a></li>
                        <li><a href="{{ route('help.index') }}" class="hover:text-[#0050ae] font-semibold text-[#0050ae]">FAQ & Pusat Bantuan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-3 text-sm">Panduan Penjual</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.create') }}" class="hover:text-[#0050ae]">Cara Berjualan</a></li>
                        <li><a href="{{ route('help.index') }}?tab=seller" class="hover:text-[#0050ae]">Fitur Tokoku</a></li>
                        <li><a href="{{ route('help.index') }}?tab=seller" class="hover:text-[#0050ae]">Ketentuan Penjualan</a></li>
                        <li><a href="{{ route('help.index') }}?tab=seller" class="hover:text-[#0050ae]">Pusat Bantuan Seller</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-3 text-sm">Hubungi Kami</h4>
                    <p class="text-gray-400 mb-3">Layanan pelanggan Gian Care siap sedia 24 jam setiap hari.</p>
                    <a href="{{ route('help.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-[#0050ae] text-white hover:bg-[#003d87] transition-all shadow-xs">
                        <span>🎧 Mulai Chat Bantuan</span>
                    </a>
                </div>
            </div>
    </footer>

    @auth
    <!-- Bottom Dock Navigation (semua layar) -->
    <div id="dock-root"></div>
    @endauth

    @auth
    <!-- ===== ITEMKU STYLE USER PROFILE DRAWER / MODAL ===== -->
    <div id="profile-drawer-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-[100] opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <aside id="profile-drawer"
           class="fixed top-0 right-0 h-full w-full max-w-sm sm:max-w-md bg-[#f8fafd] z-[101] shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col overflow-y-auto">
        
        <!-- Drawer Header (Deep Blue) -->
        <div class="bg-[#0050ae] text-white p-5 relative overflow-hidden shrink-0">
            <!-- Top icons row in drawer -->
            <div class="flex items-center justify-between gap-3 text-white/90 mb-4">
                <button type="button" id="close-profile-drawer" class="p-1 hover:bg-white/10 rounded-full transition-colors text-white" title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex items-center gap-3">
                    <span class="inline-block w-4 h-2.5 bg-red-600 border border-white/50 rounded-xs relative overflow-hidden" title="Indonesia">
                        <span class="absolute inset-x-0 bottom-0 h-1.5 bg-white"></span>
                    </span>
                    <button class="hover:text-white" title="Pesan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </button>
                    <button class="hover:text-white" title="Notifikasi">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </button>
                    <button class="hover:text-white relative" title="Keranjang">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span class="absolute -top-1.5 -right-1.5 w-3.5 h-3.5 bg-[#ff5e00] text-white text-[9px] font-bold rounded-full flex items-center justify-center">1</span>
                    </button>
                    <a href="{{ route('profile.edit') }}" class="hover:text-white p-1 rounded-full hover:bg-white/10 transition-colors" title="Pengaturan Akun & Edit Profile">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Profile Info Row (Click to Edit Profile) -->
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 group cursor-pointer" title="Klik untuk Edit Profile">
                <div class="relative w-14 h-14 rounded-full bg-gradient-to-tr from-sky-400 via-indigo-500 to-purple-600 p-0.5 shadow-md shrink-0 group-hover:scale-105 transition-transform">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-full h-full rounded-full bg-[#08326e] items-center justify-center text-white font-extrabold text-lg" style="display:none;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @else
                        <div class="w-full h-full rounded-full bg-[#08326e] flex items-center justify-center text-white font-extrabold text-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <!-- Floating Pencil Edit Badge -->
                    <span class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full bg-white text-gray-800 shadow-md flex items-center justify-center text-[10px] border border-gray-100 group-hover:bg-[#0050ae] group-hover:text-white transition-colors" title="Edit Profil">
                        ✏️
                    </span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-white leading-tight flex items-center gap-1.5 group-hover:text-amber-300 transition-colors">
                        <span>{{ auth()->user()->name }}</span>
                        <span class="text-xs opacity-70 group-hover:opacity-100">✏️</span>
                    </h3>
                    <div class="flex items-center gap-2 mt-1 text-xs">
                        <div class="flex items-center gap-1 bg-black/20 px-2 py-0.5 rounded-full font-bold text-amber-300">
                            <span>🪙</span> 0 Token
                        </div>
                        <span class="text-white/40">|</span>
                        <div class="flex items-center gap-1 bg-black/20 px-2 py-0.5 rounded-full font-bold text-sky-300">
                            <span>🎟️</span> 0 Coupons
                        </div>
                    </div>
                </div>
            </a>

            <!-- Quest EXP Progress Bar -->
            <div class="mt-4 pt-3 border-t border-white/15">
                <div class="flex items-center justify-between text-[11px] font-semibold text-white/90 mb-1">
                    <span>Quest: <strong class="text-amber-300">Level 1</strong></span>
                    <span class="text-white/70">0/- EXP</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-black/30 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-amber-400 to-[#ffb800] h-full w-2"></div>
                    </div>
                    <span class="text-lg" title="Hadiah Quest">🎁</span>
                </div>
            </div>

        </div>

        <!-- Floating Wallet Cards (3 Column) -->
        <div class="px-4 -mt-3 relative z-10">
            <div class="bg-white rounded-2xl p-3 shadow-md border border-gray-100 grid grid-cols-3 divide-x divide-gray-100 text-center">
                <!-- DANA -->
                <div class="px-2">
                    <a href="https://www.dana.id/" target="_blank" rel="noopener noreferrer" class="group block">
                        <div class="flex items-center justify-center gap-1 text-[11px] font-bold text-sky-600 mb-0.5">
                            <span class="w-3.5 h-3.5 rounded-full bg-sky-500 text-white text-[9px] flex items-center justify-center font-black">D</span>
                            <span>DANA</span>
                        </div>
                        <p class="text-xs font-bold text-blue-600 group-hover:underline">Connect</p>
                    </a>
                </div>
                <!-- OVO -->
                <div class="px-2">
                    <a href="https://www.ovo.id/" target="_blank" rel="noopener noreferrer" class="group block">
                        <div class="flex items-center justify-center gap-1 text-[11px] font-bold text-purple-600 mb-0.5">
                            <span class="w-3.5 h-3.5 rounded-full bg-purple-600 text-white text-[9px] flex items-center justify-center font-black">O</span>
                            <span>OVO</span>
                        </div>
                        <p class="text-xs font-bold text-blue-600 group-hover:underline">Connect</p>
                    </a>
                </div>
                <!-- My Wallet -->
                <div class="px-2">
                    <div class="flex items-center justify-center gap-1 text-[11px] font-bold text-amber-600 mb-0.5">
                        <span>👛</span>
                        <span>My Wallet</span>
                    </div>
                    <p class="text-xs font-bold text-gray-900">Rp0</p>
                </div>
            </div>
        </div>

        <!-- Body Content -->
        <div class="p-4 space-y-4 flex-1">
            
            <!-- Mulai Jualan Button -->
            @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
            {{-- Seller/Admin: langsung ke tambah produk --}}
            <a href="{{ route('products.create') }}"
               class="flex items-center justify-between bg-white hover:bg-blue-50/60 border border-blue-200 text-[#0050ae] rounded-2xl p-3.5 shadow-sm transition-all group">
                <div class="flex items-center gap-2.5 font-bold text-xs">
                    <span class="text-base">🏬</span>
                    <span>Mulai Jualan / Tambah Produk</span>
                </div>
                <svg class="w-4 h-4 text-blue-500 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @else
            {{-- Buyer: ajak daftar sebagai seller --}}
            <a href="{{ route('become-seller') }}"
               class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-2xl p-3.5 shadow-sm transition-all group">
                <div class="flex items-center gap-2.5 font-bold text-xs">
                    <span class="text-base">🏬</span>
                    <div>
                        <span class="block">Mulai Jualan Sekarang</span>
                        <span class="text-[10px] font-normal text-blue-200">Daftar sebagai Seller gratis!</span>
                    </div>
                </div>
                <svg class="w-4 h-4 text-white/80 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @endif

            <!-- Activity History Grid -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3 text-xs">
                    <h4 class="font-bold text-gray-900">Activity History</h4>
                    <a href="{{ route('products.index') }}" class="font-bold text-[#0050ae] hover:underline flex items-center gap-0.5">
                        View All <span class="text-[10px]">»</span>
                    </a>
                </div>
                <div class="grid grid-cols-4 gap-2 text-center text-[11px]">
                    <a href="{{ route('products.index') }}" class="p-2 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">💳</div>
                        <span class="text-gray-600 font-medium leading-tight block">To Be Paid</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="p-2 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🚚</div>
                        <span class="text-gray-600 font-medium leading-tight block">To Be Delivered</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="p-2 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">📦</div>
                        <span class="text-gray-600 font-medium leading-tight block">Delivered</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="p-2 hover:bg-gray-50 rounded-xl transition-colors group">
                        <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🧾</div>
                        <span class="text-gray-600 font-medium leading-tight block">Completed</span>
                    </a>
                </div>
            </div>

            <!-- Support & Lacak Transaksi List -->
            <div class="bg-white rounded-2xl p-3.5 shadow-sm border border-gray-100 space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h4 class="font-bold text-gray-900 text-xs flex items-center gap-1.5">
                        <span>🎧 Support & Bantuan</span>
                    </h4>
                    <span class="text-[10px] bg-blue-50 text-[#0050ae] font-bold px-2 py-0.5 rounded-full">Lacak Pesanan</span>
                </div>

                <!-- Lacak Transaksi Quick Input Form inside Support -->
                <form action="{{ route('orders.search') }}" method="POST" class="bg-[#f8fafd] border border-blue-100 rounded-xl p-3 space-y-2">
                    @csrf
                    <label class="text-[11px] font-bold text-gray-800 flex items-center justify-between">
                        <span>🔍 Lacak Transaksi</span>
                        <span class="text-[10px] text-gray-400 font-normal">Ketik nomor OD...</span>
                    </label>
                    <div class="flex items-center gap-1.5">
                        <input type="text"
                               name="order_number"
                               placeholder="Contoh: OD000000164288180"
                               required
                               class="w-full bg-white border border-gray-200 text-gray-900 text-xs px-3 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0050ae] font-mono placeholder:font-sans placeholder-gray-400">
                        <button type="submit"
                                class="bg-[#0050ae] hover:bg-[#003d87] text-white font-bold text-xs px-3.5 py-2 rounded-lg transition-all shrink-0 shadow-xs active:scale-95">
                            Lacak
                        </button>
                    </div>
                </form>

                <div class="divide-y divide-gray-100 text-xs pt-1">
                    <a href="{{ route('orders.index') }}" class="flex items-center justify-between p-2.5 hover:bg-gray-50 rounded-xl transition-colors text-gray-700 font-medium">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">🎧</span>
                            <div>
                                <span class="block text-gray-900 font-bold">Order Issues</span>
                                <span class="text-[10px] text-gray-400">Kendala & Bantuan Transaksi</span>
                            </div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('help.index') }}" class="flex items-center justify-between p-2.5 hover:bg-blue-50/60 rounded-xl transition-colors text-gray-700 font-medium group">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">❓</span>
                            <span class="font-bold group-hover:text-[#0050ae]">Pusat Bantuan (Help Center)</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-[#0050ae]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center justify-between p-2.5 hover:bg-gray-50 rounded-xl transition-colors text-gray-700 font-medium">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">⚠️</span>
                            <span>Complain List</span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Accordion Menus -->
            <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 divide-y divide-gray-100 text-xs">
                <details class="group p-2 cursor-pointer">
                    <summary class="flex items-center justify-between font-bold text-gray-800 list-none">
                        <span>Information and Community</span>
                        <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="pt-2 text-gray-500 space-y-1.5 pl-1">
                        <p><a href="#" class="hover:text-[#0050ae]">Discord Community</a></p>
                        <p><a href="#" class="hover:text-[#0050ae]">Blog & Promo</a></p>
                    </div>
                </details>
                <details class="group p-2 cursor-pointer">
                    <summary class="flex items-center justify-between font-bold text-gray-800 list-none">
                        <span>About Us</span>
                        <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="pt-2 text-gray-500 space-y-1.5 pl-1">
                        <p><a href="#" class="hover:text-[#0050ae]">Tentang gian games</a></p>
                        <p><a href="#" class="hover:text-[#0050ae]">Aturan & Kebijakan</a></p>
                    </div>
                </details>
            </div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="pt-1">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2.5 rounded-xl transition-colors text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar dari Akun
                </button>
            </form>

        </div>

        <!-- Drawer Bottom Nav Strip -->
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-4 py-2 flex items-center justify-around text-[10px] text-gray-500 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 hover:text-[#0050ae]">
                <span class="text-base">🏠</span>
                <span>Home</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-0.5 hover:text-[#0050ae]">
                <span class="text-base">🤍</span>
                <span>Favorite</span>
            </a>
            <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-0.5 hover:text-[#0050ae]">
                <span class="text-base">🔁</span>
                <span>Transaction</span>
            </a>
            <a href="#" onclick="if(window.openProfileDrawer) window.openProfileDrawer(); return false;" class="flex flex-col items-center gap-0.5 hover:text-[#0050ae]">
                <span class="text-base">🎧</span>
                <span>Support</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('profile.*') ? 'text-[#0050ae] font-bold' : 'hover:text-[#0050ae]' }}" title="Edit Profil">
                <span class="text-base">👤</span>
                <span>Account</span>
            </a>
        </div>

    </aside>
    @endauth

    <!-- ========================================================================= -->
    <!-- ===== CART SLIDE-OVER DRAWER ===== -->
    <!-- ========================================================================= -->
    <div id="cart-drawer-backdrop"
         onclick="closeCartDrawer()"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs z-[120] opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <aside id="cart-drawer"
           class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-[125] shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">

        <!-- Cart Header -->
        <div class="bg-[#0050ae] text-white px-5 py-4 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center gap-2.5">
                <span class="text-xl">🛒</span>
                <div>
                    <h2 class="text-base font-extrabold leading-tight">Keranjang Belanja</h2>
                    <span id="cart-drawer-count" class="text-xs text-blue-100 font-medium">0 barang</span>
                </div>
            </div>
            <button type="button"
                    onclick="closeCartDrawer()"
                    class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-colors"
                    title="Tutup Keranjang">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Cart Item List -->
        <div id="cart-items-container" class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#f7f9fb]">
            <!-- Dynamic Cart Items via JavaScript -->
        </div>

        <!-- Cart Footer -->
        <div id="cart-footer" class="bg-white border-t border-gray-200 p-4 space-y-3 shrink-0 shadow-lg hidden">
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500 font-medium">Total Tagihan</span>
                <span id="cart-total-price" class="text-base font-black text-[#ff5e00]">Rp 0</span>
            </div>
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full py-3 bg-[#0050ae] hover:bg-[#003d87] text-white font-extrabold text-xs rounded-xl shadow-md hover:shadow-lg transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                    <span>Checkout Semua Barang</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Script for Drawer Toggle -->
    <script>
    (function () {
        const drawer = document.getElementById('profile-drawer');
        const backdrop = document.getElementById('profile-drawer-backdrop');
        const closeBtn = document.getElementById('close-profile-drawer');

        function openDrawer() {
            if (!drawer || !backdrop) return;
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-100');
            drawer.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            if (!drawer || !backdrop) return;
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            drawer.classList.add('translate-x-full');
            document.body.style.overflow = '';
        }

        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDrawer();
        });

        window.openProfileDrawer = openDrawer;
    })();
    </script>

    <!-- ===== SCRIPT FOR CART MANAGEMENT ===== -->
    <script>
    (function () {
        const cartDrawer   = document.getElementById('cart-drawer');
        const cartBackdrop = document.getElementById('cart-drawer-backdrop');
        const cartBadge    = document.getElementById('cart-badge');
        const cartCount    = document.getElementById('cart-drawer-count');
        const cartItemsBox = document.getElementById('cart-items-container');
        const cartFooter   = document.getElementById('cart-footer');
        const cartTotalFmt = document.getElementById('cart-total-price');

        function openCartDrawer() {
            if (!cartDrawer || !cartBackdrop) return;
            fetchCart();
            cartBackdrop.classList.remove('opacity-0', 'pointer-events-none');
            cartBackdrop.classList.add('opacity-100');
            cartDrawer.classList.remove('translate-x-full');
            document.body.style.overflow = 'hidden';
        }

        function closeCartDrawer() {
            if (!cartDrawer || !cartBackdrop) return;
            cartBackdrop.classList.remove('opacity-100');
            cartBackdrop.classList.add('opacity-0', 'pointer-events-none');
            cartDrawer.classList.add('translate-x-full');
            document.body.style.overflow = '';
        }

        function updateCartBadge(count) {
            if (!cartBadge) return;
            if (count > 0) {
                cartBadge.textContent = count > 99 ? '99+' : count;
                cartBadge.classList.remove('hidden');
                // Bounce animation
                cartBadge.classList.add('scale-125');
                setTimeout(() => cartBadge.classList.remove('scale-125'), 200);
            } else {
                cartBadge.textContent = '0';
                cartBadge.classList.add('hidden');
            }
        }

        function renderCartView(data) {
            updateCartBadge(data.count || 0);
            if (cartCount) cartCount.textContent = (data.count || 0) + ' barang';
            if (cartTotalFmt) cartTotalFmt.textContent = data.total_price_fmt || 'Rp 0';

            if (!cartItemsBox) return;

            if (!data.items || data.items.length === 0) {
                cartItemsBox.innerHTML = `
                    <div class="text-center py-16 text-gray-400 space-y-3">
                        <span class="text-5xl block">🛍️</span>
                        <h3 class="text-sm font-bold text-gray-700">Keranjang Belanja Kosong</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Yuk cari game dan voucher favoritmu dan tambahkan ke keranjang!</p>
                    </div>
                `;
                if (cartFooter) cartFooter.classList.add('hidden');
                return;
            }

            if (cartFooter) cartFooter.classList.remove('hidden');

            let html = '';
            data.items.forEach(item => {
                const imgHtml = item.image
                    ? `<img src="${item.image}" class="w-full h-full object-cover">`
                    : `<div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-bold flex items-center justify-center text-sm">🎮</div>`;

                html += `
                    <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-xs space-y-2.5">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 border border-gray-100">
                                ${imgHtml}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-xs text-gray-900 truncate">${escapeHtml(item.name)}</h4>
                                <p class="text-[10px] text-gray-400 truncate">${escapeHtml(item.store_name)}</p>
                                <div class="font-extrabold text-xs text-[#ff5e00] mt-0.5">${item.price_format}</div>
                            </div>
                            <button type="button" onclick="removeCartItem(${item.id})" class="p-1 text-gray-400 hover:text-rose-600 rounded transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center justify-between pt-1 border-t border-gray-100 text-xs">
                            <span class="text-[11px] text-gray-400">Subtotal: <b class="text-gray-800">${item.subtotal_fmt}</b></span>
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                <button type="button" onclick="updateCartQty(${item.id}, ${item.quantity - 1})" class="px-2 py-0.5 hover:bg-gray-200 font-bold text-gray-600">-</button>
                                <span class="px-2 py-0.5 font-bold text-xs text-gray-800 min-w-[20px] text-center">${item.quantity}</span>
                                <button type="button" onclick="updateCartQty(${item.id}, ${item.quantity + 1})" class="px-2 py-0.5 hover:bg-gray-200 font-bold text-gray-600" ${item.quantity >= item.max_stock ? 'disabled' : ''}>+</button>
                            </div>
                        </div>
                    </div>
                `;
            });

            cartItemsBox.innerHTML = html;
        }

        function fetchCart() {
            fetch('/cart')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderCartView(data);
                    }
                })
                .catch(err => console.error('Error fetching cart:', err));
        }

        function addToCart(productId, qty) {
            qty = qty || 1;
            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    renderCartView(data);
                    if (window.showToast) {
                        window.showToast('Barang berhasil ditambahkan ke keranjang! 🛒', 'success');
                    }
                } else if (data.message) {
                    if (window.showToast) {
                        window.showToast(data.message, 'warning');
                    }
                }
            })
            .catch(err => console.error('Error adding to cart:', err));
        }

        function updateCartQty(productId, newQty) {
            fetch(`/cart/update/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    renderCartView(data);
                }
            })
            .catch(err => console.error('Error updating cart:', err));
        }

        function removeCartItem(productId) {
            fetch(`/cart/remove/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    renderCartView(data);
                    if (window.showToast) {
                        window.showToast('Barang berhasil dihapus dari keranjang.', 'info');
                    }
                }
            })
            .catch(err => console.error('Error removing from cart:', err));
        }

        function escapeHtml(str) {
            return (str || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        // Global exports
        window.openCartDrawer  = openCartDrawer;
        window.closeCartDrawer = closeCartDrawer;
        window.fetchCart       = fetchCart;
        window.addToCart       = addToCart;
        window.updateCartQty   = updateCartQty;
        window.removeCartItem  = removeCartItem;

        // Fetch cart badge count on initial page load
        document.addEventListener('DOMContentLoaded', fetchCart);
    })();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="{{ asset('js/fold-text.js') }}"></script>
    <script src="{{ asset('js/dock.js') }}"></script>
    <script src="{{ asset('js/peek-rating.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var logoTarget = document.getElementById('logo-fold-text');
        if (logoTarget && window.FoldText) {
            var logoFold = new FoldText(logoTarget, {
                text:          'gian games',
                splitBy:       'char',
                hinge:         'top',
                trigger:       'mount',
                duration:      0.65,
                stagger:       0.045,
                ease:          'power3.out',
                perspective:   700,
                creaseShading: 0.55,
                fontSize:      'inherit',
                fontWeight:    'inherit',
                color:         '#ffffff',
            });

            // Replay animasi jika kursor diarahkan ke logo
            var logoLink = logoTarget.closest('a');
            if (logoLink) {
                logoLink.addEventListener('mouseenter', function () {
                    logoFold.play(false);
                });
            }
        }
    });
    </script>
    <script>
    (function () {
        var dockRoot = document.getElementById('dock-root');
        if (!dockRoot || typeof Dock === 'undefined') return;

        /* ── SVG icon builder ── */
        function svg(path, opts) {
            opts = opts || {};
            var size  = opts.size  || 20;
            var color = opts.color || 'rgba(255,255,255,0.88)';
            var sw    = opts.sw    || 1.75;
            return '<svg xmlns="http://www.w3.org/2000/svg" width="' + size + '" height="' + size + '" viewBox="0 0 24 24" fill="none" stroke="' + color + '" stroke-width="' + sw + '" stroke-linecap="round" stroke-linejoin="round">' + path + '</svg>';
        }

        var path  = window.location.pathname;
        var isHome     = path === '/dashboard' || path === '/';
        var isOrders   = path.startsWith('/orders');
        var isProducts = path.startsWith('/products');
        var isProfile  = path.startsWith('/profile');
        var isHelp     = path.startsWith('/help');

        /* ── Add bottom padding so content is not hidden behind dock ── */
        document.body.style.paddingBottom = '90px';

        window._dockInstance = Dock.mount(dockRoot, {
            panelHeight:   64,
            baseItemSize:  46,
            magnification: 70,
            distance:      150,
            spring: { mass: 0.1, stiffness: 180, damping: 13 },
            items: [
                /* Home */
                {
                    icon:   svg('<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'),
                    label:  'Beranda',
                    href:   '/dashboard',
                    active: isHome,
                },
                /* Orders */
                {
                    icon:   svg('<path d="M9 17H5a2 2 0 0 0-2 2"/><path d="M9 3H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/><rect x="13" y="3" width="8" height="8" rx="2"/><rect x="13" y="13" width="8" height="8" rx="2"/><path d="M5 13v6"/><path d="M2 16h6"/>'),
                    label:  'Transaksi',
                    href:   '/orders',
                    active: isOrders,
                },
                /* Products */
                {
                    icon:   svg('<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>'),
                    label:  'Produk Saya',
                    href:   '/products',
                    active: isProducts,
                },
                /* separator */
                'separator',
                /* Add Product */
                {
                    icon:   svg('<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12">', { color: 'rgba(255,184,0,0.9)' }),
                    label:  'Tambah Produk',
                    href:   '/products/create',
                },
                /* separator */
                'separator',
                /* Help */
                {
                    icon:   svg('<circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>'),
                    label:  'Bantuan',
                    href:   '/help',
                    active: isHelp,
                },
                /* Profile */
                {
                    icon:   svg('<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'),
                    label:  'Profil',
                    active: isProfile,
                    onClick: function() {
                        if (window.openProfileDrawer) window.openProfileDrawer();
                        else window.location.href = '/profile';
                    },
                },
                /* Logout */
                {
                    icon:   svg('<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>'),
                    label:  'Keluar',
                    onClick: function() {
                        var form = document.querySelector('form[action*="logout"]');
                        if (form) form.submit();
                    },
                },
            ],
        });
    })();
    </script>

    <!-- ===== GLOBAL CHAT TOAST & NOTIFICATION SCRIPT ===== -->
    <div id="global-chat-toast"
         class="fixed top-20 right-4 max-w-sm w-full bg-white border-2 border-[#0050ae] text-gray-900 rounded-2xl shadow-2xl p-4 flex items-start gap-3 transform translate-x-full opacity-0 pointer-events-none transition-all duration-300 z-[200]">
        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0050ae] flex items-center justify-center text-lg shrink-0">
            💬
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <h5 class="text-xs font-bold text-gray-900 truncate" id="toast-chat-sender">Pesan Baru Masuk</h5>
                <span class="text-[10px] text-gray-400">Baru saja</span>
            </div>
            <p class="text-xs text-gray-600 line-clamp-2 mt-0.5" id="toast-chat-text">...</p>
            <a href="#" id="toast-chat-link" class="inline-block text-[11px] font-bold text-[#0050ae] hover:underline mt-1.5">
                Buka Chat Sekarang →
            </a>
        </div>
        <button onclick="dismissChatToast()" class="text-gray-400 hover:text-gray-600 text-xs">✕</button>
    </div>

    <script>
    window._isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    (function () {
        let lastUnreadCount = null;
        let audioContext = null;

        function playNotificationChime() {
            try {
                if (!audioContext) {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioContext.state === 'suspended') {
                    audioContext.resume();
                }
                const now = audioContext.currentTime;
                const osc = audioContext.createOscillator();
                const gain = audioContext.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, now); // D5
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.12); // A5
                gain.gain.setValueAtTime(0.15, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.35);
                osc.connect(gain);
                gain.connect(audioContext.destination);
                osc.start(now);
                osc.stop(now + 0.35);
            } catch (e) {}
        }

        window.toggleChatDropdown = function () {
            const dropdown = document.getElementById('header-chat-dropdown');
            if (!dropdown) return;
            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                fetchChatNotifications();
            } else {
                dropdown.classList.add('hidden');
            }
        };

        window.dismissChatToast = function () {
            const toast = document.getElementById('global-chat-toast');
            if (!toast) return;
            toast.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');
            toast.classList.remove('translate-x-0', 'opacity-100');
        };

        function showChatToast(senderName, message, chatUrl) {
            const toast = document.getElementById('global-chat-toast');
            if (!toast) return;
            document.getElementById('toast-chat-sender').textContent = 'Pesan dari ' + senderName;
            document.getElementById('toast-chat-text').textContent = message;
            document.getElementById('toast-chat-link').href = chatUrl;
            toast.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-x-0', 'opacity-100');

            setTimeout(window.dismissChatToast, 6000);
        }

        function fetchChatNotifications() {
            if (!window._isAuthenticated) return;
            fetch('{{ route("orders.chat.notifications") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        updateBadgeAndDropdown(data);
                    }
                })
                .catch(err => console.error('Error fetching chat notifications:', err));
        }

        function updateBadgeAndDropdown(data) {
            const badge = document.getElementById('chat-unread-badge');
            const countText = document.getElementById('chat-unread-count-text');
            const listContainer = document.getElementById('chat-dropdown-list');

            const count = data.unread_count || 0;

            // Trigger notification if unread count increased
            if (lastUnreadCount !== null && count > lastUnreadCount && data.threads && data.threads.length > 0) {
                const latestThread = data.threads[0];
                if (!latestThread.is_me) {
                    playNotificationChime();
                    showChatToast(latestThread.sender_name, latestThread.message, latestThread.chat_url);
                }
            }
            lastUnreadCount = count;

            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            if (countText) {
                countText.textContent = count > 0 ? count + ' Baru' : 'Semua Terbaca';
            }

            if (listContainer && data.threads) {
                if (data.threads.length === 0) {
                    listContainer.innerHTML = `
                        <div class="py-8 px-4 text-center text-gray-400">
                            <span class="text-3xl block mb-1">💬</span>
                            <p class="text-xs">Belum ada obrolan pesanan.</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Obrolan dengan pembeli/penjual akan muncul di sini.</p>
                        </div>
                    `;
                    return;
                }

                let html = '';
                data.threads.forEach(thread => {
                    const unreadBadge = thread.unread_count > 0 
                        ? `<span class="bg-red-500 text-white font-bold text-[9px] px-1.5 py-0.2 rounded-full">${thread.unread_count} baru</span>` 
                        : '';
                    const bgClass = thread.unread_count > 0 ? 'bg-blue-50/50 hover:bg-blue-50' : 'hover:bg-gray-50';

                    html += `
                        <a href="${thread.chat_url}" class="block p-3 ${bgClass} transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-gray-900 truncate">${escapeHtml(thread.other_party_name)}</span>
                                        ${unreadBadge}
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-mono block truncate">${thread.order_number} • ${escapeHtml(thread.product_name)}</span>
                                </div>
                                <span class="text-[10px] text-gray-400 shrink-0">${thread.created_time}</span>
                            </div>
                            <p class="text-xs text-gray-600 truncate mt-1 ${thread.unread_count > 0 ? 'font-semibold text-gray-900' : ''}">
                                ${thread.is_me ? '<span class="text-gray-400">Anda: </span>' : ''}${escapeHtml(thread.message)}
                            </p>
                        </a>
                    `;
                });
                listContainer.innerHTML = html;
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const container = document.getElementById('header-messages-container');
            const dropdown = document.getElementById('header-chat-dropdown');
            if (container && dropdown && !container.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Initialize polling
        fetchChatNotifications();
        setInterval(fetchChatNotifications, 5000);
    })();
    </script>

    <!-- ===== THEME TOGGLE SCRIPT ===== -->
    <script>
    (function () {
        function getTheme() {
            try { return localStorage.getItem('gg-theme') || 'light'; } catch(e) { return 'light'; }
        }
        function applyTheme(t) {
            try { localStorage.setItem('gg-theme', t); } catch(e) {}
            if (t === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.removeAttribute('data-theme');
                document.documentElement.classList.remove('dark');
            }
        }

        window.toggleAppTheme = function (e) {
            if (e && e.preventDefault) e.preventDefault();
            var isDark = document.documentElement.getAttribute('data-theme') === 'dark' || document.documentElement.classList.contains('dark');
            var next = isDark ? 'light' : 'dark';
            applyTheme(next);
        };

        var btn = document.getElementById('theme-toggle');
        if (btn) {
            btn.addEventListener('click', window.toggleAppTheme);
        }

        // Apply theme immediately
        applyTheme(getTheme());
    })();
    </script>

    <!-- ===== GLOBAL GLASS TOAST NOTIFICATION CONTAINER & SCRIPTS ===== -->
    <div id="glass-toast-container" class="fixed top-5 right-5 z-[99999] flex flex-col gap-2.5 pointer-events-none max-w-sm w-full px-4 sm:px-0"></div>

    <script>
    (function () {
        const ICONS = {
            success: '✅',
            error:   '❌',
            warning: '⚡',
            info:    '💡'
        };

        const THEMES = {
            success: 'bg-white/95 dark:bg-[#0f172a]/95 text-slate-800 dark:text-slate-100 border-emerald-500/30',
            error:   'bg-white/95 dark:bg-[#0f172a]/95 text-slate-800 dark:text-slate-100 border-rose-500/30',
            warning: 'bg-white/95 dark:bg-[#0f172a]/95 text-slate-800 dark:text-slate-100 border-amber-500/30',
            info:    'bg-white/95 dark:bg-[#0f172a]/95 text-slate-800 dark:text-slate-100 border-blue-500/30',
        };

        const BAR_COLORS = {
            success: 'bg-emerald-500',
            error:   'bg-rose-500',
            warning: 'bg-amber-500',
            info:    'bg-blue-500',
        };

        window.showToast = function (message, type, duration) {
            type = type || 'success';
            duration = duration || 3500;
            const container = document.getElementById('glass-toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            const icon = ICONS[type] || '🔔';
            const themeClass = THEMES[type] || THEMES.info;
            const barColor = BAR_COLORS[type] || BAR_COLORS.info;

            toast.className = `glass-toast glass-toast-enter pointer-events-auto rounded-2xl border p-3.5 shadow-2xl relative overflow-hidden flex items-start gap-3 text-xs font-semibold ${themeClass}`;
            toast.innerHTML = `
                <span class="text-base shrink-0 mt-0.5">${icon}</span>
                <div class="flex-1 min-w-0 pr-4 leading-snug">
                    ${message}
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors font-bold text-xs p-0.5 ml-auto cursor-pointer">✕</button>
                <div class="absolute bottom-0 left-0 h-1 ${barColor}" style="animation: toastProgress ${duration}ms linear forwards;"></div>
            `;

            const closeBtn = toast.querySelector('button');
            const dismiss = () => {
                toast.classList.remove('glass-toast-show');
                toast.classList.add('glass-toast-leave');
                setTimeout(() => toast.remove(), 350);
            };

            closeBtn.addEventListener('click', dismiss);

            container.appendChild(toast);

            // Animate in
            requestAnimationFrame(() => {
                toast.classList.remove('glass-toast-enter');
                toast.classList.add('glass-toast-show');
            });

            // Auto dismiss
            setTimeout(dismiss, duration);
        };

        // Clipboard Copy Helper with Toast
        window.copyToClipboard = function (text, label) {
            label = label || 'Teks';
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    window.showToast(`${label} berhasil disalin ke clipboard! 📋`, 'success');
                }).catch(() => {
                    fallbackCopy(text, label);
                });
            } else {
                fallbackCopy(text, label);
            }
        };

        function fallbackCopy(text, label) {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            window.showToast(`${label} berhasil disalin! 📋`, 'success');
        }

        // Auto flash messages from session
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                window.showToast("{{ session('success') }}", 'success', 4000);
            @endif
            @if(session('error'))
                window.showToast("{{ session('error') }}", 'error', 4500);
            @endif
            @if(session('info'))
                window.showToast("{{ session('info') }}", 'info', 3500);
            @endif
        });
    })();
    </script>
</body>
</html>
