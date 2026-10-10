@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', $store->name . ' - Profil Toko & Katalog Dagangan | gian games')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- ===== BREADCRUMB ===== -->
    <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}" class="hover:text-[#7c3aed] dark:hover:text-purple-300 transition-colors">
            Beranda
        </a>
        <span>/</span>
        <a href="{{ route('products.index') }}" class="hover:text-[#7c3aed] dark:hover:text-purple-300 transition-colors">
            Katalog
        </a>
        <span>/</span>
        <span class="text-gray-900 dark:text-gray-100 font-bold truncate">
            {{ $store->name }}
        </span>
    </nav>

    <!-- ===== STORE HEADER CARD ===== -->
    <div class="bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-5 sm:p-7 shadow-xs relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            
            <!-- Left: Store Avatar & Identity -->
            <div class="flex items-start sm:items-center gap-4 min-w-0">
                <!-- Avatar / Logo -->
                <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden shrink-0 border-2 border-purple-200 dark:border-purple-800/80 bg-gray-100 dark:bg-gray-800 shadow-sm flex items-center justify-center">
                    @if($store->logo && Storage::disk('public')->exists($store->logo))
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @elseif($store->seller?->avatar_url)
                        <img src="{{ $store->seller->avatar_url }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[#581c87] to-[#7c3aed] text-white flex items-center justify-center font-black text-2xl font-brand">
                            {{ strtoupper(substr($store->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="space-y-1.5 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white font-brand tracking-tight truncate">
                            {{ $store->name }}
                        </h1>
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-blue-500/10 text-[#7c3aed] dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 px-2 py-0.5 rounded-lg">
                            ✓ Penjual Resmi
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Aktif & Cepat Tanggap</span>
                        </span>
                        <span>•</span>
                        <span>Pemilik: <strong>{{ $store->seller?->name ?? 'Penjual Terverifikasi' }}</strong></span>
                    </p>

                    @if(!empty(trim($store->description)))
                        <p class="text-xs text-gray-600 dark:text-gray-300 max-w-2xl pt-1 leading-relaxed">
                            {{ $store->description }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Right: Real Data Badges & Action -->
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-800">
                <!-- Stat 1: Total Produk -->
                <div class="bg-gray-50 dark:bg-gray-800/60 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl px-4 py-2.5 text-center min-w-[90px]">
                    <span class="block text-base sm:text-lg font-black text-gray-900 dark:text-white font-brand">{{ $totalProducts }}</span>
                    <span class="text-[10px] text-gray-400 font-medium">Produk Aktif</span>
                </div>

                <!-- Stat 2: Rating Ulasan -->
                <div class="bg-gray-50 dark:bg-gray-800/60 border border-gray-200/80 dark:border-gray-700/60 rounded-2xl px-4 py-2.5 text-center min-w-[90px]">
                    <div class="flex items-center justify-center gap-1 text-base sm:text-lg font-black text-amber-500 font-brand">
                        <span>★</span>
                        <span>{{ number_format($avgRating, 1) }}</span>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $totalReviews }} Ulasan</span>
                </div>

                <!-- Actions if Owner/Admin -->
                @if(auth()->check() && (auth()->id() === $store->seller_id || auth()->user()->isAdmin()))
                    <a href="{{ route('products.create') }}"
                       class="inline-flex items-center gap-1.5 bg-[#7c3aed] hover:bg-[#6d28d9] text-white font-bold text-xs px-4 py-3 rounded-2xl shadow-sm transition-all focus-visible:ring-2 focus-visible:ring-[#7c3aed] focus-visible:outline-none">
                        <span>+</span>
                        <span>Tambah Dagangan</span>
                    </a>
                @endif
            </div>

        </div>
    </div>

    <!-- ===== SECTION HEADING ===== -->
    <div class="flex items-center justify-between pb-1 border-b border-gray-200/60 dark:border-gray-800">
        <div>
            <h2 class="text-base sm:text-lg font-black text-gray-900 dark:text-white font-brand">
                Katalog Produk Toko
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Daftar produk digital dan voucher game yang dijual oleh toko ini
            </p>
        </div>
        <span class="text-xs font-bold text-gray-400">
            {{ $products->total() }} Produk
        </span>
    </div>

    <!-- ===== PRODUCT GRID ===== -->
    @if($products->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5 sm:gap-4">
        @foreach ($products as $product)
        <div class="itemku-product-card p-3.5 group relative flex flex-col justify-between rounded-3xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 shadow-xs hover:-translate-y-1 transition-all duration-300 hover:shadow-lg">
            <div>
                <!-- Product Image -->
                <a href="{{ route('products.show', $product) }}" class="block relative w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 mb-3 border border-gray-100 dark:border-gray-800 group/img focus-visible:ring-2 focus-visible:ring-[#7c3aed] focus-visible:outline-none">
                    @php
                        $colors = ['from-blue-600 to-indigo-700','from-indigo-600 to-purple-700','from-emerald-600 to-teal-700','from-amber-600 to-red-700','from-purple-600 to-pink-700'];
                        $colorIdx = crc32($product->name) % count($colors);
                    @endphp
                    @if ($product->image && Storage::disk('public')->exists($product->image))
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br {{ $colors[$colorIdx] }} flex items-center justify-center text-white font-black text-4xl group-hover:scale-105 transition-transform duration-300">
                            {{ strtoupper(substr($product->name, 0, 1)) }}
                        </div>
                    @endif

                    <!-- Delivery Type Badge -->
                    @if($product->delivery_type === 'regular')
                        <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[9px] font-black px-2 py-0.5 rounded-lg shadow-md">
                            📦 Biasa
                        </span>
                    @else
                        <span class="absolute top-2 left-2 bg-gradient-to-r from-amber-400 to-amber-500 text-gray-950 text-[9px] font-black px-2 py-0.5 rounded-lg shadow-md flex items-center gap-0.5">
                            <span>⚡</span>
                            <span>Instan</span>
                        </span>
                    @endif

                    <!-- Rating Badge -->
                    <div class="absolute bottom-2 left-2 bg-black/75 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-md flex items-center gap-1 border border-white/15 backdrop-blur-xs shadow-xs">
                        <span class="text-amber-400">★</span>
                        <span>{{ number_format($product->averageRating(), 1) }}</span>
                    </div>

                    <!-- Stock Badge -->
                    <div class="absolute bottom-2 right-2">
                        @if ($product->stock > 5)
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-white/90 dark:bg-slate-900/90 text-emerald-700 dark:text-emerald-400 shadow-xs border border-emerald-200 dark:border-emerald-800">
                                Stok {{ $product->stock }}
                            </span>
                        @elseif ($product->stock > 0)
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-white/90 dark:bg-slate-900/90 text-amber-600 dark:text-amber-400 shadow-xs border border-amber-200 dark:border-amber-800">
                                Sisa {{ $product->stock }}
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-rose-600 text-white shadow-xs">
                                Habis
                            </span>
                        @endif
                    </div>
                </a>

                <!-- Product Info -->
                <div class="space-y-1">
                    <h3 class="font-black text-gray-900 dark:text-white text-xs truncate group-hover:text-[#7c3aed] dark:group-hover:text-purple-300 transition-colors" title="{{ $product->name }}">
                        <a href="{{ route('products.show', $product) }}" class="hover:underline focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-[#7c3aed]">
                            {{ $product->name }}
                        </a>
                    </h3>
                    <div class="pt-1 flex items-baseline justify-between">
                        <span class="font-black text-sm text-[#ff5e00]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action button -->
            <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-800">
                <a href="{{ route('products.show', $product) }}"
                   class="block w-full text-center bg-purple-50 dark:bg-purple-950/60 hover:bg-[#7c3aed] hover:text-white text-[#7c3aed] dark:text-purple-300 text-[11px] font-bold py-2 rounded-xl transition-all focus-visible:ring-2 focus-visible:ring-[#7c3aed] focus-visible:outline-none">
                    Lihat Produk
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ===== PAGINATION ===== -->
    @if ($products->hasPages())
        <div class="flex justify-center pt-4">
            {{ $products->links() }}
        </div>
    @endif

    @else
    <!-- ===== EMPTY STATE (R-27) ===== -->
    <div class="text-center py-16 bg-white dark:bg-[#111827] rounded-3xl border border-gray-200/80 dark:border-gray-800 p-8 space-y-3">
        <div class="text-5xl">📦</div>
        <h3 class="text-base font-black text-gray-900 dark:text-white">Toko Belum Memiliki Produk Aktif</h3>
        <p class="text-xs text-gray-400 max-w-sm mx-auto">
            Penjual saat ini belum memiliki produk yang siap dibeli. Silakan kembali lagi nanti atau telusuri produk dari penjual lainnya.
        </p>
        <div class="pt-2">
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 bg-[#7c3aed] hover:bg-[#6d28d9] text-white font-bold px-5 py-2.5 rounded-2xl shadow-md text-xs transition-all focus-visible:ring-2 focus-visible:ring-[#7c3aed] focus-visible:outline-none">
                <span>Lihat Semua Produk Lainnya</span>
            </a>
        </div>
    </div>
    @endif

</div>
@endsection
