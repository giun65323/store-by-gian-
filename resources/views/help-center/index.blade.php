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
    <title>Pusat Bantuan — gian games</title>
    <meta name="description" content="Pusat Bantuan gian games. Temukan solusi kendala transaksi, panduan pembelian, dan hubungi customer service kami.">
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
                            500: '#0050ae',
                            600: '#003d87',
                            orange: '#ff5e00',
                            yellow: '#ffb800',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .topic-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .topic-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 80, 174, 0.12);
            border-color: #0050ae;
        }
        .tab-btn.active {
            background-color: #0050ae;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 80, 174, 0.25);
        }
        .tab-btn {
            background-color: #ffffff;
            color: #4b5563;
            font-weight: 600;
        }
        .dark .tab-btn:not(.active) {
            background-color: #101422;
            color: #94a3b8;
        }
    </style>
</head>
<body class="bg-[#f8fafc] dark:bg-[#080a10] text-gray-900 dark:text-gray-100 min-h-screen flex flex-col antialiased selection:bg-blue-200 transition-colors duration-200">

    <!-- ===== TOP HEADER BAR ===== -->
    <header class="bg-white dark:bg-[#101422] border-b border-gray-200/80 dark:border-gray-800 sticky top-0 z-40 shadow-xs transition-colors">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4">
            <!-- Brand Logo & Pusat Bantuan Title -->
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-[#0050ae] to-blue-500 flex items-center justify-center text-white font-black text-sm shadow-sm">
                        GG
                    </div>
                    <div class="flex flex-col">
                        <span class="font-brand font-extrabold text-base text-[#0050ae] dark:text-blue-400 leading-tight">gian games</span>
                        <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">Pusat Bantuan</span>
                    </div>
                </a>
            </div>

            <!-- Top Right Links -->
            <div class="flex items-center gap-3 sm:gap-5 text-xs">
                <div class="hidden sm:flex items-center gap-1.5 text-gray-500 dark:text-gray-400 font-medium">
                    <span class="inline-block w-4 h-2.5 bg-red-600 border border-gray-300 dark:border-gray-600 rounded-xs relative overflow-hidden">
                        <span class="absolute inset-x-0 bottom-0 h-1.25 bg-white"></span>
                    </span>
                    <span>ID - IDR | ID</span>
                </div>

                <!-- Dark Mode Toggle Button -->
                <button type="button"
                        onclick="toggleHelpTheme()"
                        class="p-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:text-[#0050ae] dark:hover:text-blue-400 transition-all cursor-pointer"
                        title="Ganti Mode Terang / Gelap"
                        aria-label="Toggle dark/light mode">
                    <!-- Sun (shown in dark mode) -->
                    <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                    </svg>
                    <!-- Moon (shown in light mode) -->
                    <svg class="w-4 h-4 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                </button>

                <a href="{{ route('orders.index') }}" class="hidden sm:flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-[#0050ae] font-semibold transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>Daftar Komplain</span>
                </a>

                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/60 text-[#0050ae] dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60 px-3.5 py-1.5 rounded-full font-bold transition-all hover:scale-102">
                    <span>Kembali ke gian games</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="flex-1">
        
        <!-- ===== 1. HERO SECTION WITH SQUIRREL MASCOT ===== -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-10">
            <div class="bg-gradient-to-r from-blue-50/80 via-indigo-50/50 to-amber-50/40 border border-blue-100/80 rounded-3xl p-6 sm:p-10 shadow-xs flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
                
                <!-- Left Hero Text -->
                <div class="space-y-4 max-w-xl text-center md:text-left z-10">
                    <div class="inline-flex items-center gap-2 bg-white/90 border border-blue-200/80 text-[#0050ae] text-xs font-bold px-3.5 py-1.5 rounded-full shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Customer Service Online 24/7
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Halo Kak, Ada yang Bisa Dibantu?
                    </h1>
                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                        Gian Care siap bantu jika ada kendala transaksi, pembayaran, atau seputar toko di gian games.
                    </p>
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center md:justify-start gap-3">
                        <button type="button"
                                onclick="openCsChatModal()"
                                class="w-full sm:w-auto bg-[#0050ae] hover:bg-[#003d87] active:scale-98 text-white font-bold text-sm px-6 py-3.5 rounded-2xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group cursor-pointer">
                            <span class="text-amber-300 text-base group-hover:scale-125 transition-transform">✦</span>
                            <span>Mulai Chat</span>
                        </button>
                        <a href="{{ route('orders.index') }}" class="w-full sm:w-auto bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-bold text-sm px-5 py-3.5 rounded-2xl shadow-xs transition-all flex items-center justify-center gap-2">
                            <span>🔍 Lacak Pesanan</span>
                        </a>
                    </div>
                </div>

                <!-- Right Hero: Mascot Image (Squirrel with Headset & Laptop) -->
                <div class="shrink-0 relative flex items-center justify-center z-10">
                    <!-- Soft Background Glow Circle -->
                    <div class="w-56 h-56 sm:w-72 sm:h-72 rounded-full bg-gradient-to-tr from-blue-300/30 via-amber-200/30 to-rose-200/30 absolute blur-2xl -z-10"></div>
                    
                    <!-- Squirrel Mascot Image Container -->
                    <div class="w-52 h-52 sm:w-64 sm:h-64 rounded-3xl overflow-hidden shadow-xl border-4 border-white bg-white flex items-center justify-center group hover:scale-105 transition-transform duration-300">
                        <img src="{{ asset('images/mascot-squirrel.jpg') }}"
                             alt="Gian Care Mascot Squirrel"
                             class="w-full h-full object-cover object-center">
                    </div>

                    <!-- Floating Badge -->
                    <div class="absolute -bottom-3 -left-3 bg-white/95 backdrop-blur-xs border border-gray-200 rounded-2xl px-3.5 py-2 shadow-lg flex items-center gap-2.5">
                        <span class="text-xl">🐿️</span>
                        <div>
                            <p class="text-xs font-bold text-gray-900 leading-tight">Gian Care</p>
                            <p class="text-[10px] text-emerald-600 font-semibold">Siap Melayani Anda</p>
                        </div>
                    </div>
                </div>

            </div>
        </section>


        <!-- ===== 2. CARI BERDASARKAN TOPIK SECTION ===== -->
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            
            <div class="text-center mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Cari Berdasarkan Topik</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Pilih kategori kendala sesuai kebutuhan Anda</p>
            </div>

            <!-- Tab Switcher: Sebagai Pembeli / Sebagai Penjual -->
            <div class="flex items-center justify-center mb-6">
                <div class="bg-gray-200/70 p-1 rounded-2xl flex items-center gap-1 shadow-inner border border-gray-200">
                    <button type="button"
                            onclick="switchTopicTab('buyer')"
                            id="tab-btn-buyer"
                            class="tab-btn active px-6 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer">
                        Sebagai Pembeli
                    </button>
                    <button type="button"
                            onclick="switchTopicTab('seller')"
                            id="tab-btn-seller"
                            class="tab-btn px-6 py-2.5 rounded-xl text-xs sm:text-sm transition-all cursor-pointer">
                        Sebagai Penjual
                    </button>
                </div>
            </div>

            <!-- Search Keyword Input Box -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="relative flex items-center bg-white rounded-2xl border border-gray-300 shadow-xs focus-within:ring-2 focus-within:ring-[#0050ae] focus-within:border-transparent transition-all">
                    <input type="text"
                           id="help-search-input"
                           oninput="filterHelpTopics()"
                           placeholder="Ketik kata kunci (contoh: cara top up, refund, saldo, garansi)..."
                           class="w-full bg-transparent text-gray-800 placeholder-gray-400 text-xs sm:text-sm px-4 py-3.5 focus:outline-none">
                    <div class="pr-4 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- TOPIC CARDS: SEBAGAI PEMBELI -->
            <div id="topics-buyer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                
                <!-- Card 1: Cara Membeli -->
                <div onclick="selectTopicFaq('Cara Membeli')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#0050ae] flex items-center justify-center text-xl shrink-0">
                            🛒
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Cara Membeli</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Panduan memilih produk & checkout aman</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pembayaran -->
                <div onclick="selectTopicFaq('Pembayaran')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                            💳
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Metode Pembayaran</h3>
                            <p class="text-xs text-gray-500 mt-0.5">DANA, GoPay, QRIS, & Transfer</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Pengiriman Instan -->
                <div onclick="selectTopicFaq('Pengiriman')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                            ⚡
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Pengiriman Instan</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Waktu penerimaan item & voucher</p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Garansi 100% & Refund -->
                <div onclick="selectTopicFaq('Garansi')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                            🛡️
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Garansi 100% & Refund</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Jaminan uang kembali jika bermasalah</p>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Akun & Keamanan -->
                <div onclick="selectTopicFaq('Akun')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                            👤
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Akun Pembeli</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Verifikasi OTP & ganti password</p>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Komplain Pesanan -->
                <div onclick="selectTopicFaq('Komplain')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shrink-0">
                            ⚠️
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Komplain Transaksi</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Solusi kendala pesanan tidak terkirim</p>
                        </div>
                    </div>
                </div>

            </div>


            <!-- TOPIC CARDS: SEBAGAI PENJUAL (SELLER) -->
            <div id="topics-seller" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 hidden">
                
                <!-- Card 1: Fitur Tokoku -->
                <div onclick="selectTopicFaq('Fitur Tokoku')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#0050ae] flex items-center justify-center text-xl shrink-0">
                            ⚙️
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Fitur Tokoku</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Kelola produk, stok, & update harga</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Cara Berjualan -->
                <div onclick="selectTopicFaq('Cara Berjualan')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                            💼
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Cara Berjualan</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Panduan buka toko & upload dagangan</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Akun Tokoku -->
                <div onclick="selectTopicFaq('Akun Tokoku')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shrink-0">
                            🏪
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Akun Tokoku</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Profil toko & reputasi penjual terpercaya</p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Ketentuan Penjualan -->
                <div onclick="selectTopicFaq('Ketentuan Penjualan')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                            📜
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Ketentuan Penjualan</h3>
                            <p class="text-xs text-gray-500 mt-0.5">SLA pengiriman & kebijakan larangan</p>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Penjualan & Saldo -->
                <div onclick="selectTopicFaq('Penjualan')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                            💰
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Penjualan & Saldo</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Penghasilan toko & riwayat penjualan</p>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Chat dengan Pembeli -->
                <div onclick="selectTopicFaq('Chat Pembeli')" class="topic-card bg-white border border-gray-200/90 rounded-2xl p-5 cursor-pointer shadow-xs">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                            💬
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-gray-900">Chat dengan Pembeli</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Cara membalas pesan & konfirmasi data</p>
                        </div>
                    </div>
                </div>

            </div>

        </section>


        <!-- ===== 3. ARTIKEL YANG SERING DICARI (FAQ ACCORDION) ===== -->
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            
            <div class="text-center mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Artikel yang Sering Dicari</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Jawaban cepat untuk pertanyaan yang paling sering diajukan</p>
            </div>

            <div class="space-y-3" id="faq-list">
                
                <!-- FAQ 1 -->
                <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                    <button type="button"
                            onclick="toggleFaq(this)"
                            class="w-full p-4 sm:p-5 text-left font-bold text-sm sm:text-base text-gray-900 flex items-center justify-between gap-4 hover:bg-gray-50/80 transition-colors">
                        <span class="flex items-center gap-2.5">
                            <span class="text-[#0050ae]">Q:</span>
                            <span>Apa itu Pelanggaran Level 1, Level 2, dan Level 3?</span>
                        </span>
                        <svg class="faq-chevron w-5 h-5 text-gray-400 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100 bg-gray-50/50">
                        <p class="mb-2">Sistem Level Pelanggaran di gian games diterapkan untuk menjaga ekosistem transaksi yang aman:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Level 1 (Peringatan):</strong> Keterlambatan pengiriman pesanan instan pertama kali atau pembatalan sepihak. Toko diberikan peringatan sistem.</li>
                            <li><strong>Level 2 (Pembatasan Sementara):</strong> Pengulangan keterlambatan atau komplain pembeli yang tidak diselesaikan dalam 1x24 jam. Fitur tambah produk dibatasi sementara selama 3 hari.</li>
                            <li><strong>Level 3 (Penonaktifan Akun):</strong> Terbukti mengirimkan data voucher palsu, penipuan, atau transaksi di luar platform. Toko dan akun dinonaktifkan secara permanen.</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                    <button type="button"
                            onclick="toggleFaq(this)"
                            class="w-full p-4 sm:p-5 text-left font-bold text-sm sm:text-base text-gray-900 flex items-center justify-between gap-4 hover:bg-gray-50/80 transition-colors">
                        <span class="flex items-center gap-2.5">
                            <span class="text-[#0050ae]">Q:</span>
                            <span>Bagaimana cara mengajukan pengembalian dana (refund)?</span>
                        </span>
                        <svg class="faq-chevron w-5 h-5 text-gray-400 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100 bg-gray-50/50">
                        <p>Jika pesanan Anda tidak dikirimkan oleh penjual atau kode yang diterima bermasalah, Anda dilindungi oleh <strong>Garansi 100% gian games</strong>:</p>
                        <ol class="list-decimal pl-5 mt-2 space-y-1">
                            <li>Buka menu <strong>Riwayat Transaksi</strong> (<a href="{{ route('orders.index') }}" class="text-[#0050ae] underline">orders</a>).</li>
                            <li>Pilih pesanan yang bermasalah, lalu klik <strong>Lihat Rincian</strong>.</li>
                            <li>Klik tombol <strong>Contact Seller</strong> untuk konfirmasi dengan penjual terlebih dahulu.</li>
                            <li>Jika penjual tidak merespon dalam waktu garansi, klik <strong>Ajukan Komplain</strong> atau hubungi Customer Service kami melalui tombol <strong>Mulai Chat</strong> untuk pencairan refund 100%.</li>
                        </ol>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                    <button type="button"
                            onclick="toggleFaq(this)"
                            class="w-full p-4 sm:p-5 text-left font-bold text-sm sm:text-base text-gray-900 flex items-center justify-between gap-4 hover:bg-gray-50/80 transition-colors">
                        <span class="flex items-center gap-2.5">
                            <span class="text-[#0050ae]">Q:</span>
                            <span>Berapa lama waktu proses pengiriman pesanan instan?</span>
                        </span>
                        <svg class="faq-chevron w-5 h-5 text-gray-400 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100 bg-gray-50/50">
                        <p>Untuk produk bertanda <strong>Pengiriman Instan</strong>, sistem akan secara otomatis menerbitkan kode voucher atau detail item di halaman rincian pesanan segera setelah pembayaran terkonfirmasi (rata-rata di bawah <strong>1 menit</strong>).</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                    <button type="button"
                            onclick="toggleFaq(this)"
                            class="w-full p-4 sm:p-5 text-left font-bold text-sm sm:text-base text-gray-900 flex items-center justify-between gap-4 hover:bg-gray-50/80 transition-colors">
                        <span class="flex items-center gap-2.5">
                            <span class="text-[#0050ae]">Q:</span>
                            <span>Bagaimana cara penjual membalas chat dari pembeli?</span>
                        </span>
                        <svg class="faq-chevron w-5 h-5 text-gray-400 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100 bg-gray-50/50">
                        <p>Penjual dapat membalas pesan pembeli secara mudah:</p>
                        <ul class="list-disc pl-5 mt-2 space-y-1">
                            <li>Buka icon pesan 💬 di navbar atas untuk melihat daftar pesan masuk yang belum dibaca.</li>
                            <li>Atau buka tab <strong>Pesanan Masuk (Penjualan)</strong> di halaman Transaksi, lalu klik tombol <strong>💬 Balas Chat</strong>.</li>
                            <li>Gunakan template pesan cepat (Quick Reply) untuk membalas konfirmasi pesanan dalam satu klik.</li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="faq-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-xs">
                    <button type="button"
                            onclick="toggleFaq(this)"
                            class="w-full p-4 sm:p-5 text-left font-bold text-sm sm:text-base text-gray-900 flex items-center justify-between gap-4 hover:bg-gray-50/80 transition-colors">
                        <span class="flex items-center gap-2.5">
                            <span class="text-[#0050ae]">Q:</span>
                            <span>Bagaimana jika saya lupa password akun gian games saya?</span>
                        </span>
                        <svg class="faq-chevron w-5 h-5 text-gray-400 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer hidden px-5 pb-5 pt-1 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-100 bg-gray-50/50">
                        <p>Anda dapat menggunakan fitur <strong>Lupa Password</strong> di halaman Login. Masukkan alamat Gmail Anda, sistem akan mengirimkan <strong>kode OTP 6-digit</strong> ke email untuk verifikasi pembuatan kata sandi baru.</p>
                    </div>
                </div>

            </div>

        </section>

    </main>


    <!-- ===== 4. GIAN CARE LIVE CUSTOMER SERVICE MODAL ===== -->
    <div id="cs-chat-backdrop"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs z-[120] opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center p-3 sm:p-4">
        
        <div id="cs-chat-box"
             class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col h-[560px] max-h-[90vh] transform scale-95 opacity-0 transition-all duration-300 border border-gray-100">
            
            <!-- CS Modal Header -->
            <div class="bg-gradient-to-r from-[#0050ae] to-blue-600 text-white px-5 py-4 flex items-center justify-between shrink-0 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl overflow-hidden border-2 border-white/40 shadow-sm bg-white shrink-0">
                        <img src="{{ asset('images/mascot-squirrel.jpg') }}" alt="Gian Care Mascot" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <h3 class="font-bold text-sm">Gian Care Assistant</h3>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>
                        <p class="text-[11px] text-blue-100">Customer Support & Bantuan Otomatis</p>
                    </div>
                </div>
                <button type="button" onclick="closeCsChatModal()" class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-full transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- CS Messages Area -->
            <div id="cs-chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 bg-[#f8fafc] text-xs">
                <!-- Mascot Greeting Bubble -->
                <div class="flex items-start gap-2.5">
                    <div class="w-7 h-7 rounded-full overflow-hidden border border-gray-200 bg-white shrink-0 mt-0.5">
                        <img src="{{ asset('images/mascot-squirrel.jpg') }}" alt="Squirrel" class="w-full h-full object-cover">
                    </div>
                    <div class="max-w-[82%] bg-white border border-gray-200 rounded-2xl rounded-tl-xs p-3.5 shadow-xs text-gray-800 space-y-2 leading-relaxed">
                        <p>Halo Kak! Saya <strong>Gian Care 🐿️</strong>, asisten bantuan gian games.</p>
                        <p class="text-gray-600">Ada kendala apa yang sedang Kakak hadapi hari ini? Pilih topik cepat di bawah atau ketik pertanyaan langsung ya!</p>
                    </div>
                </div>

                <!-- Suggested Quick Actions Bubble -->
                <div class="pl-9 space-y-1.5">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Topik Populer:</p>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="sendCsOption('Cek Status Pesanan')" class="bg-white hover:bg-blue-50 border border-blue-200 hover:border-blue-400 text-[#0050ae] px-3 py-1.5 rounded-xl font-semibold text-[11px] transition-all shadow-2xs cursor-pointer">
                            📦 Cek Status Pesanan
                        </button>
                        <button type="button" onclick="sendCsOption('Klaim Garansi & Refund')" class="bg-white hover:bg-blue-50 border border-blue-200 hover:border-blue-400 text-[#0050ae] px-3 py-1.5 rounded-xl font-semibold text-[11px] transition-all shadow-2xs cursor-pointer">
                            🛡️ Klaim Garansi & Refund
                        </button>
                        <button type="button" onclick="sendCsOption('Cara Hubungi Penjual')" class="bg-white hover:bg-blue-50 border border-blue-200 hover:border-blue-400 text-[#0050ae] px-3 py-1.5 rounded-xl font-semibold text-[11px] transition-all shadow-2xs cursor-pointer">
                            💬 Cara Hubungi Penjual
                        </button>
                        <button type="button" onclick="sendCsOption('Lupa Password Akun')" class="bg-white hover:bg-blue-50 border border-blue-200 hover:border-blue-400 text-[#0050ae] px-3 py-1.5 rounded-xl font-semibold text-[11px] transition-all shadow-2xs cursor-pointer">
                            🔑 Lupa Password Akun
                        </button>
                    </div>
                </div>
            </div>

            <!-- CS Input Box -->
            <form onsubmit="handleSendCsMessage(event)" class="bg-white border-t border-gray-100 p-3 flex items-center gap-2 shrink-0">
                <input type="text"
                       id="cs-user-input"
                       placeholder="Ketik pertanyaan Kakak di sini..."
                       class="flex-1 bg-gray-100 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0050ae] focus:bg-white transition-all">
                <button type="submit" class="bg-[#0050ae] hover:bg-[#003d87] text-white p-2.5 rounded-xl font-bold transition-all shadow-sm shrink-0 cursor-pointer">
                    <svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>


    <!-- ===== FOOTER ===== -->
    <footer class="bg-white border-t border-gray-200 py-8 text-center text-xs text-gray-400">
        <p>&copy; {{ date('Y') }} gian games Pusat Bantuan. Seluruh hak cipta dilindungi.</p>
    </footer>


    <!-- ===== JAVASCRIPT LOGIC ===== -->
    <script>
    function switchTopicTab(tab) {
        const btnBuyer = document.getElementById('tab-btn-buyer');
        const btnSeller = document.getElementById('tab-btn-seller');
        const topicsBuyer = document.getElementById('topics-buyer');
        const topicsSeller = document.getElementById('topics-seller');

        if (tab === 'buyer') {
            btnBuyer.classList.add('active');
            btnSeller.classList.remove('active');
            topicsBuyer.classList.remove('hidden');
            topicsSeller.classList.add('hidden');
        } else {
            btnSeller.classList.add('active');
            btnBuyer.classList.remove('active');
            topicsSeller.classList.remove('hidden');
            topicsBuyer.classList.add('hidden');
        }
    }

    function toggleFaq(btn) {
        const answer = btn.nextElementSibling;
        const chevron = btn.querySelector('.faq-chevron');
        if (!answer) return;

        const isHidden = answer.classList.contains('hidden');
        if (isHidden) {
            answer.classList.remove('hidden');
            if (chevron) chevron.classList.add('rotate-180');
        } else {
            answer.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    }

    function filterHelpTopics() {
        const query = document.getElementById('help-search-input').value.toLowerCase().trim();
        const activeTabBuyer = document.getElementById('tab-btn-buyer').classList.contains('active');
        const cards = activeTabBuyer 
            ? document.querySelectorAll('#topics-buyer .topic-card')
            : document.querySelectorAll('#topics-seller .topic-card');

        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            if (text.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        // Filter FAQs too
        const faqs = document.querySelectorAll('.faq-item');
        faqs.forEach(faq => {
            const text = faq.textContent.toLowerCase();
            if (text.includes(query)) {
                faq.style.display = 'block';
            } else {
                faq.style.display = query === '' ? 'block' : 'none';
            }
        });
    }

    function selectTopicFaq(topicName) {
        document.getElementById('help-search-input').value = topicName;
        filterHelpTopics();
        const faqSection = document.getElementById('faq-list');
        if (faqSection) {
            faqSection.scrollIntoView({ behavior: 'smooth' });
        }
    }

    /* ── Live CS Chat & AI Logic ── */
    let chatHistory = [];
    let isAiGenerating = false;

    function openCsChatModal() {
        const backdrop = document.getElementById('cs-chat-backdrop');
        const box = document.getElementById('cs-chat-box');
        if (!backdrop || !box) return;

        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100');
        box.classList.remove('scale-95', 'opacity-0');
        box.classList.add('scale-100', 'opacity-100');

        setTimeout(() => {
            document.getElementById('cs-user-input')?.focus();
        }, 300);
    }

    function closeCsChatModal() {
        const backdrop = document.getElementById('cs-chat-backdrop');
        const box = document.getElementById('cs-chat-box');
        if (!backdrop || !box) return;

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        box.classList.remove('scale-100', 'opacity-100');
        box.classList.add('scale-95', 'opacity-0');
    }

    function sendCsOption(text) {
        if (isAiGenerating) return;
        appendUserMessage(text);
        fetchAiResponse(text);
    }

    function handleSendCsMessage(e) {
        e.preventDefault();
        if (isAiGenerating) return;

        const input = document.getElementById('cs-user-input');
        if (!input || !input.value.trim()) return;
        const userText = input.value.trim();
        input.value = '';

        appendUserMessage(userText);
        fetchAiResponse(userText);
    }

    function appendUserMessage(text) {
        const container = document.getElementById('cs-chat-messages');
        if (!container) return;

        const userHtml = `
            <div class="flex flex-col items-end animate-fadeIn">
                <div class="max-w-[78%] bg-[#0050ae] text-white px-3.5 py-2 rounded-2xl rounded-tr-xs shadow-xs leading-relaxed">
                    ${escapeHtml(text)}
                </div>
                <span class="text-[10px] text-gray-400 mt-0.5 mr-1">Baru saja</span>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', userHtml);
        container.scrollTop = container.scrollHeight;
    }

    function showTypingIndicator() {
        const container = document.getElementById('cs-chat-messages');
        if (!container) return;

        removeTypingIndicator();

        const typingHtml = `
            <div id="ai-typing-indicator" class="flex items-start gap-2.5">
                <div class="w-7 h-7 rounded-full overflow-hidden border border-gray-200 bg-white shrink-0 mt-0.5">
                    <img src="{{ asset('images/mascot-squirrel.jpg') }}" alt="Squirrel" class="w-full h-full object-cover">
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-xs px-3.5 py-2.5 shadow-xs flex items-center gap-2 text-xs text-gray-500">
                    <span class="font-bold text-[#0050ae]">Gian Care sedang mengetik</span>
                    <span class="inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-[#0050ae] rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-[#0050ae] rounded-full animate-bounce [animation-delay:0.15s]"></span>
                        <span class="w-1.5 h-1.5 bg-[#0050ae] rounded-full animate-bounce [animation-delay:0.3s]"></span>
                    </span>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', typingHtml);
        container.scrollTop = container.scrollHeight;
    }

    function removeTypingIndicator() {
        const el = document.getElementById('ai-typing-indicator');
        if (el) el.remove();
    }

    function appendBotMessage(htmlContent) {
        removeTypingIndicator();
        const container = document.getElementById('cs-chat-messages');
        if (!container) return;

        const botHtml = `
            <div class="flex items-start gap-2.5 animate-fadeIn">
                <div class="w-7 h-7 rounded-full overflow-hidden border border-gray-200 bg-white shrink-0 mt-0.5">
                    <img src="{{ asset('images/mascot-squirrel.jpg') }}" alt="Squirrel" class="w-full h-full object-cover">
                </div>
                <div class="max-w-[82%] bg-white border border-gray-200 rounded-2xl rounded-tl-xs p-3.5 shadow-xs text-gray-800 space-y-2 leading-relaxed">
                    ${htmlContent}
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', botHtml);
        container.scrollTop = container.scrollHeight;
    }

    function fetchAiResponse(userText) {
        isAiGenerating = true;
        showTypingIndicator();

        const input = document.getElementById('cs-user-input');
        if (input) input.disabled = true;

        fetch('{{ route("help.ai.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: userText,
                history: chatHistory
            })
        })
        .then(res => res.json())
        .then(data => {
            isAiGenerating = false;
            if (input) {
                input.disabled = false;
                input.focus();
            }

            if (data.status === 'success' && data.reply) {
                appendBotMessage(data.reply);
                chatHistory.push({ sender: 'user', text: userText });
                chatHistory.push({ sender: 'assistant', text: data.reply });
                if (chatHistory.length > 20) chatHistory.splice(0, 2);
            } else {
                appendBotMessage('Maaf Kak, terjadi sedikit kendala jaringan. Silakan coba tanyakan kembali ya! 🐿️');
            }
        })
        .catch(err => {
            isAiGenerating = false;
            if (input) {
                input.disabled = false;
                input.focus();
            }
            console.error('AI chat error:', err);
            appendBotMessage('Mohon maaf Kak, terjadi kendala saat memproses jawaban. Silakan coba sesaat lagi ya 🙏');
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function toggleHelpTheme() {
        var current = localStorage.getItem('gg-theme') || 'light';
        var next = (current === 'dark' || document.documentElement.classList.contains('dark')) ? 'light' : 'dark';
        try { localStorage.setItem('gg-theme', next); } catch(e) {}
        if (next === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
            document.documentElement.classList.remove('dark');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var savedTheme = localStorage.getItem('gg-theme') || 'light';
        if (savedTheme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            document.documentElement.classList.add('dark');
        }

        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab === 'seller') {
            switchTopicTab('seller');
        } else if (tab === 'buyer') {
            switchTopicTab('buyer');
        }

        if (urlParams.get('chat') === '1' || urlParams.get('open_chat') === '1') {
            setTimeout(openCsChatModal, 300);
        }
    });
    </script>
</body>
</html>
