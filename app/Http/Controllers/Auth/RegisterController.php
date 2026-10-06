<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Exception;

class RegisterController extends Controller
{
    /**
     * Tampilkan form register.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses register akun baru.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Generate 6-digit OTP
        $otpCode = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            // Kirim email verifikasi OTP
            Mail::to($user->email)->send(new SendOtpMail($otpCode, $user->name));
        } catch (Exception $e) {
            // Hapus user yang baru dibuat jika email gagal dikirim agar user bisa mencoba lagi
            $user->delete();
            return back()->withInput()->withErrors(['email' => 'Gagal mengirim email verifikasi OTP. Silakan periksa kembali pengaturan SMTP Gmail Anda. Error: ' . $e->getMessage()]);
        }

        // Simpan OTP dan user_id ke dalam session (berlaku 10 menit)
        session([
            'otp_verification' => [
                'user_id'    => $user->id,
                'otp'        => $otpCode,
                'expires_at' => now()->addMinutes(10)->timestamp,
            ]
        ]);

        return redirect()->route('verify-otp');
    }

    /**
     * Tampilkan form verifikasi OTP.
     */
    public function showVerifyOtpForm()
    {
        if (!session()->has('otp_verification')) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi verifikasi tidak ditemukan. Silakan mendaftar kembali.']);
        }

