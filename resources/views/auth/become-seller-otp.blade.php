@extends('layouts.app')

@section('title', 'Verifikasi OTP Pendaftaran Seller — gian games')

@section('head')
<style>
    .input-otp {
        width: 100%;
        border: 2px solid #cbd5e1;
        border-radius: 16px;
        padding: 16px;
        font-size: 28px;
        font-weight: 800;
        letter-spacing: 10px;
        text-align: center;
        color: #0f172a;
        outline: none;
        transition: all 0.2s;
        background: #f8fafd;
    }
    .input-otp:focus {
        border-color: #0050ae;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 80, 174, 0.15);
    }
    .animate-up { animation: slideUp 0.5s ease both; }
    @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
</style>
@endsection

@section('content')
<section class="py-16 min-h-[80vh] flex items-center justify-center bg-gradient-to-br from-[#051e46] via-[#0050ae] to-indigo-700 relative overflow-hidden px-4">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-indigo-900/40 rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md animate-up bg-white rounded-3xl p-8 sm:p-10 shadow-2xl relative z-10 border border-gray-100">
        
        <!-- Header Badge -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-[#0050ae] flex items-center justify-center text-3xl mx-auto mb-4 shadow-sm border border-blue-100">
                📩
            </div>
            <h2 class="text-2xl font-black text-gray-900">Verifikasi OTP Seller</h2>
            <p class="text-gray-500 text-xs mt-2 leading-relaxed">
                Kami telah mengirimkan kode OTP 6-digit ke alamat email Anda:
            </p>
            <div class="inline-block bg-blue-50 border border-blue-100 text-[#0050ae] font-mono font-bold text-xs px-3 py-1 rounded-full mt-2">
                {{ auth()->user()->email }}
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-200 px-4 py-3 text-rose-700 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <div class="flex items-start gap-2">
                        <svg class="h-4 w-4 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Form Submit OTP -->
        <form method="POST" action="{{ route('become-seller.verify-otp.submit') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-center text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Masukkan 6-Digit Kode OTP
                </label>
                <input type="text"
                       name="otp"
                       maxlength="6"
                       required
                       autofocus
                       autocomplete="off"
                       placeholder="••••••"
                       class="input-otp">
                <p class="text-center text-[11px] text-gray-400 mt-2">
                    ⏱️ Kode berlaku selama <strong>10 menit</strong>
                </p>
            </div>

            <button type="submit"
                    class="w-full py-3.5 bg-gradient-to-r from-[#0050ae] to-indigo-700 hover:from-[#003d87] hover:to-indigo-800 text-white font-extrabold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                <span>🔐</span>
                <span>Verifikasi & Kirim Pendaftaran</span>
            </button>
        </form>

        <!-- Resend OTP Section -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center space-y-3">
            <p class="text-xs text-gray-500">
                Tidak menerima email kode verifikasi?
            </p>
            <form method="POST" action="{{ route('become-seller.resend-otp') }}">
                @csrf
                <button type="submit"
                        class="text-xs font-bold text-[#0050ae] hover:underline cursor-pointer flex items-center justify-center gap-1 mx-auto">
                    <span>🔄</span>
                    <span>Kirim Ulang Kode OTP</span>
                </button>
            </form>
            
            <div class="pt-2">
                <a href="{{ route('become-seller') }}" class="text-[11px] text-gray-400 hover:text-gray-600 hover:underline">
                    ← Ubah Data / Foto Pendaftaran
                </a>
            </div>
        </div>

    </div>
</section>
@endsection
