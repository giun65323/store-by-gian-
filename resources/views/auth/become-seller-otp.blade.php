@extends('layouts.app')

@section('title', 'Verifikasi OTP Pendaftaran Seller — gian games')

@section('head')
<link rel="stylesheet" href="{{ asset('css/code-slots.css') }}">
<style>
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
        <form method="POST" action="{{ route('become-seller.verify-otp.submit') }}" id="otp-form" class="space-y-6">
            @csrf

            <div>
                <label class="block text-center text-xs font-bold text-gray-700 uppercase tracking-wider mb-4">
                    Masukkan 6-Digit Kode OTP
                </label>

                {{-- CodeSlots animated container --}}
                <div class="flex justify-center">
                    <div
                        id="cs-root"
                        data-code-slots
                        data-length="6"
                        data-input-name="otp"
                        data-slot-color="#eff6ff"
                        data-accent-color="#0050ae"
                        data-ink-color="#0050ae"
                        data-digit-color="#ffffff"
                        data-danger-color="#ff3b30"
                        data-slot-size="52"
                        data-gap="10"
                        data-radius="14"
                        data-auto-focus="true"
                        data-aria-label="Kode OTP Pendaftaran Seller"
                    ></div>
                </div>

                <p class="text-center text-[11px] text-gray-400 mt-3">
                    ⏱️ Kode berlaku selama <strong>10 menit</strong>
                </p>
            </div>

            <button type="submit"
                    id="submit-btn"
                    class="w-full py-3.5 bg-gradient-to-r from-[#0050ae] to-indigo-700 hover:from-[#003d87] hover:to-indigo-800 text-white font-extrabold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all active:scale-[0.99] flex items-center justify-center gap-2 opacity-50 cursor-not-allowed"
                    disabled>
                <span>🔐</span>
                <span>Verifikasi &amp; Kirim Pendaftaran</span>
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

<script src="{{ asset('js/code-slots.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el     = document.getElementById('cs-root');
        const btn    = document.getElementById('submit-btn');
        const hasErr = {{ $errors->any() ? 'true' : 'false' }};

        requestAnimationFrame(function () {
            const cs = el._csInstance;
            if (!cs) return;

            cs.opts.onChange = function (code) {
                if (code.length === 6) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            };

            if (hasErr) {
                setTimeout(function () { cs.setStatus('error'); }, 500);
            }
        });
    });
</script>
@endsection
