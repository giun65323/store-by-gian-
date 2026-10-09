@extends('layouts.app')
@php use Illuminate\Support\Facades\Storage; @endphp

@section('title', 'Pusat Kontrol & Moderasi Admin — gian games')

@section('head')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }
    .admin-tab-btn.active {
        background: #0050ae;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(0, 80, 174, 0.25);
    }
    .admin-tab-btn:not(.active) {
        background: #f1f5f9;
        color: #475569;
    }
    .admin-tab-btn:not(.active):hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .badge-admin {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #ffffff;
    }
    .badge-seller {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
    }
    .badge-buyer {
        background: linear-gradient(135deg, #64748b, #475569);
        color: #ffffff;
    }
    .role-card-radio:checked + label {
        border-color: #0050ae;
        background-color: #f0f7ff;
        box-shadow: 0 0 0 2px rgba(0, 80, 174, 0.2);
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- ===== ADMIN HERO HEADER ===== -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#061838] via-[#0b285f] to-[#0050ae] p-6 sm:p-8 text-white shadow-2xl border border-blue-800/40">
        <!-- Background Ambient Glow -->
        <div class="absolute -right-10 -top-10 h-72 w-72 rounded-full bg-blue-400/15 blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -bottom-10 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-400 to-amber-500 px-3.5 py-1 text-[11px] font-black text-gray-900 shadow-md">
                    <span>🛡️ ADMINISTRATOR PLATFORM</span>
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-900"></span>
                    <span>HAK AKSES PENUH</span>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                    Pusat Kontrol & Manajemen Role
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/90 max-w-2xl leading-relaxed">
                    Kelola hak akses pengguna (Admin, Seller, Buyer), verifikasi pendaftaran seller baru dengan KTP & Selfie, kontrol moderasi toko dan seluruh produk marketplace.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-gray-950 font-black text-xs px-4 py-2.5 rounded-xl shadow-md transition-all hover:scale-105 active:scale-95">
                    <span>+</span>
                    <span>Tambah Produk Resmi</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white font-bold text-xs px-4 py-2.5 rounded-xl border border-white/20 transition-all">
                    <span>🏬</span>
                    <span>Lihat Marketplace</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ===== STATS OVERVIEW CARDS ===== -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Card Pengguna -->
        <div onclick="switchTab('tab-users')" class="cursor-pointer bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all hover:-translate-y-0.5 group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pengguna</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">{{ number_format($totalUsers) }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#0050ae] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    👥
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-[#0050ae]">
                <span>Kelola Role Pengguna</span>
                <span class="transition-transform group-hover:translate-x-1">→</span>
            </div>
        </div>

        <!-- Card Pending Seller -->
        <div onclick="switchTab('tab-verifications')" class="cursor-pointer bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all hover:-translate-y-0.5 group relative overflow-hidden">
            @if(isset($pendingSellers) && $pendingSellers->count() > 0)
                <div class="absolute top-0 right-0 w-2 h-full bg-amber-500 animate-pulse"></div>
            @endif
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Verifikasi Seller</span>
                    <div class="text-2xl sm:text-3xl font-black {{ $pendingSellers->count() > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1">
                        {{ $pendingSellers->count() }}
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    🪪
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-amber-600">
                <span>{{ $pendingSellers->count() > 0 ? $pendingSellers->count() . ' Pengajuan Menunggu' : 'Semua Terverifikasi' }}</span>
                <span class="transition-transform group-hover:translate-x-1">→</span>
            </div>
        </div>

        <!-- Card Toko -->
        <div onclick="switchTab('tab-stores')" class="cursor-pointer bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all hover:-translate-y-0.5 group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Toko</span>
                    <div class="text-2xl sm:text-3xl font-black text-purple-700 mt-1">{{ number_format($totalStores) }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    🏬
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-purple-600">
                <span>Moderasi Toko Penjual</span>
                <span class="transition-transform group-hover:translate-x-1">→</span>
            </div>
        </div>

        <!-- Card Produk -->
        <div onclick="switchTab('tab-products')" class="cursor-pointer bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs hover:shadow-md transition-all hover:-translate-y-0.5 group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Produk</span>
                    <div class="text-2xl sm:text-3xl font-black text-[#ff5e00] mt-1">{{ number_format($totalProducts) }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    📦
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-[#ff5e00]">
                <span>Moderasi Produk Seller</span>
                <span class="transition-transform group-hover:translate-x-1">→</span>
            </div>
        </div>
    </div>

    <!-- ===== SMART TAB NAVIGATION BAR ===== -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200/80 scrollbar-none">
        <button type="button"
                onclick="switchTab('tab-users')"
                id="btn-tab-users"
                class="admin-tab-btn active inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-extrabold text-xs transition-all cursor-pointer shrink-0">
            <span>👥</span>
            <span>Pengguna & Role</span>
            <span class="bg-white/20 text-white px-2 py-0.5 rounded-full text-[10px] font-bold">
                {{ $totalUsers }}
            </span>
        </button>

        <button type="button"
                onclick="switchTab('tab-verifications')"
                id="btn-tab-verifications"
                class="admin-tab-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-extrabold text-xs transition-all cursor-pointer shrink-0">
            <span>🪪</span>
            <span>Verifikasi Seller</span>
            @if($pendingSellers->count() > 0)
                <span class="bg-amber-500 text-white px-2 py-0.5 rounded-full text-[10px] font-black animate-pulse">
                    {{ $pendingSellers->count() }} Baru
                </span>
            @else
                <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-[10px] font-bold">0</span>
            @endif
        </button>

        <button type="button"
                onclick="switchTab('tab-products')"
                id="btn-tab-products"
                class="admin-tab-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-extrabold text-xs transition-all cursor-pointer shrink-0">
            <span>📦</span>
            <span>Moderasi Produk</span>
            <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-[10px] font-bold">
                {{ $totalProducts }}
            </span>
        </button>

        <button type="button"
                onclick="switchTab('tab-stores')"
                id="btn-tab-stores"
                class="admin-tab-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-extrabold text-xs transition-all cursor-pointer shrink-0">
            <span>🏬</span>
            <span>Moderasi Toko</span>
            <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-[10px] font-bold">
                {{ $totalStores }}
            </span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: MANAJEMEN PENGGUNA & ROLE (USER & ROLE MANAGER) -->
    <!-- ========================================================================= -->
    <div id="tab-users" class="admin-tab-content space-y-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            
            <!-- Tab Header & Quick Filter -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span>👥 Manajemen Pengguna & Hak Akses Role</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Tambahkan atau ubah peran pengguna menjadi <strong>Admin</strong>, <strong>Seller</strong>, atau <strong>Buyer</strong> secara instan.
                    </p>
                </div>

                <!-- Instant Search Box -->
                <div class="flex items-center gap-3">
                    <div class="relative w-full sm:w-64">
                        <input type="text"
                               id="userSearchInput"
                               onkeyup="filterUsersTable()"
                               placeholder="Cari nama / email / role..."
                               class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#0050ae] focus:ring-2 focus:ring-[#0050ae]/15 outline-none transition-all">
                        <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🔍</span>
                    </div>

                    <select id="userRoleFilter" onchange="filterUsersTable()" class="px-3 py-2 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-700 outline-none">
                        <option value="">Semua Role</option>
                        <option value="ADMIN">🛡️ Admin</option>
                        <option value="SELLER">🏬 Seller</option>
                        <option value="BUYER">👤 Buyer</option>
                    </select>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="usersTable">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-4">Pengguna</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">Peran (Role)</th>
                            <th class="py-3.5 px-4">Status Seller</th>
                            <th class="py-3.5 px-4">Status Akun</th>
                            <th class="py-3.5 px-4 text-right">Kelola Akses & Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($allUsers as $u)
                        @php
                            $roleName = strtoupper($u->role?->name ?? 'BUYER');
                            $isCurrentAdmin = ($u->id === auth()->id());
                        @endphp
                        <tr class="user-row hover:bg-slate-50/80 transition-colors"
                            data-name="{{ strtolower($u->name) }}"
                            data-email="{{ strtolower($u->email) }}"
                            data-role="{{ $roleName }}">
                            
                            <!-- User Name & Avatar -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    @if($u->avatar_url)
                                        <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-xl object-cover shrink-0 shadow-xs" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-9 h-9 rounded-xl items-center justify-center font-black text-white text-xs shrink-0 shadow-xs {{ $u->isAdmin() ? 'bg-gradient-to-tr from-amber-500 to-amber-400' : ($u->isSeller() ? 'bg-gradient-to-tr from-blue-600 to-indigo-600' : 'bg-gradient-to-tr from-slate-600 to-slate-500') }}" style="display:none;">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-white text-xs shrink-0 shadow-xs {{ $u->isAdmin() ? 'bg-gradient-to-tr from-amber-500 to-amber-400' : ($u->isSeller() ? 'bg-gradient-to-tr from-blue-600 to-indigo-600' : 'bg-gradient-to-tr from-slate-600 to-slate-500') }}">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-extrabold text-slate-900 flex items-center gap-1.5">
                                            <span>{{ $u->name }}</span>
                                            @if($isCurrentAdmin)
                                                <span class="bg-amber-100 text-amber-800 text-[9px] font-black px-1.5 py-0.2 rounded">Anda</span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $u->id }} • Gabung {{ $u->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                                {{ $u->email }}
                            </td>

                            <!-- Role Badge -->
                            <td class="py-3.5 px-4">
                                @if($u->isAdmin())
                                    <span class="inline-flex items-center gap-1 badge-admin px-3 py-1 rounded-full text-[10px] font-black shadow-xs">
                                        <span>🛡️</span>
                                        <span>ADMIN</span>
                                    </span>
                                @elseif($u->isSeller())
                                    <span class="inline-flex items-center gap-1 badge-seller px-3 py-1 rounded-full text-[10px] font-black shadow-xs">
                                        <span>🏬</span>
                                        <span>SELLER</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 badge-buyer px-3 py-1 rounded-full text-[10px] font-black shadow-xs">
                                        <span>👤</span>
                                        <span>BUYER</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Seller Status -->
                            <td class="py-3.5 px-4">
                                @if($u->isSeller())
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Seller Aktif
                                    </span>
                                @elseif($u->seller_status === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($u->seller_status === 'rejected')
                                    <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Pengajuan Ditolak
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px] font-medium">-</span>
                                @endif
                            </td>

                            <!-- Account Status -->
                            <td class="py-3.5 px-4">
                                @if($u->is_active)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold px-2.5 py-0.5 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Diblokir
                                    </span>
                                @endif
                            </td>

                            <!-- Actions & Role Manager -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    
                                    <!-- TOMBOL UBAH ROLE (MODAL POPUP) -->
                                    <button type="button"
                                            onclick="openRoleModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->email }}', '{{ $u->role?->name ?? 'buyer' }}', {{ $isCurrentAdmin ? 'true' : 'false' }})"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#0050ae] hover:bg-[#003d87] text-white rounded-xl font-bold text-xs transition-all shadow-xs hover:scale-105 active:scale-95 cursor-pointer">
                                        <span>🏷️</span>
                                        <span>Ubah Role</span>
                                    </button>

                                    <!-- Quick Demote Seller -> Buyer -->
                                    @if($u->isSeller() && !$u->isAdmin())
                                        <form action="{{ route('admin.users.revoke-seller', $u) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Copot status Seller dari \'{{ $u->name }}\' dan kembalikan menjadi Buyer?');">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 rounded-xl font-bold text-[11px] transition-colors cursor-pointer"
                                                    title="Copot Seller menjadi Buyer">
                                                Copot Seller
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Toggle Active / Suspend Account -->
                                    @if(!$isCurrentAdmin)
                                        <form action="{{ route('admin.users.toggle', $u) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1.5 {{ $u->is_active ? 'bg-rose-50 hover:bg-rose-100 text-rose-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }} rounded-xl font-bold text-[11px] transition-colors cursor-pointer"
                                                    title="{{ $u->is_active ? 'Blokir akun pengguna ini' : 'Buka blokir akun' }}">
                                                {{ $u->is_active ? 'Blokir' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pt-3 border-t border-slate-100">
                {{ $allUsers->links() }}
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: VERIFIKASI PENDAFTARAN SELLER BARU -->
    <!-- ========================================================================= -->
    <div id="tab-verifications" class="admin-tab-content hidden space-y-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span>🪪 Verifikasi Dokumen KTP & Selfie Seller</span>
                        @if($pendingSellers->count() > 0)
                            <span class="text-xs bg-amber-500 text-white px-2.5 py-0.5 rounded-full font-black animate-pulse">
                                {{ $pendingSellers->count() }} Menunggu
                            </span>
                        @endif
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Tinjau foto KTP dan selfie kamera pendaftar. Setujui untuk meng-upgrade akun ke Seller secara resmi.
                    </p>
                </div>
            </div>

            @if($pendingSellers->isEmpty())
                <div class="text-center py-12 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <div class="text-4xl mb-2">🎉</div>
                    <h3 class="text-sm font-bold text-slate-800">Tidak ada pendaftaran yang menunggu</h3>
                    <p class="text-xs text-slate-400 mt-1">Semua pengajuan pendaftaran seller telah ditinjau.</p>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach($pendingSellers as $ps)
                    <div class="bg-gradient-to-br from-white to-amber-50/40 border-2 border-amber-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-amber-500 text-white font-black flex items-center justify-center text-sm shadow-md">
                                    {{ strtoupper(substr($ps->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-sm">{{ $ps->name }}</h3>
                                    <p class="text-xs font-mono text-slate-500">{{ $ps->email }}</p>
                                    <span class="text-[10px] text-amber-700 font-semibold mt-0.5 inline-block">
                                        ⏱️ Diajukan {{ $ps->seller_applied_at ? $ps->seller_applied_at->diffForHumans() : 'Baru saja' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Preview Grid -->
                        <div class="grid grid-cols-2 gap-3 pt-2">
                            <!-- KTP Preview -->
                            <div class="bg-white rounded-xl p-2.5 border border-slate-200 text-center shadow-xs">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase block mb-1.5">🪪 Foto KTP</span>
                                @if($ps->ktp_photo && Storage::disk('public')->exists($ps->ktp_photo))
                                    <a href="{{ asset('storage/' . $ps->ktp_photo) }}" target="_blank" class="block group relative overflow-hidden rounded-lg">
                                        <img src="{{ asset('storage/' . $ps->ktp_photo) }}" alt="KTP" class="h-28 w-full object-cover rounded-lg group-hover:scale-105 transition-transform">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[11px] font-bold">
                                            🔍 Perbesar
                                        </div>
                                    </a>
                                @else
                                    <div class="h-28 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">Tidak ada</div>
                                @endif
                            </div>

                            <!-- Selfie Preview -->
                            <div class="bg-white rounded-xl p-2.5 border border-slate-200 text-center shadow-xs">
                                <span class="text-[10px] font-extrabold text-slate-500 uppercase block mb-1.5">🤳 Selfie Kamera</span>
                                @if($ps->selfie_photo && Storage::disk('public')->exists($ps->selfie_photo))
                                    <a href="{{ asset('storage/' . $ps->selfie_photo) }}" target="_blank" class="block group relative overflow-hidden rounded-lg">
                                        <img src="{{ asset('storage/' . $ps->selfie_photo) }}" alt="Selfie" class="h-28 w-full object-cover rounded-lg group-hover:scale-105 transition-transform">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[11px] font-bold">
                                            🔍 Perbesar
                                        </div>
                                    </a>
                                @else
                                    <div class="h-28 bg-slate-100 rounded-lg flex items-center justify-center text-xs text-slate-400">Tidak ada</div>
                                @endif
                            </div>
                        </div>

                        <!-- Decision Buttons -->
                        <div class="flex items-center gap-2 pt-2 border-t border-amber-200/60">
                            <form action="{{ route('admin.users.approve-seller', $ps) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Setujui pendaftaran seller untuk \'{{ $ps->name }}\'?');">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-extrabold text-xs shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <span>✓</span>
                                    <span>Setujui Menjadi Seller</span>
                                </button>
                            </form>

                            <form action="{{ route('admin.users.reject-seller', $ps) }}" method="POST" class="flex-1"
                                  onsubmit="return confirm('Tolak pendaftaran seller untuk \'{{ $ps->name }}\'?');">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-extrabold text-xs shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <span>✕</span>
                                    <span>Tolak Pengajuan</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: MODERASI PRODUK (PRODUCTS MODERATION) -->
    <!-- ========================================================================= -->
    <div id="tab-products" class="admin-tab-content hidden space-y-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span>📦 Moderasi Produk Marketplace</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Admin dapat mengedit, menangguhkan, atau menghapus produk dari seller manapun.</p>
                </div>
                <div class="relative w-full sm:w-64">
                    <input type="text"
                           id="productSearchInput"
                           onkeyup="filterProductsTable()"
                           placeholder="Cari judul / seller / toko..."
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#0050ae] outline-none">
                    <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🔍</span>
                </div>
            </div>

            @if($allProducts->isEmpty())
                <div class="text-center py-10 text-slate-400 text-xs">Belum ada produk di database.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs" id="productsTable">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider border-b border-slate-200">
                                <th class="py-3 px-3">Produk</th>
                                <th class="py-3 px-3">Pemilik (Seller)</th>
                                <th class="py-3 px-3">Harga</th>
                                <th class="py-3 px-3">Stok</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3 text-right">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($allProducts as $p)
                            <tr class="product-row hover:bg-slate-50/80 transition-colors"
                                data-name="{{ strtolower($p->name) }}"
                                data-seller="{{ strtolower($p->user?->name ?? '') }}"
                                data-store="{{ strtolower($p->store?->name ?? '') }}">
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-900 text-white flex items-center justify-center shrink-0 shadow-xs">
                                            @if($p->image && Storage::disk('public')->exists($p->image))
                                                <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-cover">
                                            @else
                                                🎮
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 truncate max-w-xs" title="{{ $p->name }}">{{ $p->name }}</div>
                                            <span class="text-[10px] text-slate-400">ID #{{ $p->id }} • {{ $p->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-800">{{ $p->user?->name ?? 'User #' . $p->user_id }}</div>
                                    <span class="text-[10px] text-slate-400">{{ $p->store?->name ?? 'Tanpa Toko' }}</span>
                                </td>
                                <td class="py-3 px-3 font-extrabold text-[#ff5e00]">
                                    Rp {{ number_format($p->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 font-bold text-slate-700">
                                    {{ $p->stock }} unit
                                </td>
                                <td class="py-3 px-3">
                                    @if($p->status === 'active')
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Aktif</span>
                                    @elseif($p->status === 'suspended')
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Ditangguhkan</span>
                                    @else
                                        <span class="bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Dihapus Admin</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('products.edit', $p) }}"
                                           class="px-2.5 py-1 bg-slate-100 hover:bg-[#0050ae] hover:text-white rounded-lg font-bold text-[11px] text-slate-700 transition-colors">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.products.status', $p) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="{{ $p->status === 'active' ? 'suspended' : 'active' }}">
                                            <button type="submit"
                                                    class="px-2.5 py-1 {{ $p->status === 'active' ? 'bg-amber-50 hover:bg-amber-100 text-amber-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }} rounded-lg font-bold text-[11px] transition-colors"
                                                    title="Ubah Status">
                                                {{ $p->status === 'active' ? 'Suspend' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.products.delete', $p) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Hapus produk \'{{ $p->name }}\'?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-bold text-[11px] transition-colors shadow-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pt-2">
                    {{ $allProducts->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 4: MODERASI TOKO (STORES MODERATION) -->
    <!-- ========================================================================= -->
    <div id="tab-stores" class="admin-tab-content hidden space-y-4">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <span>🏬 Moderasi Toko Seller</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Admin dapat menangguhkan lapak toko penjual yang melanggar aturan.</p>
                </div>
                <span class="text-xs font-bold text-slate-500">Total: {{ $allStores->total() }} Toko</span>
            </div>

            @if($allStores->isEmpty())
                <div class="text-center py-6 text-slate-400 text-xs">Belum ada toko terdaftar.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider border-b border-slate-200">
                                <th class="py-3 px-3">Toko</th>
                                <th class="py-3 px-3">Pemilik (Seller)</th>
                                <th class="py-3 px-3">Total Produk</th>
                                <th class="py-3 px-3">Status Toko</th>
                                <th class="py-3 px-3 text-right">Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($allStores as $st)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-3 font-bold text-slate-900">
                                    {{ $st->name }}
                                    <div class="text-[10px] text-slate-400 font-mono font-normal">slug: {{ $st->slug }}</div>
                                </td>
                                <td class="py-3 px-3 text-slate-700">
                                    <div class="font-semibold text-slate-900">{{ $st->seller?->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $st->seller?->email }}</div>
                                </td>
                                <td class="py-3 px-3 font-bold text-slate-800">
                                    {{ $st->products->count() }} produk
                                </td>
                                <td class="py-3 px-3">
                                    @if($st->status === 'active')
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Aktif</span>
                                    @elseif($st->status === 'suspended')
                                        <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Ditangguhkan</span>
                                    @else
                                        <span class="bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold px-2 py-0.5 rounded-full">Dihapus</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="{{ route('admin.stores.toggle', $st) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 {{ $st->status === 'active' ? 'bg-amber-100 hover:bg-amber-200 text-amber-800' : 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800' }} rounded-lg font-bold text-[11px] transition-colors">
                                                {{ $st->status === 'active' ? 'Suspend Toko' : 'Buka Blokir' }}
                                            </button>
                                        </form>

                                        @if($st->seller && $st->seller->isSeller())
                                        <form action="{{ route('admin.users.revoke-seller', $st->seller) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Copot status Seller dari \'{{ $st->seller->name }}\'?');">
                                            @csrf
                                            <button type="submit"
                                                    class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-[11px] transition-colors shadow-xs">
                                                Copot Seller
                                            </button>
                                        </form>
                                        @endif

                                        <form action="{{ route('admin.stores.delete', $st) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Hapus toko \'{{ $st->name }}\' beserta seluruh produknya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg font-bold text-[11px] transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pt-2">
                    {{ $allStores->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL INTERAKTIF: UBAH PERAN (ROLE) PENGGUNA -->
<!-- ========================================================================= -->
<div id="roleModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 relative animate-up">
        
        <!-- Close Button -->
        <button type="button" onclick="closeRoleModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-sm">
            ✕
        </button>

        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-[#0050ae] flex items-center justify-center text-3xl mx-auto mb-3 shadow-xs">
                🏷️
            </div>
            <h3 class="text-xl font-black text-slate-900">Ubah Peran (Role) Pengguna</h3>
            <p class="text-xs text-slate-500 mt-1">
                Pilih peran baru untuk <strong id="modalUserName" class="text-slate-900">-</strong> (<span id="modalUserEmail" class="font-mono text-slate-600">-</span>)
            </p>
        </div>

        <!-- Role Selection Form -->
        <form id="roleChangeForm" method="POST" action="" class="space-y-4">
            @csrf

            <div class="space-y-3">
                <!-- Option 1: ADMIN -->
                <div class="relative">
                    <input type="radio" name="role_name" value="admin" id="role_admin" class="sr-only role-card-radio">
                    <label for="role_admin" class="flex items-start gap-3 p-4 rounded-2xl border-2 border-slate-200 hover:border-amber-400 cursor-pointer transition-all">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl shrink-0 font-bold">
                            🛡️
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-slate-900 text-sm">Admin Platform</span>
                                <span class="text-[10px] bg-amber-100 text-amber-900 font-bold px-2 py-0.5 rounded-full">Akses Penuh</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                Hak akses tertinggi untuk moderasi marketplace, kelola role pengguna, verifikasi seller, dan pengaturan sistem.
                            </p>
                        </div>
                    </label>
                </div>

                <!-- Option 2: SELLER -->
                <div class="relative">
                    <input type="radio" name="role_name" value="seller" id="role_seller" class="sr-only role-card-radio">
                    <label for="role_seller" class="flex items-start gap-3 p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-400 cursor-pointer transition-all">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-[#0050ae] flex items-center justify-center text-xl shrink-0 font-bold">
                            🏬
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-slate-900 text-sm">Seller (Penjual Terverifikasi)</span>
                                <span class="text-[10px] bg-blue-100 text-[#0050ae] font-bold px-2 py-0.5 rounded-full">Buka Toko</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                Memberikan hak untuk membuka toko resmi, mengunggah dan menjual produk/voucher game di marketplace.
                            </p>
                        </div>
                    </label>
                </div>

                <!-- Option 3: BUYER -->
                <div class="relative">
                    <input type="radio" name="role_name" value="buyer" id="role_buyer" class="sr-only role-card-radio">
                    <label for="role_buyer" class="flex items-start gap-3 p-4 rounded-2xl border-2 border-slate-200 hover:border-slate-400 cursor-pointer transition-all">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl shrink-0 font-bold">
                            👤
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-slate-900 text-sm">Buyer (Pembeli / Member Reguler)</span>
                                <span class="text-[10px] bg-slate-100 text-slate-700 font-bold px-2 py-0.5 rounded-full">Standar</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                Hak akses member standar untuk browsing katalog, belanja produk, dan transaksi pesanan.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 pt-3">
                <button type="button" onclick="closeRoleModal()" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-[#0050ae] to-indigo-700 hover:from-[#003d87] hover:to-indigo-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-all hover:scale-105 active:scale-95 cursor-pointer flex items-center justify-center gap-1.5">
                    <span>💾</span>
                    <span>Simpan Perubahan Role</span>
                </button>
            </div>
        </form>

    </div>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT: TAB SWITCHING, SEARCH & MODAL HANDLER -->
<!-- ========================================================================= -->
<script>
function switchTab(tabId) {
    // Hide all tab contents
    document.querySelectorAll('.admin-tab-content').forEach(function(content) {
        content.classList.add('hidden');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.admin-tab-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });

    // Show target tab content
    var target = document.getElementById(tabId);
    if (target) {
        target.classList.remove('hidden');
    }

    // Set active button
    var activeBtn = document.getElementById('btn-' + tabId);
    if (activeBtn) {
        activeBtn.classList.add('active');
    }

    // Store tab in localStorage or hash for persistence
    if (history.replaceState) {
        history.replaceState(null, null, '#' + tabId);
    }
}

// Auto open tab from hash on load
document.addEventListener('DOMContentLoaded', function() {
    var hash = window.location.hash.replace('#', '');
    if (hash && document.getElementById(hash)) {
        switchTab(hash);
    }
});

// Live Search in Users Table
function filterUsersTable() {
    var query = document.getElementById('userSearchInput').value.toLowerCase();
    var roleFilter = document.getElementById('userRoleFilter').value;
    var rows = document.querySelectorAll('#usersTable .user-row');

    rows.forEach(function(row) {
        var name = row.getAttribute('data-name') || '';
        var email = row.getAttribute('data-email') || '';
        var role = row.getAttribute('data-role') || '';

        var matchesQuery = name.includes(query) || email.includes(query) || role.toLowerCase().includes(query);
        var matchesRole = !roleFilter || role === roleFilter;

        if (matchesQuery && matchesRole) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Live Search in Products Table
function filterProductsTable() {
    var query = document.getElementById('productSearchInput').value.toLowerCase();
    var rows = document.querySelectorAll('#productsTable .product-row');

    rows.forEach(function(row) {
        var name = row.getAttribute('data-name') || '';
        var seller = row.getAttribute('data-seller') || '';
        var store = row.getAttribute('data-store') || '';

        if (name.includes(query) || seller.includes(query) || store.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Role Change Modal
function openRoleModal(userId, userName, userEmail, currentRole, isCurrentAdmin) {
    document.getElementById('modalUserName').textContent = userName;
    document.getElementById('modalUserEmail').textContent = userEmail;
    
    // Set form action URL
    var form = document.getElementById('roleChangeForm');
    form.action = '/admin/users/' + userId + '/role';

    // Select current role radio
    var radioId = 'role_' + currentRole.toLowerCase();
    var radio = document.getElementById(radioId);
    if (radio) {
        radio.checked = true;
    }

    var modal = document.getElementById('roleModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRoleModal() {
    var modal = document.getElementById('roleModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close on backdrop click
document.getElementById('roleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRoleModal();
    }
});
</script>

@endsection
