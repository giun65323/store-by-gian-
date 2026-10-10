@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', $product->name . ' - gian games')

@section('content')
@php
    $store = $product->store ?? $product->user?->store;
    $storeUrl = $store ? route('stores.show', $store->slug ?? $store->id) : '#';
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

    <!-- ===== BREADCRUMB ===== -->
    <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 flex-wrap">
        <a href="{{ route('dashboard') }}" class="hover:text-[#7c3aed] dark:hover:text-cyan-300 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#7c3aed] dark:hover:text-cyan-300 transition-colors">Katalog Produk</a>
        <span>/</span>
        <span class="text-gray-900 dark:text-white font-bold truncate max-w-xs sm:max-w-md">{{ $product->name }}</span>
    </nav>

    <!-- ===== FLASH MESSAGES ===== -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-900 cursor-pointer">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 dark:text-rose-400 hover:text-rose-900 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- ===== MAIN CONTENT GRID ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

        <!-- ================================================================= -->
        <!-- KOLOM KIRI (LEBAR: 8/12): FOTO, TOKO, DESKRIPSI, ULASAN & BINTANG  -->
        <!-- ================================================================= -->
        <div class="lg:col-span-8 space-y-6">

            <!-- 1. GAMBAR & INFO UTAMA PRODUK -->
            <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xs space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    <!-- Thumbnail / Banner -->
                    <div class="md:col-span-5 relative aspect-square rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200/80 dark:border-gray-700/80 shadow-inner group">
                        @php
                            $colors = ['from-blue-600 to-indigo-700','from-indigo-600 to-purple-700','from-emerald-600 to-teal-700','from-amber-600 to-red-700','from-purple-600 to-pink-700'];
                            $colorIdx = crc32($product->name) % count($colors);
                        @endphp
                        @if ($product->image && Storage::disk('public')->exists($product->image))
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br {{ $colors[$colorIdx] }} flex items-center justify-center text-white font-black text-6xl group-hover:scale-105 transition-transform duration-300">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                        @endif

                        <!-- Badge Pengiriman di Gambar -->
                        @if($product->delivery_type === 'regular')
                            <span class="absolute top-3 left-3 bg-blue-600 text-white text-[10px] font-black px-2.5 py-1 rounded-lg shadow-md flex items-center gap-1">
                                <span>📦</span>
                                <span>Pengiriman Biasa</span>
                            </span>
                        @else
                            <span class="absolute top-3 left-3 bg-gradient-to-r from-amber-400 to-amber-500 text-gray-950 text-[10px] font-black px-2.5 py-1 rounded-lg shadow-md flex items-center gap-1">
                                <span>⚡</span>
                                <span>Instan Kilat</span>
                            </span>
                        @endif

                        <!-- Rating Badge di Gambar -->
                        <div class="absolute bottom-3 left-3 bg-black/75 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1.5 border border-white/20 backdrop-blur-xs shadow-md">
                            <span class="text-amber-400">★</span>
                            <span>{{ number_format($avgRating, 1) }}</span>
                            <span class="text-white/70 text-[10px]">({{ $totalReviews }})</span>
                        </div>
                    </div>

                    <!-- Ringkasan Produk & Seller -->
                    <div class="md:col-span-7 space-y-3.5">
                        <div class="space-y-1.5">
                            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white font-brand tracking-tight leading-snug">
                                {{ $product->name }}
                            </h1>
                            <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 flex-wrap">
                                <div class="flex items-center gap-1 text-amber-500 font-bold">
                                    <span>★</span>
                                    <span>{{ number_format($avgRating, 1) }}</span>
                                    <span class="text-gray-400 font-normal">({{ $totalReviews }} ulasan pembeli)</span>
                                </div>
                                <span>·</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-bold">
                                    Stok: {{ $product->stock }} unit
                                </span>
                                <span>·</span>
                                <span class="text-slate-500">Terdaftar {{ $product->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>

                        <!-- Harga Banner Mobile / Mini -->
                        <div class="p-3.5 rounded-2xl bg-orange-50/70 dark:bg-orange-950/30 border border-orange-200/80 dark:border-orange-800/40 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium block">Harga Satuan</span>
                                <div class="text-2xl font-black text-[#ff5e00] font-brand tracking-tight">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <span class="text-[11px] font-extrabold px-2.5 py-1 rounded-lg {{ $product->stock > 0 ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-600 border border-rose-500/20' }}">
                                {{ $product->stock > 0 ? 'Tersedia Siap Kirim' : 'Stok Habis' }}
                            </span>
                        </div>

                        <!-- Kartu Penjual / Toko (Bisa Diklik untuk Melihat Profil & Seluruh Dagangan Toko) -->
                        <div class="p-3.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200/80 dark:border-gray-700/60 flex items-center justify-between gap-3 group/store hover:border-purple-300 dark:hover:border-purple-800/80 transition-colors">
                            <a href="{{ $storeUrl }}" class="flex items-center gap-3 min-w-0 flex-1 hover:opacity-95 transition-opacity focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#7c3aed] rounded-xl p-1 -m-1" title="Lihat Profil Toko & Seluruh Produk">
                                @if($store && $store->logo && Storage::disk('public')->exists($store->logo))
                                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-300 dark:border-gray-700">
                                @elseif($product->user?->avatar_url)
                                    <img src="{{ $product->user->avatar_url }}" alt="{{ $product->user->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-300 dark:border-gray-700">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-[#7c3aed] text-white flex items-center justify-center font-black text-sm shrink-0">
                                        {{ strtoupper(substr($store ? $store->name : ($product->user?->name ?? 'T'), 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-bold text-xs text-gray-900 dark:text-white group-hover/store:text-[#7c3aed] dark:group-hover/store:text-purple-300 transition-colors truncate">
                                            {{ $store ? $store->name : ($product->user?->name ?? 'Toko Resmi gian games') }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-[9px] font-black bg-blue-500/15 text-[#7c3aed] dark:text-cyan-300 border border-blue-500/30 px-1.5 py-0.2 rounded-md">
                                            ✓ Penjual Resmi
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Aktif & Cepat Tanggap</span>
                                    </p>
                                </div>
                            </a>

                            <div class="flex items-center gap-2 shrink-0">
                                @if($store)
                                <a href="{{ $storeUrl }}" class="text-xs font-bold text-[#7c3aed] dark:text-purple-300 hover:text-white hover:bg-[#7c3aed] dark:hover:bg-[#7c3aed] px-2.5 py-1.5 rounded-lg bg-purple-50 dark:bg-purple-950/60 border border-purple-200/80 dark:border-purple-800/60 transition-all focus-visible:ring-2 focus-visible:ring-[#7c3aed] focus-visible:outline-none" title="Lihat semua produk dari toko ini">
                                    Lihat Toko
                                </a>
                                @endif
                                @if(auth()->check() && (auth()->id() === $product->user_id || auth()->user()->isAdmin()))
                                    <a href="{{ route('products.edit', $product) }}" class="text-xs font-bold text-gray-700 dark:text-gray-300 hover:text-gray-950 dark:hover:text-white px-2.5 py-1.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 transition-all focus-visible:ring-2 focus-visible:ring-[#7c3aed] focus-visible:outline-none" title="Edit Produk">
                                        ✏️ Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. DESKRIPSI PRODUK -->
            <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl">📝</span>
                        <h2 class="text-base sm:text-lg font-black text-gray-900 dark:text-white font-brand">
                            Deskripsi & Rincian Produk
                        </h2>
                    </div>
                    <span class="text-xs text-gray-400">Informasi Lengkap Dagangan</span>
                </div>

                <div class="prose dark:prose-invert max-w-none text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    @if(!empty(trim($product->description)))
                        <div class="whitespace-pre-line bg-gray-50/70 dark:bg-gray-800/30 p-4 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 font-sans">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    @else
                        <div class="p-6 rounded-2xl bg-gray-50 dark:bg-gray-800/40 text-center space-y-2 border border-gray-200/60 dark:border-gray-800">
                            <span class="text-2xl">📋</span>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                Penjual belum menyertakan deskripsi teks untuk produk ini.
                            </p>
                            @if(auth()->check() && (auth()->id() === $product->user_id || auth()->user()->isAdmin()))
                                <a href="{{ route('products.edit', $product) }}" class="inline-block text-xs font-bold text-[#7c3aed] dark:text-cyan-400 hover:underline">
                                    + Tambahkan Deskripsi Sekarang
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Cara Transaksi & Catatan Penting -->
                <div class="p-4 rounded-2xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200/70 dark:border-blue-800/40 space-y-2 text-xs">
                    <div class="flex items-center gap-2 text-[#7c3aed] dark:text-cyan-300 font-extrabold">
                        <span>🛡️</span>
                        <span>Jaminan Transaksi Aman gian games:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-gray-600 dark:text-gray-400 pl-1">
                        <li>Dana transaksi Anda disimpan dengan aman oleh sistem rekber gian games hingga pesanan terkonfirmasi selesai.</li>
                        <li>Jangan pernah bertransaksi atau memberikan data login akun di luar platform resmi gian games.</li>
                        <li>Hubungi Pusat Bantuan jika ada kendala pengiriman atau klaim garansi.</li>
                    </ul>
                </div>
            </div>

            <!-- 3. ULASAN & PENILAIAN BINTANG (REVIEW SYSTEM) -->
            <div id="reviews-section" class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xs space-y-6">

                <!-- Header Section Ulasan -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl">⭐</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-gray-900 dark:text-white font-brand">
                                Ulasan & Penilaian Pembeli
                            </h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Semua ulasan publik transparan dari pengguna terverifikasi</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/20">
                        {{ $totalReviews }} Ulasan
                    </span>
                </div>

                <!-- Score Summary & Star Bars -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200/70 dark:border-gray-700/60 items-center">
                    <!-- Big Rating Number -->
                    <div class="md:col-span-4 text-center space-y-1.5 md:border-r border-gray-200 dark:border-gray-700/80 md:pr-4">
                        <div class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white font-brand tracking-tight">
                            {{ number_format($avgRating, 1) }}
                        </div>
                        <!-- 5 Star Icons -->
                        <div class="flex items-center justify-center gap-1 text-amber-400 text-lg">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Rata-rata dari {{ $totalReviews }} ulasan</p>
                    </div>

                    <!-- Breakdown Bars (5 to 1 Stars) -->
                    <div class="md:col-span-8 space-y-2">
                        @foreach([5, 4, 3, 2, 1] as $star)
                            @php
                                $starData = $ratingBreakdown[$star] ?? ['count' => 0, 'percentage' => 0];
                            @endphp
                            <div class="flex items-center gap-2.5 text-xs text-gray-600 dark:text-gray-400">
                                <span class="w-12 font-bold text-amber-500 flex items-center gap-0.5 shrink-0">
                                    <span>{{ $star }}</span>
                                    <span>★</span>
                                </span>
                                <div class="flex-1 h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full transition-all" style="width: {{ $starData['percentage'] }}%"></div>
                                </div>
                                <span class="w-14 text-right text-[11px] font-mono text-gray-400 shrink-0">
                                    {{ $starData['count'] }} ({{ $starData['percentage'] }}%)
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- FORM TULIS / EDIT ULASAN -->
                <div class="p-5 rounded-2xl bg-blue-50/50 dark:bg-slate-800/60 border border-blue-200/80 dark:border-blue-900/40 space-y-4">
                    @auth
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs sm:text-sm font-extrabold text-gray-900 dark:text-white flex items-center gap-1.5">
                                <span>✍️</span>
                                <span>{{ $userReview ? 'Perbarui Ulasan Anda' : 'Tulis Ulasan & Berikan Bintang' }}</span>
                            </h3>
                            @if($userReview)
                                <span class="text-[10px] bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-bold px-2 py-0.5 rounded-md border border-emerald-500/20">
                                    Ulasan Anda Sudah Terkirim
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('products.reviews.store', $product) }}" method="POST" class="space-y-4">
                            @csrf

                            <!-- Penilaian Bintang dengan Animasi PeekRating (React Bits) -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                        Pilih Penilaian Bintang <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-gray-400">Geser atau klik bintang di bawah</span>
                                </div>

                                <div class="p-4 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200/90 dark:border-gray-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
                                    <!-- PeekRating interactive slot -->
                                    <div id="peek-rating-slot" class="inline-flex"></div>

                                    <div class="flex items-center gap-2 text-xs font-black text-amber-500 bg-amber-500/10 px-3.5 py-2 rounded-xl border border-amber-500/20 w-fit shrink-0">
                                        <span id="peek-rating-indicator-score" class="text-sm font-mono">{{ old('rating', $userReview ? $userReview->rating : 5) }} ★</span>
                                        <span class="text-gray-400">·</span>
                                        <span id="peek-rating-indicator-label" class="text-gray-700 dark:text-gray-300 font-bold">
                                            @php
                                                $currVal = (int) old('rating', $userReview ? $userReview->rating : 5);
                                                $labelsArr = [1 => 'Kecewa', 2 => 'Kurang', 3 => 'Cukup', 4 => 'Puas', 5 => 'Sangat Puas'];
                                            @endphp
                                            {{ $labelsArr[$currVal] ?? 'Sangat Puas' }}
                                        </span>
                                    </div>
                                </div>

                                <input type="hidden" name="rating" id="review-rating-input" value="{{ old('rating', $userReview ? $userReview->rating : 5) }}">

                                @error('rating')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Textarea Komentar -->
                            <div>
                                <label for="comment" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wide">
                                    Komentar / Pengalaman Pembeli
                                    <span class="text-gray-400 font-normal lowercase">(opsional)</span>
                                </label>
                                <textarea id="comment" name="comment" rows="3"
                                          placeholder="Ceritakan pengalaman Anda mengenai kecepatan pengiriman, respon seller, atau kualitas item game ini..."
                                          class="w-full border @error('comment') border-red-400 bg-red-50 @else border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 @enderror rounded-xl p-3 text-xs text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-[#7c3aed] focus:ring-2 focus:ring-[#7c3aed]/15 transition-all">{{ old('comment', $userReview ? $userReview->comment : '') }}</textarea>
                                @error('comment')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                    class="inline-flex items-center gap-2 bg-[#7c3aed] hover:bg-[#6d28d9] text-white font-black px-5 py-2.5 rounded-xl shadow-md text-xs transition-all active:scale-95 cursor-pointer">
                                <span>⭐</span>
                                <span>{{ $userReview ? 'Simpan Perubahan Ulasan' : 'Kirim Ulasan Sekarang' }}</span>
                            </button>
                        </form>
                    @else
                        <!-- Guest Prompt -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-2 text-center sm:text-left">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🔒</span>
                                <div>
                                    <p class="font-extrabold text-xs sm:text-sm text-gray-900 dark:text-white">Ingin memberikan bintang & ulasan untuk produk ini?</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Silakan masuk atau daftar akun terlebih dahulu untuk memberikan ulasan publik.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-[#7c3aed] hover:bg-[#6d28d9] text-white text-xs font-bold rounded-xl transition-all shadow-xs">
                                    Masuk Akun
                                </a>
                                <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-900 dark:text-white text-xs font-bold rounded-xl transition-all">
                                    Daftar
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>

                <!-- DAFTAR SEMUA ULASAN PUBLIK -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                        Semua Ulasan Pelanggan ({{ $totalReviews }})
                    </h3>

                    @forelse($product->reviews as $review)
                        <div class="p-4 sm:p-5 rounded-2xl bg-gray-50/70 dark:bg-gray-800/40 border border-gray-200/60 dark:border-gray-800 space-y-2.5 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    @if($review->user?->avatar_url)
                                        <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" class="w-9 h-9 rounded-full object-cover shrink-0 border border-gray-200 dark:border-gray-700">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-950 text-[#7c3aed] dark:text-cyan-400 font-black text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($review->user?->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-extrabold text-xs text-gray-900 dark:text-white">
                                                {{ $review->user?->name ?? 'Pembeli gian games' }}
                                            </span>
                                            <span class="text-[9px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 px-1.5 py-0.2 rounded">
                                                ✓ Pembeli Terverifikasi
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-gray-400 mt-0.5">
                                            <!-- Stars -->
                                            <div class="text-amber-400 text-xs tracking-tighter">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                            <span>·</span>
                                            <span>{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete button jika pemilik ulasan atau admin -->
                                @if(auth()->check() && (auth()->id() === $review->user_id || auth()->user()->isAdmin()))
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-rose-600 text-xs p-1 rounded-lg transition-colors cursor-pointer" title="Hapus ulasan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @if(!empty($review->comment))
                                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed pl-12">
                                    {{ $review->comment }}
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 px-4 rounded-2xl bg-gray-50/50 dark:bg-gray-800/30 border border-dashed border-gray-300 dark:border-gray-700 space-y-2">
                            <span class="text-3xl">💬</span>
                            <p class="text-xs sm:text-sm font-bold text-gray-700 dark:text-gray-300">Belum ada ulasan untuk produk ini</p>
                            <p class="text-xs text-gray-400">Jadilah pembeli pertama yang memberikan penilaian bintang dan ulasan pengalaman Anda!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ================================================================= -->
        <!-- KOLOM KANAN (LEBAR: 4/12): STICKY ORDER & ACTION BOX               -->
        <!-- ================================================================= -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">

            <!-- Card Beli / Aksi Transaksi -->
            <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 sm:p-6 shadow-md space-y-5">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide block">Ringkasan Harga</span>
                    <div class="text-3xl font-black text-[#ff5e00] font-brand tracking-tight">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Info Status Stok & Pengiriman -->
                <div class="space-y-2.5 pt-2 border-t border-gray-100 dark:border-gray-800 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Status Ketersediaan</span>
                        @if($product->stock > 0)
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800">
                                Ready ({{ $product->stock }} unit)
                            </span>
                        @else
                            <span class="font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded-md border border-rose-200 dark:border-rose-800">
                                Habis
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Tipe Pengiriman</span>
                        <span class="font-bold text-gray-900 dark:text-white flex items-center gap-1">
                            @if($product->delivery_type === 'regular')
                                <span>📦 Pengiriman Biasa</span>
                            @else
                                <span class="text-amber-500">⚡ Instan Otomatis</span>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Rating Toko</span>
                        <span class="font-bold text-amber-500 flex items-center gap-1">
                            <span>★ {{ number_format($avgRating, 1) }}</span>
                            <span class="text-gray-400 font-normal">({{ $totalReviews }})</span>
                        </span>
                    </div>
                </div>

                <!-- Penjelasan Pengiriman Kilat -->
                <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-800/60 border border-gray-200/60 dark:border-gray-700 text-[11px] text-gray-600 dark:text-gray-400 leading-relaxed">
                    @if($product->delivery_type === 'regular')
                        <span>📦 <strong>Pesanan Biasa:</strong> Penjual akan langsung memproses pesanan Anda dalam antrean pengiriman reguler.</span>
                    @else
                        <span>⚡ <strong>Pengiriman Instan:</strong> Detail akun / kode produk langsung diterbitkan otomatis segera setelah pembayaran Anda sukses.</span>
                    @endif
                </div>

                <!-- Tombol Transaksi -->
                <div class="space-y-2.5 pt-2">
                    @if(auth()->check() && auth()->id() === $product->user_id)
                        <!-- Jika pemilik produk yang melihat -->
                        <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 text-center text-xs text-[#7c3aed] dark:text-cyan-300 font-bold mb-2">
                            Ini adalah produk di lapak Anda
                        </div>
                        <a href="{{ route('products.edit', $product) }}"
                           class="w-full py-3 bg-[#7c3aed] hover:bg-[#6d28d9] text-white font-extrabold text-xs rounded-xl shadow-md transition-all text-center block">
                            ✏️ Edit Informasi & Stok Produk
                        </a>
                        <a href="{{ route('products.index') }}"
                           class="w-full py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 text-gray-700 dark:text-gray-200 font-bold text-xs rounded-xl transition-all text-center block">
                            Kembali ke Lapak Saya
                        </a>
                    @else
                        <!-- Tombol untuk pembeli / publik -->
                        @if($product->stock > 0)
                            <form action="{{ route('orders.buy', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full py-3.5 bg-[#7c3aed] hover:bg-[#6d28d9] text-white font-black text-sm rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] cursor-pointer">
                                    <span>⚡</span>
                                    <span>Beli Sekarang</span>
                                </button>
                            </form>

                            <button type="button"
                                    data-product-id="{{ $product->id }}"
                                    onclick="addToCart(Number(this.dataset.productId), 1)"
                                    class="w-full py-3 bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs rounded-2xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>+ Tambah ke Keranjang</span>
                            </button>
                        @else
                            <button type="button" disabled
                                    class="w-full py-3.5 bg-gray-300 dark:bg-gray-800 text-gray-500 font-bold text-xs rounded-2xl cursor-not-allowed text-center">
                                Stok Saat Ini Habis
                            </button>
                        @endif
                    @endif
                </div>

                <!-- Jaminan Keamanan -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 space-y-2 text-[11px] text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-500">🛡️</span>
                        <span>100% Garansi Uang Kembali jika gagal</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-blue-500">🔒</span>
                        <span>Transaksi Terenkripsi & Dilindungi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-amber-500">💬</span>
                        <span>Layanan Chat Bantuan gian games 24/7</span>
                    </div>
                </div>
            </div>

            <!-- Produk Terkait / Lainnya -->
            @if($relatedProducts->count() > 0)
            <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wide">
                        Rekomendasi Lainnya
                    </h3>
                    <a href="{{ route('products.index') }}" class="text-[11px] font-bold text-[#7c3aed] dark:text-cyan-400 hover:underline">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach($relatedProducts->take(4) as $rel)
                        <a href="{{ route('products.show', $rel) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors group">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0 border border-gray-200 dark:border-gray-700">
                                @if($rel->image && Storage::disk('public')->exists($rel->image))
                                    <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-sm flex items-center justify-center">
                                        {{ strtoupper(substr($rel->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-gray-900 dark:text-white truncate group-hover:text-[#7c3aed] dark:group-hover:text-cyan-300 transition-colors">
                                    {{ $rel->name }}
                                </h4>
                                <div class="flex items-center justify-between mt-0.5">
                                    <span class="text-xs font-black text-[#ff5e00]">
                                        Rp {{ number_format($rel->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        Stok {{ $rel->stock }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const slot = document.getElementById('peek-rating-slot');
    const input = document.getElementById('review-rating-input');
    const scoreText = document.getElementById('peek-rating-indicator-score');
    const labelText = document.getElementById('peek-rating-indicator-label');
    const labels = ['Kecewa', 'Kurang', 'Cukup', 'Puas', 'Sangat Puas'];

    if (slot && window.PeekRating) {
        slot.innerHTML = '';
        new PeekRating(slot, {
            defaultValue: Number(input ? input.value : 5),
            count: 5,
            labels: ['1 ★ Kecewa', '2 ★ Kurang', '3 ★ Cukup', '4 ★ Puas', '5 ★ Sangat Puas'],
            activeColor: '#f5b400',
            idleColor: '#52525b',
            tipColor: '#1e293b',
            tipTextColor: '#f8fafc',
            size: 32,
            lift: 7,
            magnify: 1.15,
            riseDuration: 320,
            popScale: 1.3,
            showTip: true,
            allowClear: false,
            inputName: 'rating',
            onChange: function (val) {
                if (input) input.value = val;
                if (scoreText) scoreText.textContent = val + ' ★';
                if (labelText && labels[val - 1]) labelText.textContent = labels[val - 1];
            },
            onPreview: function (val) {
                if (val && scoreText) scoreText.textContent = val + ' ★';
                if (val && labelText && labels[val - 1]) labelText.textContent = labels[val - 1];
            }
        });
    }
});
</script>
@endsection
