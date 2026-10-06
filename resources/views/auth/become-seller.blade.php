@extends('layouts.app')

@section('title', 'Daftar Jadi Seller — gian games Tokoku')

@section('head')
<style>
    .section-fade { animation: fadeUp 0.6s ease both; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
    .benefit-tab { transition: all 0.2s; border-bottom: 2px solid transparent; }
    .benefit-tab.active { border-color: #0050ae; color: #0050ae; font-weight: 700; }
    .fee-row:nth-child(even) { background: #f8fafd; }
    .upload-zone { border: 2px dashed #cbd5e1; border-radius: 16px; transition: all 0.2s; cursor: pointer; }
    .upload-zone:hover, .upload-zone.drag-over { border-color: #0050ae; background: #eff6ff; }
    .upload-zone.has-file { border-color: #10b981; background: #f0fdf4; border-style: solid; }
    .step-connector { height: 2px; background: linear-gradient(to right, #0050ae, #6366f1); }
    .story-card { transition: transform 0.2s, box-shadow 0.2s; }
    .story-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.10); }
</style>
@endsection

@section('content')

{{-- ======================================================= --}}
{{-- NOTICE BANNER: Pendaftaran Ditutup Sementara            --}}
{{-- ======================================================= --}}
<div class="bg-amber-50 border-b border-amber-200 px-4 py-3 text-center">
    <p class="text-xs text-amber-800 font-semibold flex items-center justify-center gap-2">
        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Pendaftaran Penjual / Seller sedang dibuka — Lengkapi verifikasi KTP & foto wajah untuk mulai berjualan.
    </p>
</div>

{{-- ======================================================= --}}
{{-- HERO SECTION                                            --}}
{{-- ======================================================= --}}
<section class="bg-gradient-to-br from-[#051e46] via-[#0050ae] to-indigo-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-indigo-900/40 rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="section-fade">
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-xs font-semibold text-blue-100 mb-6">
                    🏬 gian games Tokoku — Platform Seller Digital #1
                </div>
                <h1 id="hero-title-container" class="text-3xl sm:text-4xl xl:text-5xl font-extrabold leading-tight mb-4 select-none">
                    <span id="hero-fold-1" class="block">Jualan ke Jutaan Gamers</span>
                    <span id="hero-fold-2" class="block text-[#ffb800]">di Berbagai Negara</span>
                    <span id="hero-fold-3" class="block">Hanya 3 Menit</span>
                </h1>
                <p class="text-blue-100 text-base leading-relaxed mb-8 max-w-lg">
                    Marketplace pilihan pertama di Indonesia untuk jualan koin, akun, item game dan produk digital lainnya. Mulai berjualan sekarang dan raih penghasilan tanpa batas!
                </p>
                <div class="flex flex-wrap gap-3 mb-8">
                    <a href="#daftar-seller" class="inline-flex items-center gap-2 bg-[#ffb800] hover:bg-[#ffa500] text-gray-900 font-extrabold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105 text-sm">
                        🚀 Mulai Jualan Sekarang
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold px-6 py-3 rounded-xl transition-all text-sm">
                        Pelajari Lebih Lanjut ↓
                    </a>
                </div>
                <div class="flex items-center gap-2 text-xs text-blue-200">
                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Tanpa biaya admin untuk <strong class="text-white">10 transaksi pertama</strong>
                </div>
            </div>
            <div class="hidden lg:grid grid-cols-2 gap-4 section-fade">
                @php
                $stats = [
                    ['icon'=>'👥','num'=>'10.000+','label'=>'Penjual Aktif'],
                    ['icon'=>'🎮','num'=>'500K+','label'=>'Produk Terdaftar'],
                    ['icon'=>'⚡','num'=>'< 12 Jam','label'=>'Order Pertama'],
                    ['icon'=>'🌏','num'=>'10+ Negara','label'=>'Jangkauan Pasar'],
                ];
                @endphp
                @foreach($stats as $s)
                <div class="bg-white/10 border border-white/15 rounded-2xl p-5 backdrop-blur-sm">
                    <div class="text-3xl mb-2">{{ $s['icon'] }}</div>
                    <div class="text-2xl font-extrabold text-white">{{ $s['num'] }}</div>
                    <div class="text-xs text-blue-200 mt-0.5">{{ $s['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- KENAPA JUALAN DI TOKOKU                                 --}}
{{-- ======================================================= --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-gray-900">Kenapa jualan di Tokoku?</h2>
            <p class="text-gray-500 text-sm mt-2">Nikmati banyak benefit kalau jualan di Tokoku</p>
        </div>

        <!-- Benefit Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            @php
            $benefits = [
                ['icon'=>'🚀','title'=>'Berjualan Mudah','desc'=>'Proses verifikasi dan moderasi cepat, mulai dalam hitungan menit.'],
                ['icon'=>'⚙️','title'=>'Fitur Handal','desc'=>'Sistem kami memudahkan kamu dalam berjualan setiap hari.'],
                ['icon'=>'💰','title'=>'Biaya Terjangkau','desc'=>'Gratis biaya untuk 10 penjualan pertama, tanpa biaya bulanan.'],
                ['icon'=>'🏦','title'=>'Pencairan Cepat','desc'=>'Tarik dana penjualan dengan mudah ke berbagai bank pilihan.'],
            ];
            @endphp
            @foreach($benefits as $b)
            <div class="bg-[#f8fafd] border border-blue-50 rounded-2xl p-5 hover:border-blue-200 hover:shadow-md transition-all text-center group">
                <div class="text-3xl mb-3 group-hover:scale-110 transition-transform inline-block">{{ $b['icon'] }}</div>
                <h3 class="font-bold text-sm text-gray-900 mb-1.5">{{ $b['title'] }}</h3>
                <p class="text-[11px] text-gray-500 leading-relaxed">{{ $b['desc'] }}</p>
                <button class="mt-3 text-[11px] font-bold text-[#0050ae] hover:underline">Lihat Selengkapnya →</button>
            </div>
            @endforeach
        </div>

        <div class="text-center">
            <a href="#daftar-seller" class="inline-flex items-center gap-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-bold px-8 py-3 rounded-xl shadow transition-all hover:scale-105 text-sm">
                🚀 Mulai Jualan Sekarang
            </a>
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- HOW IT WORKS: 3 LANGKAH                                 --}}
{{-- ======================================================= --}}
<section id="how-it-works" class="py-16 bg-[#f7f9fb]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-extrabold text-gray-900">Daftarnya cepat cuman tiga langkah mudah</h2>
        </div>

        <div class="relative">
            <!-- Connector line -->
            <div class="hidden md:block absolute top-10 left-[16.67%] right-[16.67%] step-connector"></div>

            <div class="grid md:grid-cols-3 gap-8 relative">
                @php
                $steps = [
                    ['num'=>'1','icon'=>'✅','title'=>'Verifikasi Akun','desc'=>'Buat akun gian games dan lakukan verifikasi email dan identitas KTP + foto wajah.'],
                    ['num'=>'2','icon'=>'🏪','title'=>'Isi Informasi Toko','desc'=>'Isi data toko seperti foto profil toko, nama toko, dan jam operasional.'],
                    ['num'=>'3','icon'=>'🎮','title'=>'Buat Dagangan','desc'=>'Kamu sudah bisa langsung jualan dengan buat dagangan kamu ke toko!'],
                ];
                @endphp
                @foreach($steps as $step)
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-full bg-[#0050ae] text-white flex items-center justify-center text-3xl shadow-lg mb-4 relative z-10 border-4 border-white">
                        {{ $step['icon'] }}
                    </div>
                    <div class="text-[10px] font-black text-[#0050ae] uppercase tracking-widest mb-1">Langkah {{ $step['num'] }}</div>
                    <h3 class="font-extrabold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-10 text-center">
            <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 rounded-full px-5 py-2 text-xs text-emerald-700 font-semibold mb-6">
                ⚡ Mayoritas Seller dapat Order Pertama &lt; 12 jam
            </div>
            <div class="block">
                <a href="#daftar-seller" class="inline-flex items-center gap-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-bold px-8 py-3 rounded-xl shadow transition-all hover:scale-105 text-sm">
                    🚀 Mulai Jualan Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- FITUR HANDAL                                            --}}
{{-- ======================================================= --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-gray-900">Fitur Handal</h2>
            <p class="text-gray-500 text-sm mt-2">Fitur yang membantu kamu untuk berjualan lebih mudah</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
            $features = [
                ['icon'=>'📦','title'=>'Buat Dagangan Sekaligus','desc'=>'Butuh upload banyak produk dalam sekali jalan? Bisa!'],
                ['icon'=>'⚡','title'=>'Pengiriman Otomatis','desc'=>'Upload kode voucher/info akun yang mau dijual, sistem akan kirim secara otomatis.'],
                ['icon'=>'🔗','title'=>'Kirim Instan Lewat API','desc'=>'Integrasikan API untuk proses pesanan lebih cepat dan efisien.'],
                ['icon'=>'📣','title'=>'Akses Fitur Promosi','desc'=>'Tingkatkan penjualan dengan iklan tokoku. Saldo hanya terpotong jika terjual.'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="border border-gray-100 rounded-2xl p-5 hover:border-blue-200 hover:shadow-md transition-all bg-[#f8fafd]">
                <div class="text-3xl mb-3">{{ $f['icon'] }}</div>
                <h3 class="font-bold text-sm text-gray-900 mb-2">{{ $f['title'] }}</h3>
                <p class="text-[11px] text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- SKEMA BIAYA                                             --}}
{{-- ======================================================= --}}
<section class="py-16 bg-[#f7f9fb]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-gray-900">Biaya Jual Rendah dan Aman</h2>
            <p class="text-gray-500 text-sm mt-2">Nikmati biaya rendah ketika berjualan dan fokus pada keuntungan</p>
        </div>

        <!-- Highlights -->
        <div class="grid sm:grid-cols-4 gap-4 mb-8">
            @php
            $perks = [
                ['icon'=>'🎁','title'=>'Gratis biaya jual','desc'=>'10 transaksi pertama untuk pengguna baru.'],
                ['icon'=>'🚫','title'=>'Tanpa Biaya Bulanan','desc'=>'Tidak ada biaya tambahan untuk berjualan.'],
                ['icon'=>'💳','title'=>'Keuntungan Langsung','desc'=>'Hasil penjualan otomatis masuk ke saldo kamu.'],
                ['icon'=>'🛡️','title'=>'Aman','desc'=>'Perlindungan dari chargeback dan transaksi palsu.'],
            ];
            @endphp
            @foreach($perks as $p)
            <div class="bg-white border border-blue-50 rounded-2xl p-4 text-center">
                <div class="text-2xl mb-2">{{ $p['icon'] }}</div>
                <h4 class="font-bold text-xs text-gray-900 mb-1">{{ $p['title'] }}</h4>
                <p class="text-[10px] text-gray-500">{{ $p['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- Fee Table -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-5 py-4 bg-[#0050ae] text-white">
                <h3 class="font-bold text-sm">Skema Biaya Penjualan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-bold text-gray-700">Tipe Kategori Produk</th>
                            <th class="text-right px-5 py-3 font-bold text-gray-700">Biaya Berjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $fees = [
                            ['Akun (Non Roblox)', '10%'],
                            ['Akun (Roblox)', '15%'],
                            ['Currency (Non Roblox)', '2%'],
                            ['Currency (Roblox)', '6%'],
                            ['Game Key (Steam, Playstation, etc.)', '4%'],
                            ['Software & Aplikasi', '8%'],
                            ['Item (Non Roblox)', '5%'],
                            ['Item (Roblox)', '12%'],
                            ['Pulsa', '4%'],
                            ['Top Up', '5%'],
                            ['Top Up Login', '5%'],
                            ['Utilitas', '7%'],
                            ['Joki / Boosting', '15%'],
                            ['Voucher (Steam, Others)', '4%'],
                            ['Voucher (PSN, Xbox, Nintendo, Roblox)', '5%'],
                        ];
                        @endphp
                        @foreach($fees as $fee)
                        <tr class="fee-row border-b border-gray-50">
                            <td class="px-5 py-2.5 text-gray-700">{{ $fee[0] }}</td>
                            <td class="px-5 py-2.5 text-right font-extrabold text-[#0050ae]">{{ $fee[1] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 bg-emerald-50 border-t border-emerald-100 text-center">
                <p class="text-[11px] text-emerald-700 font-semibold">🎁 Gratis Biaya Admin 10 Transaksi Pertama untuk Pengguna Baru</p>
            </div>
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- KISAH SUKSES                                            --}}
{{-- ======================================================= --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-gray-900">Kata mereka yang sukses berjualan</h2>
            <p class="text-gray-500 text-sm mt-2">Kumpulan kisah dari para penjual yang sukses berbisnis di usia muda</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6 mb-10">
            @php
            $stories = [
                ['initial'=>'A','name'=>'Alfin Tantowi','store'=>'DIGITAL STORES','color'=>'#e83c6e','quote'=>'Berhenti menjadi pegawai bank, sekarang penghasilannya lebih dari 100jt per bulan!','rating'=>5],
                ['initial'=>'R','name'=>'Rizky Kurniawan','store'=>'REEV STORE','color'=>'#0050ae','quote'=>'Berjualan dapat membantu ekonomi keluarga dan memberikan kebebasan finansial!','rating'=>5],
                ['initial'=>'K','name'=>'Khairul Ikhwan','store'=>'RAZKIGAMING','color'=>'#7c3aed','quote'=>'Berawal coba-coba sampai bisa beli PC dan Motor dari hasil jualan digital!','rating'=>5],
            ];
            @endphp
            @foreach($stories as $s)
            <div class="story-card bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-1 mb-4">
                    @for($i=0;$i<5;$i++)
                    <svg class="w-4 h-4 text-[#ffb800]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-700 leading-relaxed mb-5 italic">"{{ $s['quote'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-extrabold text-base shrink-0" style="background-color: {{ $s['color'] }}">
                        {{ $s['initial'] }}
                    </div>
                    <div>
                        <p class="font-bold text-xs text-gray-900">{{ $s['name'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $s['store'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="#daftar-seller" class="inline-flex items-center gap-2 bg-[#0050ae] hover:bg-[#003d87] text-white font-bold px-8 py-3 rounded-xl shadow transition-all hover:scale-105 text-sm">
                🚀 Mulai Jualan Sekarang
            </a>
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- DUKUNGAN                                                --}}
{{-- ======================================================= --}}
<section class="py-16 bg-[#f7f9fb]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-gray-900">Dukungan dari kami kapanpun dibutuhkan</h2>
            <p class="text-gray-500 text-sm mt-2">Kami berkomitmen untuk membantu lebih dari 10.000 lebih penjual</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
            $supports = [
                ['icon'=>'🎧','title'=>'Customer Service 24/7','desc'=>'Customer support kami siap membantu kapan pun kamu memiliki pertanyaan.'],
                ['icon'=>'📚','title'=>'Pusat Edukasi Penjual','desc'=>'Dapatkan informasi dan tips terbaru yang akan meningkatkan penjualanmu.'],
                ['icon'=>'💬','title'=>'Komunitas Penjual','desc'=>'Bergabung ke channel Discord kami untuk saling berbagi ilmu antar penjual.'],
                ['icon'=>'🎤','title'=>'Online Gathering','desc'=>'Kami menyediakan wadah untuk mendengarkan aspirasi dan berbagi update terkini.'],
            ];
            @endphp
            @foreach($supports as $s)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:shadow-md transition-all">
                <div class="text-3xl mb-3">{{ $s['icon'] }}</div>
                <h3 class="font-bold text-sm text-gray-900 mb-2">{{ $s['title'] }}</h3>
                <p class="text-[11px] text-gray-500 leading-relaxed">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- FAQ                                                     --}}
{{-- ======================================================= --}}
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-extrabold text-gray-900">Pertanyaan seputar Tokoku</h2>
        </div>
        <div class="space-y-3">
            @php
            $faqs = [
                [
                    'q' => 'Bagaimana cara mengirimkan produk ke pembeli?',
                    'a' => 'Setelah mendapatkan pesanan, pilih menu Riwayat Pesanan → Klik Nomor Pesanan. Di dalam nomor pesanan tertera kontak pembeli dan informasi transaksi. Gunakan informasi tersebut untuk melakukan pengiriman, lalu klik Sudah Dikirim.',
                ],
                [
                    'q' => 'Mengapa harus melalui proses verifikasi?',
                    'a' => 'Proses verifikasi dilakukan untuk memastikan bahwa toko kamu telah memenuhi syarat untuk bisa berjualan, terutama kesesuaian data KTP dan identitas asli. Proses ini bisa memakan waktu hingga 24 jam.',
                ],
                [
                    'q' => 'Berapa lama waktu yang diberikan untuk pengiriman produk?',
                    'a' => 'Tergantung kategori: Voucher & Game Key 6 jam, Top Up & Pulsa 12 jam, Akun & Item 2 Hari. Percepat proses pengiriman untuk menjaga performa toko kamu.',
                ],
                [
                    'q' => 'Kapan saldo penjualan bisa dicairkan?',
                    'a' => 'Saldo penjualan dapat langsung dicairkan melalui bank BCA, BRI, dan Mandiri secara gratis. Proses pencairan berlangsung 1-3 hari kerja.',
                ],
            ];
            @endphp
            @foreach($faqs as $i => $faq)
            <details class="group bg-[#f8fafd] border border-gray-100 rounded-2xl overflow-hidden">
                <summary class="flex items-center justify-between px-5 py-4 cursor-pointer font-semibold text-sm text-gray-900 list-none select-none">
                    <span>{{ $faq['q'] }}</span>
                    <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform shrink-0 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="px-5 pb-4 text-xs text-gray-600 leading-relaxed border-t border-gray-100 pt-3">
                    {{ $faq['a'] }}
                </div>
            </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ======================================================= --}}
{{-- FORM DAFTAR SELLER + VERIFIKASI KTP & SELFIE            --}}
{{-- ======================================================= --}}
<section id="daftar-seller" class="py-16 bg-gradient-to-br from-[#051e46] via-[#0050ae] to-indigo-700 relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3 blur-3xl"></div>
    </div>
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <div class="text-center mb-8 text-white">
            <h2 class="text-2xl font-extrabold">Tunggu apa lagi? Ayo jualan sekarang!</h2>
            <p class="text-blue-200 text-sm mt-2">Lengkapi verifikasi identitas Anda untuk mulai berjualan</p>
        </div>

        <!-- Card Form -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

            @if ($errors->any())
                <div class="px-6 pt-5">
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl px-4 py-3.5 text-rose-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('become-seller.submit') }}" enctype="multipart/form-data" class="px-6 py-7 space-y-6">
                @csrf

                <!-- Akun Info -->
                <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-2xl px-4 py-3.5">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-sky-400 to-indigo-500 flex items-center justify-center text-white font-extrabold text-base shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <span class="ml-auto shrink-0 text-[10px] font-bold px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 border border-orange-200">
                        Buyer → Seller
                    </span>
                </div>

                <!-- Upload KTP -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-1.5">
                        📷 Foto KTP / Kartu Identitas
                        <span class="text-rose-500">*</span>
                    </label>
                    <p class="text-[11px] text-gray-500 mb-3">Pastikan foto jelas, tidak buram, dan semua tulisan terbaca. Format: JPG/PNG, maks. 5MB.</p>
                    <div class="upload-zone p-6 text-center relative" id="ktp-zone" onclick="document.getElementById('ktp_photo').click()">
                        <input type="file" id="ktp_photo" name="ktp_photo" accept="image/*" class="hidden" onchange="previewFile(this, 'ktp-zone', 'ktp-preview')">
                        <div id="ktp-placeholder">
                            <div class="text-4xl mb-2">🪪</div>
                            <p class="text-sm font-semibold text-gray-700">Klik untuk upload foto KTP</p>
                            <p class="text-xs text-gray-400 mt-1">atau drag & drop file di sini</p>
                        </div>
                        <div id="ktp-preview" class="hidden">
                            <img src="" alt="KTP Preview" class="max-h-40 mx-auto rounded-xl object-contain">
                            <p class="text-xs text-emerald-600 font-semibold mt-2">✅ Foto KTP terpilih</p>
                        </div>
                    </div>
                    @error('ktp_photo')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Selfie / Ambil via Kamera -->
                <div>
                    <div class="flex items-center justify-between mb-1.5 flex-wrap gap-2">
                        <label class="block text-sm font-bold text-gray-800">
                            🤳 Foto Wajah / Selfie (Wajib Kamera)
                            <span class="text-rose-500">*</span>
                        </label>
                        <button type="button"
                                onclick="openSelfieCameraModal()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs rounded-lg shadow-sm transition-all hover:scale-105 cursor-pointer">
                            <span>📸</span>
                            <span>Buka Kamera Selfie</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-gray-500 mb-3">Foto selfie wajib diambil langsung via kamera sambil memegang KTP asli. Wajah dan data KTP harus terlihat jelas.</p>

                    <!-- Selfie Camera Zone & Preview -->
                    <div class="upload-zone p-6 text-center relative cursor-pointer" id="selfie-zone" onclick="openSelfieCameraModal()">
                        <input type="file" id="selfie_photo" name="selfie_photo" accept="image/*" class="hidden">
                        
                        <div id="selfie-placeholder">
                            <div class="text-4xl mb-2">🤳</div>
                            <p class="text-sm font-semibold text-gray-700">Klik untuk Buka Kamera & Ambil Selfie</p>
                            <p class="text-xs text-gray-400 mt-1 mb-3">Pegang KTP di depan dada/wajah saat kamera terbuka</p>
                            <div class="flex items-center justify-center">
                                <button type="button"
                                        onclick="openSelfieCameraModal(); event.stopPropagation();"
                                        class="px-5 py-2.5 bg-gradient-to-r from-[#0050ae] to-indigo-700 hover:from-[#003d87] hover:to-indigo-800 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer hover:scale-105">
                                    <span>📸</span>
                                    <span>Buka Kamera Selfie</span>
                                </button>
                            </div>
                        </div>

                        <div id="selfie-preview" class="hidden">
                            <img src="" alt="Selfie Preview" class="max-h-48 mx-auto rounded-xl object-contain shadow-sm border border-emerald-200">
                            <div class="flex items-center justify-center gap-3 mt-3">
                                <p class="text-xs text-emerald-600 font-semibold flex items-center gap-1">
                                    <span>✅</span> Foto selfie kamera siap dikirim
                                </p>
                                <button type="button"
                                        onclick="openSelfieCameraModal(); event.stopPropagation();"
                                        class="text-xs text-[#0050ae] font-bold underline hover:text-[#003d87] flex items-center gap-1 cursor-pointer">
                                    <span>📸</span> Foto Ulang Kamera
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('selfie_photo')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Agreement -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 space-y-2">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="agree_terms" value="1" id="agree_terms"
                                class="w-4 h-4 mt-0.5 rounded border-gray-300 text-[#0050ae] focus:ring-[#0050ae] shrink-0"
                                {{ old('agree_terms') ? 'checked' : '' }}>
                        <span class="text-xs text-gray-700 leading-relaxed">
                            Saya menyetujui <strong>Syarat dan Ketentuan Penjual</strong> gian games Tokoku, termasuk kebijakan biaya penjualan, pengiriman produk, dan perlindungan data. Saya memahami bahwa pendaftaran akan diverifikasi dalam 1×24 jam.
                        </span>
                    </label>
                    @error('agree_terms')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-[#0050ae] to-indigo-700 hover:from-[#003d87] hover:to-indigo-800 text-white font-extrabold text-sm rounded-2xl shadow-lg hover:shadow-xl transition-all active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Daftar Sekarang & Kirim Verifikasi
                </button>

                <p class="text-center text-[11px] text-gray-400">
                    🔒 Data identitas Anda dijaga kerahasiaannya sesuai kebijakan privasi kami.
                </p>
            </form>
        </div>

        <p class="text-center text-xs text-blue-200 mt-6">
            <a href="{{ route('dashboard') }}" class="hover:text-white underline">← Kembali ke Dashboard</a>
        </p>
    </div>
</section>

{{-- ======================================================= --}}
{{-- MODAL LIVE CAMERA SELFIE                                --}}
{{-- ======================================================= --}}
<div id="camera-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[200] hidden items-center justify-center p-4 sm:p-6 transition-all duration-300">
    <div class="bg-gray-900 border border-gray-700 text-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
        
        <!-- Modal Header -->
        <div class="px-5 py-4 bg-gray-800/80 border-b border-gray-700/80 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2">
                <span class="text-xl">📸</span>
                <div>
                    <h3 class="text-sm font-extrabold text-white leading-tight">Ambil Foto Selfie & KTP</h3>
                    <p class="text-[10px] text-gray-400">Pegang KTP di dekat wajah dengan jelas</p>
                </div>
            </div>
            <button type="button"
                    onclick="closeSelfieCameraModal()"
                    class="p-1.5 rounded-full hover:bg-gray-700 text-gray-400 hover:text-white transition-colors cursor-pointer"
                    title="Tutup Kamera">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Camera Viewport -->
        <div class="p-4 sm:p-5 flex-1 flex flex-col items-center justify-center overflow-hidden bg-black relative">
            
            <!-- Video Viewfinder (Live Stream) -->
            <div id="camera-viewfinder" class="relative w-full max-w-sm aspect-[4/3] rounded-2xl overflow-hidden bg-gray-950 flex items-center justify-center border border-gray-800 shadow-inner">
                <video id="camera-video" autoplay playsinline muted class="w-full h-full object-cover -scale-x-100"></video>
                
                <!-- Oval Guide Overlay -->
                <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-4">
                    <div class="w-44 h-56 border-2 border-dashed border-white/60 rounded-[50%] shadow-[0_0_0_9999px_rgba(0,0,0,0.35)] flex items-center justify-center">
                        <span class="text-[10px] text-white/80 font-bold bg-black/50 px-2 py-0.5 rounded-full">Wajah & KTP</span>
                    </div>
                </div>

                <!-- Countdown Overlay -->
                <div id="camera-countdown" class="hidden absolute inset-0 bg-black/60 flex items-center justify-center text-6xl font-black text-amber-400 animate-ping">
                    3
                </div>

                <!-- Camera Error Alert -->
                <div id="camera-error" class="hidden absolute inset-0 bg-gray-900 p-6 flex flex-col items-center justify-center text-center space-y-3 z-20">
                    <span class="text-4xl">⚠️</span>
                    <h4 class="text-sm font-bold text-white">Tidak Dapat Mengakses Kamera</h4>
                    <p class="text-xs text-gray-400" id="camera-error-msg">Pastikan izin kamera diaktifkan di browser Anda.</p>
                    <button type="button"
                            onclick="startCameraStream()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-colors">
                        🔄 Coba Lagi
                    </button>
                </div>
            </div>

            <!-- Photo Preview Result (After Capture) -->
            <div id="camera-result-view" class="hidden relative w-full max-w-sm aspect-[4/3] rounded-2xl overflow-hidden bg-gray-950 flex items-center justify-center border-2 border-emerald-500 shadow-lg">
                <canvas id="camera-canvas" class="hidden"></canvas>
                <img id="camera-result-img" src="" alt="Captured Selfie" class="w-full h-full object-cover">
                <span class="absolute top-2 right-2 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                    ✓ Foto Terambil
                </span>
            </div>

            <!-- Device Selector (If multiple cameras) -->
            <div id="camera-controls-bar" class="w-full max-w-sm mt-3 flex items-center justify-between text-xs text-gray-400">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="camera-status-text">Kamera Aktif</span>
                </div>
                <button type="button"
                        id="flip-camera-btn"
                        onclick="toggleCameraFacing()"
                        class="text-[11px] font-semibold text-blue-400 hover:text-blue-300 flex items-center gap-1 hover:underline cursor-pointer">
                    <span>🔄 Ganti Kamera</span>
                </button>
            </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="p-4 bg-gray-800/90 border-t border-gray-700/80 shrink-0">
            <!-- State 1: Before Capture -->
            <div id="action-before-capture" class="flex items-center justify-center gap-3">
                <button type="button"
                        onclick="closeSelfieCameraModal()"
                        class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-200 text-xs font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button type="button"
                        onclick="takeSnapshot(false)"
                        class="flex-1 max-w-xs py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-extrabold text-sm rounded-2xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <span class="text-base">📸</span>
                    <span>Jepret Foto Selfie</span>
                </button>
                <button type="button"
                        onclick="takeSnapshot(true)"
                        class="px-3 py-2.5 bg-gray-700 hover:bg-gray-600 text-amber-300 text-xs font-bold rounded-xl transition-colors"
                        title="Timer 3 Detik">
                    ⏱️ 3s
                </button>
            </div>

            <!-- State 2: After Capture (Review) -->
            <div id="action-after-capture" class="hidden flex items-center justify-between gap-3">
                <button type="button"
                        onclick="retakeSelfie()"
                        class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-200 text-xs font-bold rounded-xl transition-colors flex items-center gap-1.5 cursor-pointer">
                    <span>🔄</span>
                    <span>Foto Ulang</span>
                </button>
                <button type="button"
                        onclick="applyCapturedSelfie()"
                        class="flex-1 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <span>✅</span>
                    <span>Gunakan Foto Ini</span>
                </button>
            </div>
        </div>

    </div>
</div>

<script>
var cameraStream = null;
var currentFacingMode = 'user'; // 'user' (depan) atau 'environment' (belakang)
var capturedBlob = null;

function previewFile(input, zoneId, previewId) {
    var zone = document.getElementById(zoneId);
    var preview = document.getElementById(previewId);
    var placeholder = zone.querySelector('[id$="-placeholder"]');

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
            zone.classList.add('has-file');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ── Camera Modal & Stream Controller ── */
function openSelfieCameraModal() {
    var modal = document.getElementById('camera-modal');
    if (!modal) return;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Reset views
    document.getElementById('camera-viewfinder').classList.remove('hidden');
    document.getElementById('camera-result-view').classList.add('hidden');
    document.getElementById('action-before-capture').classList.remove('hidden');
    document.getElementById('action-after-capture').classList.add('hidden');
    document.getElementById('camera-error').classList.add('hidden');

    startCameraStream();
}

function closeSelfieCameraModal() {
    var modal = document.getElementById('camera-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    stopCameraStream();
}

function startCameraStream() {
    stopCameraStream();
    document.getElementById('camera-error').classList.add('hidden');
    document.getElementById('camera-status-text').textContent = 'Menghubungkan Kamera...';

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showCameraError('Browser Anda tidak mendukung akses kamera langsung. Silakan gunakan opsi upload file.');
        return;
    }

    var constraints = {
        video: {
            facingMode: currentFacingMode,
            width: { ideal: 1280 },
            height: { ideal: 720 }
        },
        audio: false
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(function(stream) {
            cameraStream = stream;
            var video = document.getElementById('camera-video');
            video.srcObject = stream;
            video.play();
            document.getElementById('camera-status-text').textContent = 'Kamera Aktif';
            
            // Adjust mirror effect based on facing mode
            if (currentFacingMode === 'user') {
                video.classList.add('-scale-x-100');
            } else {
                video.classList.remove('-scale-x-100');
            }
        })
        .catch(function(err) {
            console.error('Camera access error:', err);
            var msg = 'Gagal mengakses kamera. Mohon izinkan akses kamera di pengaturan browser Anda.';
            if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                msg = 'Izin kamera ditolak. Silakan izinkan akses kamera pada ikon gembok / izin browser Anda.';
            } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                msg = 'Perangkat kamera tidak ditemukan di komputer/HP Anda.';
            }
            showCameraError(msg);
        });
}

function stopCameraStream() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(function(track) {
            track.stop();
        });
        cameraStream = null;
    }
    var video = document.getElementById('camera-video');
    if (video) {
        video.srcObject = null;
    }
}

function showCameraError(msg) {
    document.getElementById('camera-error-msg').textContent = msg;
    document.getElementById('camera-error').classList.remove('hidden');
    document.getElementById('camera-status-text').textContent = 'Kamera Gagal';
}

function toggleCameraFacing() {
    currentFacingMode = (currentFacingMode === 'user') ? 'environment' : 'user';
    startCameraStream();
}

function takeSnapshot(withTimer) {
    var countdownEl = document.getElementById('camera-countdown');
    if (withTimer) {
        var count = 3;
        countdownEl.textContent = count;
        countdownEl.classList.remove('hidden');
        var timer = setInterval(function() {
            count--;
            if (count > 0) {
                countdownEl.textContent = count;
            } else {
                clearInterval(timer);
                countdownEl.classList.add('hidden');
                captureFromVideo();
            }
        }, 1000);
    } else {
        captureFromVideo();
    }
}

function captureFromVideo() {
    var video = document.getElementById('camera-video');
    var canvas = document.getElementById('camera-canvas');
    var resultImg = document.getElementById('camera-result-img');

    if (!video || !video.videoWidth) return;

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    var ctx = canvas.getContext('2d');

    // Jika kamera depan, cerminkan canvas agar sesuai dengan tampilan video
    if (currentFacingMode === 'user') {
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
    }
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    canvas.toBlob(function(blob) {
        capturedBlob = blob;
        var dataUrl = canvas.toDataURL('image/jpeg', 0.92);
        resultImg.src = dataUrl;

        // Switch to Review View
        document.getElementById('camera-viewfinder').classList.add('hidden');
        document.getElementById('camera-result-view').classList.remove('hidden');
        document.getElementById('action-before-capture').classList.add('hidden');
        document.getElementById('action-after-capture').classList.remove('hidden');

        stopCameraStream();
    }, 'image/jpeg', 0.92);
}

function retakeSelfie() {
    capturedBlob = null;
    document.getElementById('camera-viewfinder').classList.remove('hidden');
    document.getElementById('camera-result-view').classList.add('hidden');
    document.getElementById('action-before-capture').classList.remove('hidden');
    document.getElementById('action-after-capture').classList.add('hidden');

    startCameraStream();
}

function applyCapturedSelfie() {
    if (!capturedBlob) return;

    // Convert blob to File object
    var fileName = 'selfie_ktp_' + Date.now() + '.jpg';
    var file = new File([capturedBlob], fileName, { type: 'image/jpeg' });

    // Assign to file input using DataTransfer
    var input = document.getElementById('selfie_photo');
    if (window.DataTransfer) {
        var dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
    }

    // Update main form preview
    var zone = document.getElementById('selfie-zone');
    var preview = document.getElementById('selfie-preview');
    var placeholder = document.getElementById('selfie-placeholder');
    var resultImg = document.getElementById('camera-result-img');

    preview.querySelector('img').src = resultImg.src;
    preview.classList.remove('hidden');
    if (placeholder) placeholder.classList.add('hidden');
    zone.classList.add('has-file');

    closeSelfieCameraModal();
}

// Drag and drop support
['ktp-zone', 'selfie-zone'].forEach(function(zoneId) {
    var zone = document.getElementById(zoneId);
    var inputId = zoneId === 'ktp-zone' ? 'ktp_photo' : 'selfie_photo';
    var previewId = zoneId === 'ktp-zone' ? 'ktp-preview' : 'selfie-preview';

    zone.addEventListener('dragover', function(e) {
        e.preventDefault();
        zone.classList.add('drag-over');
    });
    zone.addEventListener('dragleave', function() {
        zone.classList.remove('drag-over');
    });
    zone.addEventListener('drop', function(e) {
        e.preventDefault();
        zone.classList.remove('drag-over');
        var files = e.dataTransfer.files;
        if (files.length) {
            var input = document.getElementById(inputId);
            input.files = files;
            previewFile(input, zoneId, previewId);
        }
    });
});

/* ── FoldText 3D Animation for Hero Heading ── */
document.addEventListener('DOMContentLoaded', function () {
    if (window.FoldText) {
        var fold1Target = document.getElementById('hero-fold-1');
        var fold2Target = document.getElementById('hero-fold-2');
        var fold3Target = document.getElementById('hero-fold-3');

        var fold1, fold2, fold3;

        if (fold1Target) {
            fold1Target.textContent = '';
            fold1 = new FoldText(fold1Target, {
                text:          'Jualan ke Jutaan Gamers',
                splitBy:       'char',
                hinge:         'top',
                trigger:       'mount',
                duration:      0.65,
                stagger:       0.035,
                ease:          'power3.out',
                perspective:   800,
                creaseShading: 0.55,
                fontSize:      'inherit',
                fontWeight:    'inherit',
                color:         '#ffffff',
            });
        }

        if (fold2Target) {
            fold2Target.textContent = '';
            fold2 = new FoldText(fold2Target, {
                text:          'di Berbagai Negara',
                splitBy:       'char',
                hinge:         'top',
                trigger:       'mount',
                duration:      0.65,
                stagger:       0.035,
                ease:          'power3.out',
                perspective:   800,
                creaseShading: 0.55,
                fontSize:      'inherit',
                fontWeight:    'inherit',
                color:         '#ffb800',
            });
        }

        if (fold3Target) {
            fold3Target.textContent = '';
            fold3 = new FoldText(fold3Target, {
                text:          'Hanya 3 Menit',
                splitBy:       'char',
                hinge:         'top',
                trigger:       'mount',
                duration:      0.65,
                stagger:       0.035,
                ease:          'power3.out',
                perspective:   800,
                creaseShading: 0.55,
                fontSize:      'inherit',
                fontWeight:    'inherit',
                color:         '#ffffff',
            });
        }

        // Replay animation on hover
        var container = document.getElementById('hero-title-container');
        if (container) {
            container.addEventListener('mouseenter', function () {
                if (fold1) fold1.play(false);
                if (fold2) fold2.play(false);
                if (fold3) fold3.play(false);
            });
        }
    }
});
</script>

@endsection
