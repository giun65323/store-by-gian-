<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Reset Password — gian games</title>
    <meta name="description" content="Masukkan kode OTP yang dikirim ke Gmail Anda untuk mengganti password.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-otp {
            width: 100%;
            border: 1.5px solid #d1d5db;
            border-radius: 14px;
            padding: 16px;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 10px;
            text-align: center;
            color: #111827;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .input-otp:focus {
            border-color: #f43f5e;
            box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.15);
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Verifikasi OTP</h2>
            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">
                Kode verifikasi 6-digit telah dikirimkan ke Gmail:
                <br>
                <span class="font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded mt-1 inline-block">{{ $maskedEmail }}</span>
            </p>
        </div>

        <!-- Success Flash -->
        @if (session('success'))
            <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <svg class="h-4 w-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

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

        <form method="POST" action="{{ route('password.verify.otp') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-center text-[13px] font-semibold text-gray-700 mb-2">Masukkan 6-Digit Kode OTP</label>
                <input type="text" name="otp" maxLength="6" required autofocus autocomplete="off"
                       placeholder="••••••" class="input-otp">
                <p class="text-[11px] text-gray-400 text-center mt-2">
                    ⏱️ Kode OTP berlaku selama 10 menit
                </p>
            </div>

            <button type="submit" class="specular-btn specular-btn--full btn-brand">
                Verifikasi OTP & Lanjutkan
            </button>
        </form>

        <!-- Resend OTP -->
        <div class="mt-6 pt-5 border-t border-gray-100 text-center space-y-2">
            <p class="text-xs text-gray-500">Tidak menerima kode OTP di Gmail Anda?</p>
            <form method="POST" action="{{ route('password.resend') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-600 hover:underline">
                    Kirim Ulang Kode OTP
                </button>
            </form>
            <div class="pt-2">
                <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-gray-600">
                    Ganti Alamat Email
                </a>
            </div>
        </div>
    </div>

</body>
</html>
