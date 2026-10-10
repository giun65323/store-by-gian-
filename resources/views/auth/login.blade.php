<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — gian games</title>
    <meta name="description" content="Masuk ke akun gian games Anda untuk mengelola produk.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field {
            width: 100%;
            border: 1px solid #d8b4fe;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .input-field:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }
        .btn-brand {
            background: linear-gradient(to right, #7c3aed, #6d28d9);
            color: white;
            font-weight: 700;
            border-radius: 12px;
            width: 100%;
            padding: 15px;
            font-size: 15px;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s, transform 0.1s;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
        }
        .btn-brand:hover { opacity: 0.93; }
        .btn-brand:active { transform: scale(0.99); }
        .divider-line {
            border: none;
            border-top: 1px solid #ede8f5;
        }
        .animate-up { animation: slideUp 0.5s ease both; }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    </style>
    <link rel="stylesheet" href="{{ asset('css/text-type.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fold-text.css') }}">
</head>
<body class="min-h-screen bg-white text-gray-900 selection:bg-purple-200">

<div class="flex min-h-screen">

    <!-- ===== LEFT: Hero Panel ===== -->
    <div class="hidden lg:flex lg:w-[52%] relative overflow-hidden">
        <!-- Background: Royal deep purple -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#12072b] via-[#3b1366] to-[#4c1d95]"></div>
        <!-- Ambient violet orb -->
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-violet-900/40 rounded-full blur-3xl"></div>

        <!-- Content overlay -->
        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl font-black text-white tracking-tight font-brand">gian games</span>
                <span class="w-2 h-2 rounded-full bg-[#ffb800]"></span>
            </a>

            <!-- Center Text -->
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-purple-200 text-xs font-bold px-4 py-2 rounded-full">
                    <span>🎮</span>
                    <span>Platform Jual Beli Game &amp; Produk Digital</span>
                </div>
                <h1 class="text-4xl xl:text-5xl font-bold text-white leading-tight font-brand">
                    <span id="fold-hero-line1" class="block"></span>
                    <span id="fold-hero-line2" class="block"></span>
                    <span id="typing-heading" class="text-amber-300">Mudah &amp; Cepat</span>
                </h1>
                <p class="text-purple-100/80 text-base leading-relaxed max-w-sm">
                    Jual beli voucher game, Robux instan, akun digital, dan item game dengan aman dan otomatis 24 jam.
                </p>
                <!-- Real platform features -->
                <div class="flex gap-6 pt-2">
                    <div>
                        <div class="text-2xl font-black text-amber-300">24 Jam</div>
                        <div class="text-purple-200/70 text-xs mt-0.5">Sistem Otomatis</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-white">100%</div>
                        <div class="text-purple-200/70 text-xs mt-0.5">Garansi Saldo</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-purple-200">Resmi</div>
                        <div class="text-purple-200/70 text-xs mt-0.5">Katalog Game</div>
                    </div>
                </div>
            </div>

            <!-- Product Showcase Highlight -->
            <div class="bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-600/60 flex items-center justify-center text-white font-black text-lg">
                        ⚡
                    </div>
                    <div>
                        <p class="text-white text-xs font-bold">Transaksi Cepat &amp; Terpercaya</p>
                        <p class="text-purple-200/70 text-[11px]">Dukungan pembayaran QRIS, Transfer Bank, dan E-Wallet</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RIGHT: Login Form ===== -->
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 lg:px-14">
        <div class="w-full max-w-sm animate-up">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <span class="text-xl font-black text-gray-900 font-brand">gian games</span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#ffb800]"></span>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-1">Selamat datang kembali</h2>
            <p class="text-gray-500 text-sm mb-8">Masuk untuk mengelola produk dan transaksi Anda</p>

            @if (session('success'))
                <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3.5 text-emerald-800 text-sm font-semibold flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-5 rounded-xl bg-purple-50 border border-purple-200 px-4 py-3.5 text-purple-800 text-sm font-semibold flex items-start gap-2.5">
                    <svg class="h-5 w-5 text-purple-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3.5 text-rose-700 text-sm space-y-2">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                    <div class="pt-2 border-t border-rose-200/80 flex items-center justify-between gap-2">
                        <span class="text-xs text-rose-600">Password salah?</span>
                        <a href="{{ route('password.request', ['email' => old('email')]) }}" class="text-xs font-bold text-purple-700 hover:text-purple-900 underline flex items-center gap-1">
                            <span>Ganti Password via Email</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="email@example.com" class="input-field">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-[13px] font-semibold text-gray-700">Password</label>
                        <a href="{{ route('password.request', ['email' => old('email')]) }}" class="text-xs font-semibold text-[#7c3aed] hover:text-[#6d28d9] hover:underline">
                            Lupa Password?
                        </a>
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="login_password" required
                               placeholder="Masukkan password" class="input-field pr-10">
                        <button type="button" onclick="toggleLoginPassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                            👁️
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded accent-purple-600">
                    <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">Ingat saya</label>
                </div>

                <button type="submit" class="specular-btn specular-btn--full btn-brand mt-2">Masuk ke Akun</button>
            </form>

            <div class="my-6 flex items-center gap-3">
                <hr class="divider-line flex-1">
                <span class="text-xs text-gray-400 font-medium">atau</span>
                <hr class="divider-line flex-1">
            </div>

            <p class="text-center text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-[#7c3aed] hover:text-[#6d28d9] hover:underline ml-1">Daftar Sekarang</a>
            </p>

        </div>
    </div>

        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="{{ asset('js/fold-text.js') }}"></script>
<script src="{{ asset('js/text-type.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ── FoldText: hero headline lines ── */
        const heroLine1 = document.getElementById('fold-hero-line1');
        if (heroLine1) {
            new FoldText(heroLine1, {
                text:          'Kelola Produk',
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
        }

        const heroLine2 = document.getElementById('fold-hero-line2');
        if (heroLine2) {
            new FoldText(heroLine2, {
                text:          'Anda dengan',
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
        }

        /* ── TextType: animated third line ── */
        const typingTarget = document.getElementById('typing-heading');
        if (typingTarget) {
            new TextType(typingTarget, {
                text: ['Mudah & Cepat', 'Aman & Teratur', 'Efisien & Praktis'],
                typingSpeed: 75,
                pauseDuration: 1500,
                showCursor: true,
                cursorCharacter: '|',
                textColors: ['#fecdd3', '#fbcfe8', '#ffe4e6'],
            });
        }
    });

    function toggleLoginPassword(btn) {
        const input = document.getElementById('login_password');
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
        }
    }
</script>
</body>
</html>
