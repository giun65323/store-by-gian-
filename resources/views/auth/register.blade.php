<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — gian games</title>
    <meta name="description" content="Buat akun gian games gratis dan mulai kelola produk Anda.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-field {
            width: 100%;
            border: 1px solid #b0b0b0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 14px;
            color: #222;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .input-field:focus {
            border-color: #222;
            box-shadow: 0 0 0 2px rgba(34,34,34,0.12);
        }
        .btn-brand {
            background: linear-gradient(to right, #FF385C, #e31c5f);
            color: white;
            font-weight: 600;
            border-radius: 12px;
            width: 100%;
            padding: 15px;
            font-size: 15px;
            cursor: pointer;
            border: none;
            transition: opacity 0.2s, transform 0.1s;
            box-shadow: 0 4px 14px rgba(255,56,92,0.35);
        }
        .btn-brand:hover { opacity: 0.93; }
        .btn-brand:active { transform: scale(0.99); }
        .divider-line { border: none; border-top: 1px solid #ddd; }
        .animate-up { animation: slideUp 0.5s ease both; }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .feature-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 100px;
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
        @keyframes fadeSlideLeft {
            from { opacity: 0; transform: translateX(-24px); }
            to   { opacity: 1; transform: translateX(0); }
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
        @keyframes countUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
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
        .anim-chip:hover { background: rgba(255,255,255,0.28); transform: translateY(-2px) scale(1.04); }
        .anim-chip:nth-child(1) { animation-delay: 0.55s; }
        .anim-chip:nth-child(2) { animation-delay: 0.68s; }
        .anim-chip:nth-child(3) { animation-delay: 0.81s; }
        .anim-chip:nth-child(4) { animation-delay: 0.94s; }
        .anim-users {
            animation: countUp 0.7s cubic-bezier(.22,.68,0,1.2) 1.1s both;
        }
    </style>
</head>
<body class="min-h-screen bg-white text-gray-900 selection:bg-rose-100">

<div class="flex min-h-screen">

    <!-- ===== LEFT: Register Form ===== -->
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 lg:px-14 order-1 lg:order-none">
        <div class="w-full max-w-sm animate-up">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-rose-500" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M16 1c-2.3 5.1-9 8.5-9 14a9 9 0 0018 0c0-5.5-6.7-8.9-9-14zm0 20.5a4.5 4.5 0 110-9 4.5 4.5 0 010 9z"/>
                </svg>
                <span class="text-xl font-bold text-gray-900">gian games</span>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-1">Buat akun baru</h2>
            <p class="text-gray-500 text-sm mb-8">Gratis selamanya, tidak perlu kartu kredit</p>

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3.5 text-rose-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $error }}
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

                <p class="text-[12px] text-gray-400 leading-relaxed pt-1">
                    Dengan mendaftar, Anda menyetujui <a href="#" class="underline hover:text-gray-700">Ketentuan Layanan</a> dan <a href="#" class="underline hover:text-gray-700">Kebijakan Privasi</a> kami.
                </p>

                <button type="submit" class="specular-btn specular-btn--full btn-brand">Daftar Sekarang</button>
            </form>

            <div class="my-6 flex items-center gap-3">
                <hr class="divider-line flex-1">
                <span class="text-xs text-gray-400 font-medium">atau</span>
                <hr class="divider-line flex-1">
            </div>

            <p class="text-center text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-rose-500 hover:text-rose-600 hover:underline ml-1">Masuk Sekarang →</a>
            </p>

        </div>
    </div>

    <!-- ===== RIGHT: Hero Panel ===== -->
    <div class="hidden lg:flex lg:w-[48%] relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-tl from-rose-700 via-pink-500 to-rose-400"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/4 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-rose-900/30 rounded-full blur-3xl translate-y-1/4"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <!-- Brand -->
            <a href="#" class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M16 1c-2.3 5.1-9 8.5-9 14a9 9 0 0018 0c0-5.5-6.7-8.9-9-14zm0 20.5a4.5 4.5 0 110-9 4.5 4.5 0 010 9z"/>
                </svg>
                <span class="text-2xl font-bold text-white tracking-tight">gian games</span>
            </a>

            <!-- Feature list -->
            <div class="space-y-6">
                <h2 class="text-3xl xl:text-4xl font-bold text-white leading-snug">
                    <span class="block anim-title anim-title-line1">Satu platform untuk</span>
                    <span class="block anim-title anim-title-line2">semua kebutuhan</span>
                    <span class="block anim-title-accent text-rose-200">manajemen produk</span>
                </h2>

                <div class="space-y-3">
                    <div class="feature-chip anim-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Kelola ribuan produk sekaligus
                    </div>
                    <div class="feature-chip anim-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Monitor stok secara real-time
                    </div>
                    <div class="feature-chip anim-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Keamanan data terjamin
                    </div>
                    <div class="feature-chip anim-chip">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Gratis tanpa batas waktu
                    </div>
                </div>
            </div>

            <div class="flex -space-x-2 anim-users">
                @php $avatarColors = ['#e83c6e','#e86b3c','#c83ce8','#3c6be8','#3ce8a4']; @endphp
                @foreach(['A','B','C','D','E'] as $i => $letter)
                <div class="w-9 h-9 rounded-full border-2 border-white flex items-center justify-center text-white text-xs font-bold js-avatar-color"
                     data-color="{{ $avatarColors[$loop->index] }}">
                    {{ $letter }}
                </div>
                @endforeach
                <script>
                document.querySelectorAll('.js-avatar-color').forEach(function(el) {
                    el.style.backgroundColor = el.dataset.color;
                });
                </script>
                <div class="ml-3 flex flex-col justify-center">
                    <div class="text-white text-sm font-semibold">+10.000 pengguna</div>
                    <div class="text-white/60 text-xs">telah bergabung</div>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
