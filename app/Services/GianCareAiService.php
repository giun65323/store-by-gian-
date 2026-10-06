<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GianCareAiService
{
    /**
     * Generate response from Gian Care AI.
     */
    public function generateReply(string $message, array $history = []): string
    {
        $message = trim($message);
        if (empty($message)) {
            return "Halo Kak! Ada yang bisa Gian Care bantu hari ini? 🐿️";
        }

        // 1. Check if external Gemini API key is configured
        $geminiKey = env('GEMINI_API_KEY');
        if (!empty($geminiKey)) {
            $geminiReply = $this->callGeminiApi($message, $geminiKey, $history);
            if ($geminiReply) {
                return $geminiReply;
            }
        }

        // 2. Check if OpenAI API key is configured
        $openaiKey = env('OPENAI_API_KEY');
        if (!empty($openaiKey)) {
            $openaiReply = $this->callOpenAiApi($message, $openaiKey, $history);
            if ($openaiReply) {
                return $openaiReply;
            }
        }

        // 3. Built-in Comprehensive Natural Language AI & Knowledge Engine
        return $this->smartLocalAi($message);
    }

    /**
     * Call Google Gemini API (if key is set).
     */
    protected function callGeminiApi(string $message, string $apiKey, array $history = []): ?string
    {
        try {
            $systemInstruction = "Anda adalah Gian Care 🐿️, asisten AI Customer Service resmi platform e-commerce gaming 'gian games' (marketplace mirip itemku untuk top up game, voucher, item, akun, dan joki). Jawablah dengan ramah, sopan, antusias, menggunakan panggilan 'Kak' / 'Kakak'. Berikan instruksi jelas, praktis, dan akurat mengenai transaksi, pembelian, penjualan, garansi 100%, pembayaran (DANA, GoPay, QRIS, Bank), chat pembeli/penjual, dan keamanan akun. Format jawaban dengan rapi menggunakan bold dan bullet list jika perlu.";

            $contents = [];
            foreach ($history as $h) {
                $role = ($h['sender'] ?? '') === 'user' ? 'user' : 'model';
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $h['text'] ?? '']]
                ];
            }
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $message]]
            ];

            $response = Http::timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'system_instruction' => [
                    'parts' => [['text' => $systemInstruction]]
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($reply) {
                    return nl2br(e($reply));
                }
            }
        } catch (\Exception $e) {
            Log::warning('Gemini API call failed, falling back to local AI: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Call OpenAI API (if key is set).
     */
    protected function callOpenAiApi(string $message, string $apiKey, array $history = []): ?string
    {
        try {
            $messages = [
                [
                    'role' => 'system',
                    'content' => "Anda adalah Gian Care 🐿️, asisten AI Customer Service resmi platform 'gian games' (marketplace gaming & voucher). Jawab ramah dan solutif dengan bahasa Indonesia santun ('Kak/Kakak')."
                ]
            ];

            foreach ($history as $h) {
                $role = ($h['sender'] ?? '') === 'user' ? 'user' : 'assistant';
                $messages[] = ['role' => $role, 'content' => $h['text'] ?? ''];
            }
            $messages[] = ['role' => 'user', 'content' => $message];

            $response = Http::timeout(10)->withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 600,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? null;
                if ($reply) {
                    return nl2br(e($reply));
                }
            }
        } catch (\Exception $e) {
            Log::warning('OpenAI API call failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Comprehensive Local AI Assistant Engine for Gian Games.
     */
    protected function smartLocalAi(string $query): string
    {
        $q = mb_strtolower($query, 'UTF-8');

        // 1. GREETINGS & CHIT-CHAT
        if (preg_match('/\b(halo|hallo|hai|hi|hey|hei|pagi|siang|sore|malam|assalamualaikum|oy|tes|test|p\b)/i', $q)) {
            $greetings = [
                "Halo Kak! Senang sekali bisa menyapa Kakak 🐿️✨ Ada kendala transaksi, cara pembelian, atau pertanyaan seputar <strong>gian games</strong> yang bisa Gian Care bantu?",
                "Hai Kak! Gian Care siap melayani Kakak 24/7 nih 🎮 Mau tanya seputar cara belanja, top up game, jualan di toko, atau lacak pesanan?",
                "Halo Kak! Selamat datang di Pusat Bantuan <strong>gian games</strong> 🐿️. Silakan ceritakan apa yang Kakak butuhkan, Gian Care siap bantu sampai tuntas ya!"
            ];
            return $greetings[array_rand($greetings)];
        }

        // 2. WHO ARE YOU / IDENTITY
        if (preg_match('/\b(siapa kamu|kamu siapa|nama kamu|siapa namamu|kamu itu apa|bot|ai)\b/i', $q)) {
            return "Halo! Saya adalah <strong>Gian Care 🐿️</strong>, asisten AI resmi dari platform <strong>gian games</strong>.<br><br>Saya diprogram untuk membantu Kakak menemukan informasi produk, panduan belanja, penyelesaian kendala pesanan, verifikasi akun, hingga tips berjualan aman di gian games. Ada yang bisa saya bantu sekarang, Kak?";
        }

        // 3. CARA PESAN / CARA BELI / CARA ORDER / CHECKOUT
        if (preg_match('/\b(cara (pesan|beli|order|belanja|checkout|membeli)|gimana (beli|pesan)|mau beli|mau order|langkah (beli|order))\b/i', $q)) {
            return "Berikut langkah mudah berbelanja di <strong>gian games</strong> ya Kak 🛒:<br><br>" .
                "1. <strong>Cari Produk:</strong> Temukan produk game, voucher, atau item yang Kakak inginkan di halaman utama atau gunakan kolom pencarian.<br>" .
                "2. <strong>Pilih & Beli:</strong> Klik tombol <strong>Beli Sekarang</strong> pada rincian produk (atau masukkan ke Keranjang Belanja 🛒).<br>" .
                "3. <strong>Pilih Pembayaran:</strong> Pilih metode bayar yang Kakak sukai (DANA, GoPay, QRIS, dll).<br>" .
                "4. <strong>Selesaikan Transaksi:</strong> Lakukan pembayaran sesuai nominal. Untuk pengiriman instan, data akun/voucher akan otomatis terbit di rincian pesanan.<br>" .
                "5. <strong>Konfirmasi:</strong> Setelah menerima item dengan sesuai, berikan ulasan bintang 5 untuk toko penjual ⭐.<br><br>" .
                "👉 <a href='/dashboard' class='text-[#0050ae] font-bold underline'>Jelajahi Produk Sekarang</a>";
        }

        // 4. METODE PEMBAYARAN / CARA BAYAR
        if (preg_match('/\b(bayar|pembayaran|metode bayar|dana|gopay|ovo|qris|transfer|va|bca|bri|mandiri|bni|shopeepay)\b/i', $q)) {
            return "Di <strong>gian games</strong>, Kakak bisa membayar dengan berbagai metode instan & aman 💳:<br><br>" .
                "• <strong>E-Wallet:</strong> DANA, GoPay, OVO, ShopeePay.<br>" .
                "• <strong>QRIS:</strong> Scan langsung dari semua aplikasi m-Banking & dompet digital nasional.<br>" .
                "• <strong>Virtual Account Bank:</strong> BCA, Mandiri, BRI, BNI, Permata.<br><br>" .
                "💡 <em>Tips:</em> Seluruh pembayaran diverifikasi secara otomatis dalam hitungan detik setelah transfer berhasil dilakukan tanpa perlu upload bukti manual!";
        }

        // 5. STATUS PESANAN / LACAK PESANAN / NOMOR ORDER
        if (preg_match('/\b(status|lacak|pesanan saya|cek pesanan|nomor order|od\d+|kapan dikirim|belum dikirim|belum masuk|lama banget)\b/i', $q)) {
            return "Untuk memeriksa perkembangan pesanan Kakak 📦:<br><br>" .
                "1. Buka menu <strong><a href='/orders' class='text-[#0050ae] font-bold underline'>Riwayat Transaksi</a></strong>.<br>" .
                "2. Pilih tab <strong>Pembelian Saya</strong> lalu klik <strong>Lihat Rincian</strong> pada pesanan terkait.<br>" .
                "3. Kakak bisa melihat status pesanan (Pending / Processing / Completed) serta voucher yang dikirimkan penjual.<br>" .
                "4. Jika pesanan belum selesai, Kakak bisa langsung klik tombol <strong>💬 Hubungi Penjual</strong> untuk chat real-time dengan toko!";
        }

        // 6. GARANSI, REFUND & KOMPLAIN
        if (preg_match('/\b(garansi|refund|kembalikan dana|pengembalian dana|komplain|uang kembali|ditipu|penipuan|batal|batalkan)\b/i', $q)) {
            return "Tenang Kak, seluruh transaksi di <strong>gian games</strong> dilindungi <strong>Garansi 100%</strong> 🛡️:<br><br>" .
                "• Uang Kakak aman di sistem escrow kami dan <strong>belum diteruskan ke penjual</strong> sampai pesanan terkonfirmasi selesai.<br>" .
                "• Jika penjual tidak mengirim pesanan dalam batas waktu, atau produk tidak sesuai deskripsi, Kakak bisa mengajukan komplain.<br>" .
                "• Buka <a href='/orders' class='text-[#0050ae] font-bold underline'>Riwayat Transaksi</a> -> Pilih pesanan -> Klik <strong>Ajukan Komplain</strong>.<br>" .
                "• Tim Gian Care akan meninjau dan mengembalikan dana 100% ke saldo/metode pembayaran Kakak!";
        }

        // 7. CARA CHAT DENGAN PENJUAL / SELLER
        if (preg_match('/\b(chat penjual|hubungi penjual|contact seller|bicara dengan penjual|tanya seller|penjual)\b/i', $q)) {
            return "Kakak bisa langsung mengirim pesan ke penjual dengan cara 💬:<br><br>" .
                "1. Masuk ke halaman <a href='/orders' class='text-[#0050ae] font-bold underline'>Riwayat Transaksi</a>.<br>" .
                "2. Klik <strong>Lihat Rincian</strong> pada pesanan yang ingin ditanyakan.<br>" .
                "3. Klik tombol <strong>💬 Hubungi Penjual</strong> (atau gunakan tombol Cepat / Quick Reply).<br>" .
                "4. Penjual akan menerima notifikasi pesan baru dan bisa langsung membalas chat Kakak!";
        }

        // 8. SELLER: CARA BERJUALAN / CARA BUKA TOKO / TAMBAH PRODUK
        if (preg_match('/\b(cara jualan|cara berjualan|buka toko|tambah produk|jual produk|jadi seller|mau jual|jual akun|jual item)\b/i', $q)) {
            return "Mau mulai menghasilkan cuan di <strong>gian games</strong>? Caranya sangat mudah Kak 💼✨:<br><br>" .
                "1. Pastikan Kakak sudah mendaftar dan login ke akun gian games.<br>" .
                "2. Buka menu <strong><a href='/products/create' class='text-[#0050ae] font-bold underline'>Tambah Produk</a></strong>.<br>" .
                "3. Isi nama produk, kategori game (Mobile Legends, Free Fire, Roblox, dll), harga jual, dan stok.<br>" .
                "4. Upload foto produk yang menarik dan tuliskan deskripsi lengkap & jelas.<br>" .
                "5. Klik <strong>Simpan Produk</strong>, produk Kakak langsung tayang dan siap dibeli ribuan gamers!";
        }

        // 9. SELLER: BALAS CHAT PEMBELI & KELOLA PENJUALAN
        if (preg_match('/\b(balas chat|pesanan masuk|penjualan saya|tarik saldo|cairkan saldo|fitur tokoku)\b/i', $q)) {
            return "Bagi Penjual (Seller) di gian games 🏪:<br><br>" .
                "• <strong>Pesanan Masuk:</strong> Cek di <a href='/orders' class='text-[#0050ae] font-bold underline'>Transaksi -> Pesanan Masuk (Penjualan)</a>.<br>" .
                "• <strong>Balas Chat Pembeli:</strong> Klik tombol <strong>💬 Balas Chat</strong> pada pesanan masuk atau cek icon pesan 💬 di navbar atas jika ada badge merah.<br>" .
                "• <strong>Kelola Stok:</strong> Masuk ke menu <a href='/products' class='text-[#0050ae] font-bold underline'>Produk Saya</a> untuk edit harga dan stok secara real-time!";
        }

        // 10. LUPA PASSWORD / OTP / EMAIL GMAIL
        if (preg_match('/\b(lupa password|ganti password|reset password|otp|kode otp|gmail|tidak bisa login|lupa kata sandi)\b/i', $q)) {
            return "Jika Kakak lupa kata sandi akun 🔑:<br><br>" .
                "1. Buka halaman <strong><a href='/forgot-password' class='text-[#0050ae] font-bold underline'>Lupa Password</a></strong>.<br>" .
                "2. Masukkan alamat Gmail yang terdaftar di akun gian games.<br>" .
                "3. Sistem kami akan otomatis mengirimkan <strong>6-digit Kode OTP</strong> ke inbox Gmail Kakak.<br>" .
                "4. Masukkan kode OTP tersebut, lalu buat password baru yang kuat dan aman.<br><br>" .
                "💡 <em>Tips:</em> Cek juga folder <em>Spam / Promosi</em> di Gmail jika kode OTP belum muncul di inbox utama.";
        }

        // 11. KEAMANAN & TIPS ANTI PENIPUAN
        if (preg_match('/\b(aman|keamanan|terpercaya|penipu|legal|resmi|scam|tips aman)\b/i', $q)) {
            return "Transaksi di <strong>gian games 100% Aman & Legal</strong> 🔒🛡️:<br><br>" .
                "• Sistem Escrow (Rekening Bersama) menahan uang sampai pembeli menerima pesanan dengan sukses.<br>" .
                "• <strong>PERINGATAN:</strong> Jangan pernah bertransaksi di luar gian games (misalnya via WA / Discord pribadi).<br>" .
                "• Jangan pernah membagikan kode OTP atau kata sandi kepada siapapun, termasuk yang mengatasnamakan admin gian games!";
        }

        // 12. ATURAN / LEVEL PELANGGARAN
        if (preg_match('/\b(pelanggaran|level 1|level 2|level 3|sanksi|banned|blokir|aturan)\b/i', $q)) {
            return "Berikut ringkasan Sistem Level Pelanggaran di gian games 📜:<br><br>" .
                "• <strong>Level 1 (Peringatan):</strong> Keterlambatan pengiriman pesanan pertama kali atau pembatalan sepihak.<br>" .
                "• <strong>Level 2 (Pembatasan):</strong> Tidak merespon komplain pembeli dalam 1x24 jam. Fitur tambah produk dibekukan 3 hari.<br>" .
                "• <strong>Level 3 (Penonaktifan Akun):</strong> Terbukti melakukan penipuan, data voucher palsu, atau transaksi di luar platform. Akun & toko dibanned permanen.";
        }

        // 13. GAME & VOUCHER POPULER
        if (preg_match('/\b(game|mobile legends|mlbb|free fire|ff|roblox|valorant|steam|genshin|pubg|diamond|robux)\b/i', $q)) {
            return "Di <strong>gian games</strong>, tersedia berbagai kategori game populer 🎮:<br><br>" .
                "• <strong>Mobile Legends:</strong> Diamond, Akun, Joki Rank, Gift Skin.<br>" .
                "• <strong>Free Fire:</strong> Diamond Fast, Akun Old, Bundle.<br>" .
                "• <strong>Roblox:</strong> Robux Instan, Item Blox Fruits, Pet Simulator.<br>" .
                "• <strong>Steam & PC Games:</strong> Steam Wallet IDR, Valorant Points, Game Keys.<br><br>" .
                "👉 Silakan cari produk favorit Kakak di <a href='/dashboard' class='text-[#0050ae] font-bold underline'>Katalog Utama</a>!";
        }

        // 14. THANK YOU / TERIMA KASIH / CLOSING
        if (preg_match('/\b(makasih|terima kasih|thanks|ok|oke|sip|mantap|siap|keren|good|bye|dadah)\b/i', $q)) {
            return "Sama-sama Kak! Senang sekali bisa membantu Kakak 😊🐿️✨<br><br>Jika nanti ada pertanyaan atau kendala lain, jangan ragu untuk chat Gian Care lagi ya. Selamat beraktivitas dan selamat bermain game di <strong>gian games</strong>! 🎮🌟";
        }

        // 15. DYNAMIC SMART GENERAL ANSWER (Instead of generic rejection)
        return "Terima kasih atas pertanyaannya mengenai <strong>" . e($query) . "</strong> ya Kak! 🐿️<br><br>" .
            "Untuk memberikan solusi terbaik bagi Kakak:<br>" .
            "• <strong>Bantuan Transaksi:</strong> Kakak bisa mengecek langsung status pesanan di <a href='/orders' class='text-[#0050ae] font-bold underline'>Riwayat Transaksi</a>.<br>" .
            "• <strong>Panduan Toko & Belanja:</strong> Silakan jelajahi topik pada tab <em>Sebagai Pembeli</em> atau <em>Sebagai Penjual</em> di halaman Pusat Bantuan ini.<br>" .
            "• <strong>Bantuan Khusus:</strong> Kakak juga bisa menuliskan pertanyaan lebih spesifik (contoh: <em>'cara top up'</em>, <em>'cara refund'</em>, atau <em>'lupa password'</em>) agar Gian Care bisa memberikan panduan langkah demi langkah yang tepat!";
    }
}
