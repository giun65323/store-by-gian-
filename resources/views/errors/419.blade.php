<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Sesi Halaman Berakhir | gian games</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#f8fafc] text-gray-900 min-h-screen flex items-center justify-center p-4 selection:bg-blue-100">
    <div class="max-w-md w-full bg-white border border-gray-200 rounded-3xl p-8 shadow-lg text-center space-y-5">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#0050ae] to-blue-500 text-white flex items-center justify-center text-2xl font-black mx-auto shadow-md">
            GG
        </div>

        <div class="space-y-2">
            <span class="inline-block px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-xs font-black">
                419 | SESI BERAKHIR
            </span>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">
                Sesi Halaman Perlu Disegarkan
            </h1>
            <p class="text-xs text-gray-500 leading-relaxed">
                Token keamanan form Anda telah kedaluwarsa karena halaman dibiarkan terlalu lama. Silakan klik tombol di bawah untuk masuk kembali dengan aman.
            </p>
        </div>

        <div class="pt-2">
            <a href="{{ route('login') }}"
               class="w-full inline-flex items-center justify-center gap-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-extrabold text-xs py-3 px-6 rounded-xl shadow-md transition-all active:scale-[0.98]">
                <span>🔄 Segarkan & Masuk Kembali</span>
            </a>
        </div>

        <p class="text-[11px] text-gray-400">
            Mengalihkan otomatis ke halaman login dalam <span id="countdown" class="font-bold text-[#0050ae]">3</span> detik...
        </p>
    </div>

    <script>
        let timeLeft = 3;
        const countEl = document.getElementById('countdown');
        const timer = setInterval(() => {
            timeLeft--;
            if (countEl) countEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = "{{ route('login') }}";
            }
        }, 1000);
    </script>
</body>
</html>
