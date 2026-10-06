<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordOtpMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form input email untuk lupa password.
     */
    public function showForgotForm(Request $request)
    {
        $prefilledEmail = $request->query('email', old('email', ''));
        return view('auth.forgot-password', compact('prefilledEmail'));
    }

    /**
     * Kirim kode OTP reset password ke email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Silakan masukkan alamat email Anda.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()->withErrors([
                'email' => 'Alamat email ini tidak terdaftar dalam sistem kami.'
            ]);
        }

        // Generate 6-digit OTP
        $otpCode = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            // Kirim email OTP menggunakan Gmail SMTP
            Mail::to($user->email)->send(new ResetPasswordOtpMail($otpCode, $user->name));
        } catch (Exception $e) {
            return back()->withInput()->withErrors([
                'email' => 'Gagal mengirim email OTP ke Gmail. Silakan cek konfigurasi email atau coba lagi beberapa saat lagi. Detail: ' . $e->getMessage()
            ]);
        }

        // Simpan data OTP ke session (berlaku 10 menit)
        session([
            'password_reset_otp' => [
                'user_id'    => $user->id,
                'email'      => $user->email,
                'user_name'  => $user->name,
                'otp'        => $otpCode,
                'expires_at' => now()->addMinutes(10)->timestamp,
                'verified'   => false,
            ]
        ]);

        return redirect()->route('password.otp')
            ->with('success', 'Kode OTP 6-digit telah dikirim ke alamat email Gmail Anda: ' . $user->email);
    }

    /**
     * Tampilkan form input kode OTP.
     */
    public function showOtpForm()
    {
        $sessionData = session('password_reset_otp');

        if (!$sessionData) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password tidak ditemukan. Silakan masukkan email Anda kembali.']);
        }

        // Mask email for privacy, e.g. al***@gmail.com
        $email = $sessionData['email'];
        $parts = explode('@', $email);
        $maskedEmail = substr($parts[0], 0, min(2, strlen($parts[0]))) . '***@' . ($parts[1] ?? 'gmail.com');

        return view('auth.reset-password-otp', compact('maskedEmail', 'sessionData'));
    }

    /**
     * Verifikasi kode OTP yang dimasukkan user.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Silakan masukkan 6-digit kode OTP.',
            'otp.size'     => 'Kode OTP harus terdiri dari 6 angka.',
        ]);

        $sessionData = session('password_reset_otp');

        if (!$sessionData) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi verifikasi telah kedaluwarsa. Silakan masukkan email kembali.']);
        }

        // Cek kedaluwarsa waktu
        if (time() > $sessionData['expires_at']) {
            session()->forget('password_reset_otp');
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Kode OTP telah kedaluwarsa (lebih dari 10 menit). Silakan minta kode baru.']);
        }

        // Validasi kode OTP
        if ($request->otp !== $sessionData['otp']) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah. Periksa kembali kotak masuk email Anda.']);
        }

        // Tandai sudah terverifikasi
        $sessionData['verified'] = true;
        session(['password_reset_otp' => $sessionData]);

        return redirect()->route('password.new')
            ->with('success', 'Kode OTP berhasil diverifikasi! Silakan masukkan password baru Anda.');
    }

    /**
     * Kirim ulang kode OTP.
     */
    public function resendOtp()
    {
        $sessionData = session('password_reset_otp');

        if (!$sessionData) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi telah kedaluwarsa. Silakan masukkan email kembali.']);
        }

        $user = User::find($sessionData['user_id']);
        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        $otpCode = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            Mail::to($user->email)->send(new ResetPasswordOtpMail($otpCode, $user->name));
        } catch (Exception $e) {
            return back()->withErrors(['otp' => 'Gagal mengirim ulang email OTP. Error: ' . $e->getMessage()]);
        }

        $sessionData['otp'] = $otpCode;
        $sessionData['expires_at'] = now()->addMinutes(10)->timestamp;
        $sessionData['verified'] = false;
        session(['password_reset_otp' => $sessionData]);

        return back()->with('success', 'Kode OTP baru telah dikirimkan ke email Anda!');
    }

    /**
     * Tampilkan form pembuatan password baru setelah OTP valid.
     */
    public function showResetPasswordForm()
    {
        $sessionData = session('password_reset_otp');

        if (!$sessionData || empty($sessionData['verified'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Anda harus memverifikasi kode OTP terlebih dahulu sebelum mengganti password.']);
        }

        return view('auth.reset-password-new', compact('sessionData'));
    }

    /**
     * Update password baru pengguna ke database.
     */
    public function updatePassword(Request $request)
    {
        $sessionData = session('password_reset_otp');

        if (!$sessionData || empty($sessionData['verified'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid atau belum terverifikasi.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'password.required'  => 'Silakan masukkan password baru.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password baru minimal harus 8 karakter.',
        ]);

        $user = User::find($sessionData['user_id']);

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        // Update password baru di database
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus session OTP
        session()->forget('password_reset_otp');

        return redirect()->route('login')
            ->with('success', 'Password Anda berhasil diganti! Silakan masuk dengan kata sandi baru Anda.');
    }
}
