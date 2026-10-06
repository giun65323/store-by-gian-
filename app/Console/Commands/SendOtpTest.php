<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Exception;

class SendOtpTest extends Command
{
    /**
     * Nama dan signature perintah console.
     *
     * @var string
     */
    protected $signature = 'mail:send-otp {email : Alamat email tujuan}';

    /**
     * Deskripsi perintah console.
     *
     * @var string
     */
    protected $description = 'Menguji pengiriman email verifikasi OTP 6-digit menggunakan SMTP Gmail';

    /**
     * Eksekusi perintah console.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Validasi input email sederhana
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Format email '$email' tidak valid!");
            return Command::FAILURE;
        }

        // Generate 6-digit OTP
        $otpCode = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $userName = 'Pengguna Percobaan';

        $this->info("Menyiapkan pengiriman OTP ($otpCode) ke $email...");

        try {
            // Mengirim email
            Mail::to($email)->send(new SendOtpMail($otpCode, $userName));
            
            $this->info("==============================================");
            $this->info("Sukses! Email verifikasi OTP berhasil dikirim.");
            $this->info("Silakan cek kotak masuk atau folder spam di: $email");
            $this->info("==============================================");
            
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error("==============================================");
            $this->error("Gagal mengirim email verifikasi OTP!");
            $this->error("Pesan Error: " . $e->getMessage());
            $this->error("==============================================");
            $this->line("");
            $this->warn("Tips Troubleshooting:");
            $this->line("1. Pastikan Anda telah mengaktifkan 2-Step Verification pada Akun Google Anda.");
            $this->line("2. Buat App Password (16-digit) khusus untuk aplikasi ini.");
            $this->line("3. Pastikan konfigurasi MAIL_USERNAME dan MAIL_PASSWORD di file .env sudah sesuai.");
            $this->line("4. Periksa koneksi internet server Anda (pastikan port 587 terbuka).");
            
            return Command::FAILURE;
        }
    }
}
