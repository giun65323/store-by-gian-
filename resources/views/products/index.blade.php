@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', auth()->user()->isAdmin() ? 'Katalog & Moderasi Seluruh Produk — Admin gian games' : 'Katalog Produk Saya — gian games')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- ===== PAGE HEADER ===== -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 rounded-3xl p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="text-2xl">🏬</span>
                <h1 class="text-xl lg:text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                    @if(auth()->user()->isAdmin())
                        Katalog & Moderasi Seluruh Produk
                    @else
                        Katalog Produk Jualan Saya
                    @endif
                </h1>
                @if(auth()->user()->isAdmin())
                    <span class="bg-[#ffb800] text-gray-950 font-black text-[10px] px-2.5 py-0.5 rounded-full shadow-xs">ADMIN MODE</span>
                @endif
            </div>
            <p class="text-xs text-gray-400 mt-1">
                @if(auth()->user()->isAdmin())
                    Menampilkan total <strong class="text-[#0050ae] dark:text-sky-400">{{ $products->total() }}</strong> produk dari seluruh penjual di platform gian games.
                @else
                    Total <strong class="text-[#0050ae] dark:text-sky-400">{{ $products->total() }}</strong> produk aktif di lapak Anda
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center gap-1.5 bg-[#07193b] hover:bg-[#0050ae] text-white font-black px-4 py-2.5 rounded-2xl shadow transition-all text-xs shrink-0 hover:scale-105">
                    <span>🛡️ Pusat Kontrol Admin</span>
                </a>
            @endif
            <a href="{{ route('products.create') }}"
               class="inline-flex items-center gap-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-black px-5 py-2.5 rounded-2xl shadow-md transition-all text-xs active:scale-95 shrink-0 hover:scale-105">
                <span>+</span>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    <!-- ===== PRODUCT GRID (ITEMKU STYLE CARDS) ===== -->
    @if($products->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach ($products as $product)
        <div class="itemku-product-card p-3.5 group relative flex flex-col justify-between rounded-3xl bg-white dark:bg-[#111827] border border-gray-200/80 dark:border-gray-800 shadow-xs hover:-translate-y-1 transition-all duration-300 hover:shadow-lg">
            <div>
                <!-- Product Image / Color Placeholder -->
                <div class="relative w-full aspect-square rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 mb-3 border border-gray-100 dark:border-gray-800">
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
                </div>

                <!-- Product Info -->
                <div class="space-y-1">
                    <h3 class="font-black text-gray-900 dark:text-white text-xs truncate group-hover:text-[#0050ae] dark:group-hover:text-sky-400 transition-colors" title="{{ $product->name }}">
                        {{ $product->name }}
                    </h3>
                    @if(auth()->user()->isAdmin())
                        <div class="text-[10px] font-bold text-indigo-700 dark:text-indigo-300 truncate bg-indigo-50 dark:bg-indigo-950 px-2 py-0.5 rounded-md">
                            👤 {{ $product->user?->name ?? 'User #' . $product->user_id }}
                        </div>
                    @else
                        <div class="text-[11px] text-gray-400">Pengiriman Kilat</div>
                    @endif
                    <div class="pt-1 flex items-baseline justify-between">
                        <span class="font-black text-sm text-[#ff5e00]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action buttons (Bottom) -->
            <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between gap-2">
                <a href="{{ route('products.edit', $product) }}"
                   class="flex-1 text-center bg-gray-100 dark:bg-gray-800 hover:bg-[#0050ae] hover:text-white text-gray-700 dark:text-gray-200 text-xs font-bold py-2 rounded-xl transition-all">
                    Edit
                </a>
                <!-- Delete button (Admin or Owner) -->
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk \'{{ $product->name }}\'?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors cursor-pointer"
                            title="{{ auth()->user()->isAdmin() ? 'Hapus Produk Ini (Hak Akses Admin)' : 'Hapus Produk' }}">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
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
    <div class="text-center py-16 bg-white dark:bg-[#111827] rounded-3xl border border-gray-200/80 dark:border-gray-800 p-8 space-y-3">
        <div class="text-5xl">📦</div>
        <h3 class="text-base font-black text-gray-900 dark:text-white">Belum Ada Produk Jualan</h3>
        <p class="text-xs text-gray-400 max-w-sm mx-auto">Mulai tambahkan voucher game atau akun digital untuk mulai berjualan hari ini.</p>
        <div class="pt-2">
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-black px-5 py-2.5 rounded-2xl shadow-md text-xs transition-all hover:scale-105">
                <span>+</span>
                <span>Tambah Produk Pertama</span>
            </a>
        </div>
    </div>
    @endif

</div>
@endsection
