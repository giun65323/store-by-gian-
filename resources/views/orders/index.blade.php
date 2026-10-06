@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Riwayat Pesanan & Transaksi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-6 space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 flex items-center gap-2">
                <span>📑 Riwayat Transaksi & Pesanan</span>
            </h1>
            <p class="text-xs text-gray-500 mt-1">Lacak status pesanan game, voucher digital, dan chat langsung dengan penjual</p>
        </div>
    </div>

    <!-- Search Order Number Box -->
    <div class="bg-white border border-gray-200 rounded-2xl p-4 sm:p-5 shadow-xs">
        <h2 class="text-xs font-bold text-gray-700 mb-2 flex items-center gap-1.5">
            <span>🔍 Cari Nomor Pesanan (Order Number)</span>
        </h2>
        <form action="{{ route('orders.search') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2.5">
            @csrf
            <div class="relative flex-1 w-full">
                <input type="text"
                       name="order_number"
                       value="{{ old('order_number') }}"
                       placeholder="Masukkan nomor pesanan (Contoh: OD000000164288180)"
                       required
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3.5 py-2.5 text-xs font-mono placeholder:font-sans placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0050ae] focus:bg-white transition-all">
            </div>
            <button type="submit"
                    class="w-full sm:w-auto bg-[#0050ae] hover:bg-[#003d87] text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-all shadow-xs shrink-0 flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span>Cari Transaksi</span>
            </button>
        </form>
        @error('order_search')
            <p class="text-rose-500 text-xs font-medium mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Purchases Tab -->
    <div class="space-y-3">
        <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
            <span>🛒 Pembelian Saya</span>
            <span class="text-xs bg-blue-50 text-[#0050ae] px-2 py-0.5 rounded-full font-bold">{{ $myPurchases->total() }}</span>
        </h2>

        @if($myPurchases->isEmpty())
            <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center text-gray-400 space-y-2">
                <span class="text-4xl block">🛍️</span>
                <p class="text-xs font-medium text-gray-600">Belum ada transaksi pembelian.</p>
                <a href="{{ route('dashboard') }}" class="inline-block mt-2 text-xs font-bold text-[#0050ae] hover:underline">
                    Jelajahi Produk Sekarang →
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($myPurchases as $order)
                    @php
                        $unreadChatCount = $order->messages()->where('receiver_id', auth()->id())->where('is_read', false)->count();
                    @endphp
                    <div class="bg-white border {{ $unreadChatCount > 0 ? 'border-blue-300 ring-1 ring-blue-200' : 'border-gray-100' }} rounded-2xl p-4 sm:p-5 shadow-xs hover:border-blue-200 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-gray-900 text-white flex items-center justify-center text-lg shrink-0 overflow-hidden">
                                @if($order->product_image && Storage::disk('public')->exists($order->product_image))
                                    <img src="{{ asset('storage/' . $order->product_image) }}" class="w-full h-full object-cover">
                                @else
                                    🎮
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-[11px] font-mono font-bold text-gray-500">{{ $order->order_number }}</span>
                                    @if($order->status === 'completed')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">
                                            ✓ Selesai
                                        </span>
                                    @elseif($order->status === 'processing')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">
                                            ⏳ Diproses Penjual
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">
                                            Menunggu Bayar
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif

                                    @if($order->delivery_type === 'Pengiriman BIASA')
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200/60">
                                            📦 Biasa
                                        </span>
                                    @else
                                        <span class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200/60">
                                            ⚡ Instan
                                        </span>
                                    @endif

                                    @if($unreadChatCount > 0)
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-500 text-white animate-pulse flex items-center gap-1">
                                            💬 {{ $unreadChatCount }} Pesan Baru
                                        </span>
                                    @endif
                                </div>
                                <h3 class="font-bold text-sm text-gray-900 truncate mt-0.5">{{ $order->product_name }}</h3>
                                <p class="text-xs text-gray-400">
                                    {{ $order->store ? $order->store->name : ($order->seller?->name ?? 'Penjual') }} • {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-3 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100 shrink-0">
                            <div class="text-left sm:text-right">
                                <span class="text-[10px] text-gray-400 block">Total Bayar</span>
                                <span class="font-bold text-xs text-[#ff5e00]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('orders.show', $order->order_number) }}{{ $unreadChatCount > 0 ? '?open_chat=1' : '' }}"
                               class="inline-flex items-center gap-1 bg-[#0050ae] hover:bg-[#003d87] text-white text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs">
                                <span>{{ $unreadChatCount > 0 ? '💬 Buka Chat' : 'Lihat Rincian' }}</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pt-2">
                {{ $myPurchases->links() }}
            </div>
        @endif
    </div>

    <!-- Sales Tab (If seller) -->
    @if(auth()->user()->isSeller() || $mySales->isNotEmpty())
    <div class="space-y-3 pt-4">
        <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2">
            <span>📦 Pesanan Masuk (Penjualan)</span>
            <span class="text-xs bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full font-bold">{{ $mySales->total() }}</span>
        </h2>

        @if($mySales->isEmpty())
            <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center text-gray-400">
                <p class="text-xs font-medium text-gray-600">Belum ada pesanan masuk untuk toko Anda.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($mySales as $sale)
                    @php
                        $unreadSaleChatCount = $sale->messages()->where('receiver_id', auth()->id())->where('is_read', false)->count();
                    @endphp
                    <div class="bg-white border {{ $unreadSaleChatCount > 0 ? 'border-amber-300 ring-1 ring-amber-200' : 'border-gray-100' }} rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[11px] font-mono font-bold text-gray-500">{{ $sale->order_number }}</span>
                                @if($sale->status === 'completed')
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">
                                        ✓ Selesai
                                    </span>
                                @elseif($sale->status === 'processing')
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-600 text-white animate-pulse">
                                        📦 Perlu Dikirim
                                    </span>
                                @elseif($sale->status === 'pending')
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-600">
                                        Menunggu Bayar
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                @endif

                                @if($sale->delivery_type === 'Pengiriman BIASA')
                                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200/60">
                                        📦 Biasa
                                    </span>
                                @else
                                    <span class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200/60">
                                        ⚡ Instan
                                    </span>
                                @endif
                                @if($unreadSaleChatCount > 0)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-500 text-white animate-pulse flex items-center gap-1">
                                        💬 {{ $unreadSaleChatCount }} Chat Pembeli
                                    </span>
                                @endif
                            </div>
                            <h3 class="font-bold text-sm text-gray-900 truncate mt-0.5">{{ $sale->product_name }}</h3>
                            <p class="text-xs text-gray-400">Pembeli: {{ $sale->buyer?->name }} • {{ $sale->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-3 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100 shrink-0">
                            <div class="text-left sm:text-right">
                                <span class="text-[10px] text-gray-400 block">Penghasilan</span>
                                <span class="font-bold text-xs text-emerald-600">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('orders.show', $sale->order_number) }}{{ $unreadSaleChatCount > 0 ? '?open_chat=1' : '' }}"
                               class="inline-flex items-center gap-1 {{ $unreadSaleChatCount > 0 ? 'bg-[#0050ae] text-white hover:bg-[#003d87]' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }} text-xs font-bold px-3.5 py-2 rounded-xl transition-all shadow-xs">
                                <span>{{ $unreadSaleChatCount > 0 ? '💬 Balas Chat' : 'Rincian & Chat' }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pt-2">
                {{ $mySales->links() }}
            </div>
        @endif
    </div>
    @endif

</div>
@endsection
