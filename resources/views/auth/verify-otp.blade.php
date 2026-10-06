<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP — gian games</title>
    <meta name="description" content="Masukkan kode verifikasi OTP Anda untuk mengakses gian games.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-otp {
            width: 100%;
            border: 1px solid #b0b0b0;
            border-radius: 12px;
            padding: 16px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 8px;
            text-align: center;
            color: #222;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        .input-otp:focus {
            border-color: #f43f5e;
            box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.15);
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
<body class="min-h-screen bg-slate-50 flex items-center justify-center px-4 selection:bg-rose-100">

    <div class="w-full max-w-md animate-up bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-10 shadow-xl">
        <!-- Logo -->
        <div class="flex items-center justify-center gap-2 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-rose-500" viewBox="0 0 32 32" fill="currentColor">
                <path d="M16 1c-2.3 5.1-9 8.5-9 14a9 9 0 0018 0c0-5.5-6.7-8.9-9-14zm0 20.5a4.5 4.5 0 110-9 4.5 4.5 0 010 9z"/>
            </svg>
            <span class="text-xl font-bold text-gray-900">gian games</span>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Verifikasi Email Anda</h2>
        <p class="text-gray-500 text-sm text-center mb-6 leading-relaxed">
            Kami baru saja mengirimkan kode OTP 6-digit ke alamat email Anda. Silakan masukkan kode tersebut untuk menyelesaikan pendaftaran.
        </p>

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

        <form method="POST" action="{{ route('verify-otp') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-center text-[13px] font-semibold text-gray-700 mb-3">Kode Verifikasi (OTP)</label>
                <input type="text" name="otp" maxLength="6" required autofocus autocomplete="off"
                       placeholder="••••••" class="input-otp">
            </div>

            <button type="submit" class="specular-btn specular-btn--full btn-brand">Verifikasi Sekarang</button>
        </form>

        <div class="mt-6 text-center">
            <span class="text-xs text-gray-500">Salah memasukkan email? </span>
            <a href="{{ route('register') }}" class="text-xs font-semibold text-rose-500 hover:text-rose-600">Daftar ulang</a>
        </div>
    </div>

</body>
</html>