        return view('auth.verify-otp');
    }

    /**
     * Proses verifikasi OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $sessionData = session('otp_verification');

        if (!$sessionData) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi verifikasi kedaluwarsa. Silakan mendaftar kembali.']);
        }

        // Cek jika OTP sudah kedaluwarsa
        if (time() > $sessionData['expires_at']) {
            session()->forget('otp_verification');
            $user = User::find($sessionData['user_id']);
            if ($user) {
                $user->delete();
            }
            return redirect()->route('register')->withErrors(['email' => 'Kode OTP telah kedaluwarsa (lebih dari 10 menit). Silakan daftar kembali.']);
        }

        // Cek kecocokan OTP
        if ($request->otp !== $sessionData['otp']) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
        }

        // Dapatkan user dan login
        $user = User::find($sessionData['user_id']);
        if (!$user) {
            return redirect()->route('register')->withErrors(['email' => 'Pengguna tidak ditemukan. Silakan daftar kembali.']);
        }

        // Set email terverifikasi
        $user->email_verified_at = now();
        $user->save();

        // Login ke aplikasi
        Auth::login($user);

        // Hapus session OTP
        session()->forget('otp_verification');

        return redirect()->route('dashboard')->with('success', 'Akun Anda berhasil diverifikasi!');
    }
    /**
     * Tampilkan halaman landing seller + form verifikasi.
     */
    public function showBecomeSeller()
    {
        $user = Auth::user();

        // Sudah seller/admin → langsung ke produk
        if ($user->isSeller() || $user->isAdmin()) {
            return redirect()->route('products.create')
                ->with('success', 'Anda sudah terdaftar sebagai Seller!');
        }

        // Sudah apply sebelumnya
        if ($user->seller_status === 'pending') {
            return redirect()->route('dashboard')
                ->with('success', 'Pendaftaran seller Anda sedang dalam proses verifikasi admin. Harap tunggu.');
        }

        return view('auth.become-seller');
    }

    /**
     * Proses pendaftaran seller: upload KTP + selfie, set status pending.
     */
    /**
     * Proses pendaftaran seller: upload KTP + selfie, kirim email verifikasi OTP.
     */
    public function becomeSeller(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isSeller() || $user->isAdmin()) {
            return redirect()->route('products.create')
                ->with('success', 'Akun Anda sudah terdaftar sebagai Seller.');
        }

        $request->validate([
            'ktp_photo'    => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'selfie_photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'agree_terms'  => ['required', 'accepted'],
        ], [
            'ktp_photo.required'    => 'Foto KTP wajib diunggah.',
            'ktp_photo.image'       => 'File KTP harus berupa gambar.',
            'ktp_photo.max'         => 'Ukuran foto KTP maksimal 5MB.',
            'selfie_photo.required' => 'Foto wajah/selfie wajib diambil melalui kamera.',
            'selfie_photo.image'    => 'Foto selfie harus berupa gambar.',
            'selfie_photo.max'      => 'Ukuran foto selfie maksimal 5MB.',
            'agree_terms.accepted'  => 'Anda harus menyetujui syarat dan ketentuan penjual.',
        ]);

        // Simpan foto KTP & selfie ke storage
        $ktpPath    = $request->file('ktp_photo')->store('seller-verifications/ktp', 'public');
        $selfiePath = $request->file('selfie_photo')->store('seller-verifications/selfie', 'public');

        // Generate 6-digit OTP
        $otpCode = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            // Kirim email kode OTP verifikasi seller
            Mail::to($user->email)->send(new SendOtpMail($otpCode, $user->name));
        } catch (Exception $e) {
            // Hapus file yang sempat diupload jika pengiriman email gagal
            \Illuminate\Support\Facades\Storage::disk('public')->delete([$ktpPath, $selfiePath]);
            return back()->withInput()->withErrors(['error' => 'Gagal mengirim email verifikasi OTP: ' . $e->getMessage()]);
        }

        // Simpan data pendaftaran sementara & OTP ke session (berlaku 10 menit)
        session([
            'seller_otp_verification' => [
                'user_id'           => $user->id,
                'ktp_photo'         => $ktpPath,
                'selfie_photo'      => $selfiePath,
                'otp'               => $otpCode,
                'expires_at'        => now()->addMinutes(10)->timestamp,
            ]
        ]);

        return redirect()->route('become-seller.verify-otp')
            ->with('success', 'Kode OTP verifikasi 6-digit telah dikirimkan ke email Anda (' . $user->email . '). Masukkan kode untuk menyelesaikan pendaftaran Seller.');
    }

    /**
     * Tampilkan halaman input OTP verifikasi pendaftaran seller.
     */
    public function showBecomeSellerVerifyOtpForm()
    {
        if (!session()->has('seller_otp_verification')) {
            return redirect()->route('become-seller')->withErrors(['error' => 'Sesi verifikasi seller tidak ditemukan atau telah berakhir. Silakan isi form kembali.']);
        }

        return view('auth.become-seller-otp');
    }

    /**
     * Verifikasi kode OTP pendaftaran seller.
     */
    public function verifyBecomeSellerOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size'     => 'Kode OTP harus terdiri dari 6 digit.',
        ]);

        $sessionData = session('seller_otp_verification');

        if (!$sessionData) {
            return redirect()->route('become-seller')->withErrors(['error' => 'Sesi verifikasi kedaluwarsa. Silakan ulangi pendaftaran.']);
        }

        // Cek jika OTP sudah kedaluwarsa (> 10 menit)
        if (time() > $sessionData['expires_at']) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete([$sessionData['ktp_photo'], $sessionData['selfie_photo']]);
            session()->forget('seller_otp_verification');
            return redirect()->route('become-seller')->withErrors(['error' => 'Kode OTP telah kedaluwarsa (lebih dari 10 menit). Silakan ulangi pendaftaran Seller.']);
        }

        // Cek kecocokan OTP
        if ($request->otp !== $sessionData['otp']) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user || $user->id !== $sessionData['user_id']) {
            return redirect()->route('login')->withErrors(['error' => 'Pengguna tidak valid. Silakan login kembali.']);
        }

        // Simpan data pendaftaran seller ke database user
        $user->update([
            'ktp_photo'         => $sessionData['ktp_photo'],
            'selfie_photo'      => $sessionData['selfie_photo'],
            'seller_status'     => 'pending',
            'seller_applied_at' => now(),
        ]);

        // Hapus session OTP verifikasi seller
        session()->forget('seller_otp_verification');

        return redirect()->route('dashboard')
            ->with('success', '🎉 Verifikasi OTP Berhasil! Pendaftaran Seller Anda telah dikirim dan sedang dalam proses verifikasi identitas oleh Admin gian games (1×24 jam).');
    }

    /**
     * Kirim ulang kode OTP pendaftaran seller.
     */
    public function resendBecomeSellerOtp()
    {
        $sessionData = session('seller_otp_verification');

        if (!$sessionData) {
            return redirect()->route('become-seller')->withErrors(['error' => 'Sesi verifikasi tidak ditemukan. Silakan isi form pendaftaran kembali.']);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $otpCode = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            Mail::to($user->email)->send(new SendOtpMail($otpCode, $user->name));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengirim ulang OTP: ' . $e->getMessage()]);
        }

        $sessionData['otp'] = $otpCode;
        $sessionData['expires_at'] = now()->addMinutes(10)->timestamp;
        session(['seller_otp_verification' => $sessionData]);

        return back()->with('success', 'Kode OTP baru telah berhasil dikirim ulang ke email Anda (' . $user->email . ').');
    }
}
