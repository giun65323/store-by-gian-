<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - gian games</title>
    <meta name="description" content="Buat akun gian games gratis dan mulai transaksi produk game digital Anda.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-brand { font-family: 'Outfit', sans-serif; }
        .input-field {
            width: 100%;
            border: 1px solid #d1d5db;
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
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.18);
        }
        .btn-brand {
            background: linear-gradient(to right, #7c3aed, #581c87);
            color: white;
            font-weight: 700;
            border-radius: 12px;
            width: 100%;
            padding: 15px;
            font-size: 15px;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s, transform 0.1s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
        }
        .btn-brand:hover {
            opacity: 0.95;
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.45);
        }
        .btn-brand:active { transform: scale(0.99); }
        .divider-line { border: none; border-top: 1px solid #e5e7eb; }
        .animate-up { animation: slideUp 0.5s ease both; }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .feature-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 14px;
            padding: 8px 14px;
            color: white;
            font-size: 13px;
            font-weight: 500;
        }
        /* ===== Hero Text Animations ===== */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes chipPop {
            0%   { opacity: 0; transform: scale(0.8) translateY(10px); }
            70%  { transform: scale(1.04) translateY(-2px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes shimmer {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.7; }
        }
        .anim-title {
            animation: fadeSlideUp 0.75s cubic-bezier(.22,.68,0,1.2) both;
        }
        .anim-title-line1 { animation-delay: 0.1s; }
        .anim-title-line2 { animation-delay: 0.25s; }
        .anim-title-accent {
            animation: fadeSlideUp 0.75s cubic-bezier(.22,.68,0,1.2) 0.4s both,
                       shimmer 3s ease-in-out 1.2s infinite;
        }
        .anim-chip {
            animation: chipPop 0.5s cubic-bezier(.22,.68,0,1.2) both;
            transition: background 0.2s, transform 0.15s;
        }
        .anim-chip:hover { background: rgba(255,255,255,0.22); transform: translateY(-2px) scale(1.02); }
        .anim-chip:nth-child(1) { animation-delay: 0.55s; }
        .anim-chip:nth-child(2) { animation-delay: 0.68s; }
        .anim-chip:nth-child(3) { animation-delay: 0.81s; }
        .anim-chip:nth-child(4) { animation-delay: 0.94s; }
    </style>
</head>
<body class="min-h-screen bg-white text-gray-900 selection:bg-purple-200">

<div class="flex min-h-screen">

    <!-- ===== LEFT: Register Form ===== -->
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 lg:px-14 order-1 lg:order-none">
        <div class="w-full max-w-sm animate-up">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="text-xl font-black text-gray-900 font-brand">gian games</span>
                    <span class="w-2 h-2 rounded-full bg-[#ffb800]"></span>
                </a>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-1">Buat akun baru</h1>
            <p class="text-gray-500 text-sm mb-8">Daftar akun gian games dan mulai transaksi</p>

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3.5 text-rose-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           placeholder="Nama lengkap Anda" class="input-field">
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="email@example.com" class="input-field">
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                           placeholder="Minimal 8 karakter" class="input-field">
                </div>

                <div>
                    <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           placeholder="Ulangi password Anda" class="input-field">
                </div>

                <p class="text-[12px] text-gray-500 leading-relaxed pt-1">
                    Dengan mendaftar, Anda menyetujui <a href="{{ route('help.index') }}" class="font-medium text-[#7c3aed] hover:underline">Ketentuan Layanan</a> dan <a href="{{ route('help.index') }}" class="font-medium text-[#7c3aed] hover:underline">Kebijakan Privasi</a> kami.
                </p>

                <button type="submit" class="btn-brand">Daftar Sekarang</button>
            </form>

            <div class="my-6 flex items-center gap-3">
                <hr class="divider-line flex-1">
                <span class="text-xs text-gray-400 font-medium">atau</span>
                <hr class="divider-line flex-1">
            </div>

            <p class="text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-[#7c3aed] hover:text-[#581c87] hover:underline ml-1">Masuk Sekarang</a>
            </p>

        </div>
    </div>

    <!-- ===== RIGHT: Hero Panel (Royal Deep Purple Theme) ===== -->
    <div class="hidden lg:flex lg:w-[48%] relative overflow-hidden">
        <!-- Deep Purple Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#12072b] via-[#3b1366] to-[#4c1d95]"></div>
        
        <!-- Ambient subtle illumination -->
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-purple-400/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-violet-900/35 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <!-- Brand -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-2xl font-black text-white tracking-tight font-brand">gian games</span>
                <span class="w-2.5 h-2.5 rounded-full bg-[#ffb800]"></span>
            </a>

            <!-- Feature list -->
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-purple-200 text-xs font-bold px-4 py-2 rounded-full">
                    <span>🎮</span>
                    <span>Platform Jual Beli Game &amp; Produk Digital</span>
                </div>

                <h2 class="text-3xl xl:text-4xl font-extrabold text-white leading-snug font-brand">
                    <span class="block anim-title anim-title-line1">Satu platform untuk</span>
                    <span class="block anim-title anim-title-line2">semua transaksi</span>
                    <span class="block anim-title-accent text-amber-300">produk game resmi</span>
                </h2>

                <div class="space-y-3">
                    <div class="feature-chip anim-chip">
                        <svg class="h-4 w-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Top Up Robux, MLBB, dan Free Fire</span>
                    </div>
                    <div class="feature-chip anim-chip">
                        <svg class="h-4 w-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Pembayaran QRIS, E-Wallet, dan Bank Transfer</span>
                    </div>
                    <div class="feature-chip anim-chip">
                        <svg class="h-4 w-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Buka Lapak Toko &amp; Kelola Dagangan Sendiri</span>
                    </div>
                    <div class="feature-chip anim-chip">
                        <svg class="h-4 w-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Obrolan Live Chat Transaksi Real-Time</span>
                    </div>
                </div>
            </div>

            <!-- Authentic Trust Highlight (Real Information, no fake numbers) -->
            <div class="bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-600/60 flex items-center justify-center text-white font-black text-lg">
                        ⚡
                    </div>
                    <div>
                        <p class="text-white text-xs font-bold">Transaksi Cepat &amp; Terverifikasi</p>
                        <p class="text-purple-200/70 text-[11px]">Dukungan pembayaran QRIS, Transfer Bank, dan dompet digital</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
