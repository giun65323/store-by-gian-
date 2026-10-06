<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru — gian games</title>
    <meta name="description" content="Buat kata sandi baru untuk akun gian games Anda.">
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
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Buat Password Baru</h2>
            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">
                OTP berhasil diverifikasi untuk akun:
                <br>
                <strong class="text-gray-800">{{ $sessionData['email'] ?? '' }}</strong>
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

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="new_password" required autofocus
                           placeholder="Minimal 8 karakter" class="input-field pr-10">
                    <button type="button" onclick="togglePassword('new_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                        👁️
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-[13px] font-semibold text-gray-700 mb-1.5">Ulangi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           placeholder="Ketik ulang password baru" class="input-field pr-10">
                    <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" class="specular-btn specular-btn--full btn-brand mt-4">
                Simpan Password Baru & Masuk
            </button>
        </form>
    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
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
