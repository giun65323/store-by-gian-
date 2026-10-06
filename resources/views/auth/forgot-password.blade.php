<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — gian games</title>
    <meta name="description" content="Atur ulang kata sandi akun gian games Anda dengan kode verifikasi OTP.">
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
        .animate-up { animation: slideUp 0.5s ease both; }
        @keyframes slideUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-8 selection:bg-rose-100">

    <div class="w-full max-w-md animate-up bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-10 shadow-xl">
        <!-- Logo -->
        <div class="flex items-center justify-center gap-2 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-rose-500" viewBox="0 0 32 32" fill="currentColor">
                <path d="M16 1c-2.3 5.1-9 8.5-9 14a9 9 0 0018 0c0-5.5-6.7-8.9-9-14zm0 20.5a4.5 4.5 0 110-9 4.5 4.5 0 010 9z"/>
            </svg>
            <span class="text-xl font-bold text-gray-900">gian games</span>
        </div>

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-rose-50 text-rose-500 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Lupa Password?</h2>
            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">
                Masukkan email yang terdaftar. Kami akan mengirimkan <strong>6-digit kode OTP ke Gmail</strong> Anda untuk mengganti password baru.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3.5 text-rose-700 text-xs sm:text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <div class="flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Alamat Email Terdaftar</label>
                <input type="email" name="email" value="{{ old('email', $prefilledEmail ?? '') }}" required autofocus
                       placeholder="email@example.com" class="input-field">
            </div>

            <button type="submit" class="specular-btn specular-btn--full btn-brand mt-2 flex items-center justify-center gap-2">
                <span>Kirim Kode OTP ke Email</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-gray-100 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Halaman Masuk
            </a>
        </div>
    </div>

</body>
</html>
