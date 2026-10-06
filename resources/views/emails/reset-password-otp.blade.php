<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Atur Ulang Password</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 0;
        }
        .container {
            max-width: 520px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
            border: 1px solid #f1f5f9;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 26px;
        }
        .logo {
            font-size: 22px;
            font-weight: 700;
            color: #f43f5e;
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }
        .heading {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0;
            margin-bottom: 10px;
            text-align: center;
        }
        .subheading {
            font-size: 13px;
            color: #64748b;
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 20px 0;
        }
        .message-body {
            font-size: 14px;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 24px;
        }
        .otp-container {
            text-align: center;
            margin: 28px 0;
        }
        .otp-box {
            display: inline-block;
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            padding: 16px 36px;
            border-radius: 14px;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: 8px;
            color: #e11d48;
            border: 2px solid #fecdd3;
            box-shadow: inset 0 2px 4px rgba(225,29,72,0.04);
        }
        .alert-box {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 24px;
        }
        .alert-text {
            font-size: 13px;
            color: #b45309;
            margin: 0;
            line-height: 1.5;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .footer a {
            color: #f43f5e;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- App Logo -->
            <div class="logo-container">
                <a href="#" class="logo">
                    <span style="margin-right: 8px; font-size: 24px;">🔑</span>
                    {{ config('app.name', 'gian games') }}
                </a>
            </div>

            <!-- Heading -->
            <h1 class="heading">Atur Ulang Kata Sandi</h1>
            <p class="subheading">Permintaan penggantian kata sandi akun Anda</p>
            
            <div class="divider"></div>

            <!-- Message Body -->
            <div class="message-body">
                Halo <strong>{{ $userName }}</strong>,
                <br><br>
                Kami menerima permintaan untuk mereset kata sandi akun <strong>{{ config('app.name') }}</strong> Anda. Silakan masukkan kode OTP 6-digit berikut pada halaman verifikasi:
            </div>

            <!-- OTP Code Box -->
            <div class="otp-container">
                <div class="otp-box">{{ $otpCode }}</div>
            </div>

            <!-- Alert Expiry -->
            <div class="alert-box">
                <p class="alert-text">
                    ⚠️ <strong>Penting:</strong> Kode OTP ini hanya berlaku selama <strong>10 menit</strong>. 
                    Jika Anda tidak merasa meminta penggantian kata sandi ini, segera amankan akun Anda dan abaikan email ini.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                Email ini dikirim secara otomatis oleh sistem keamanan {{ config('app.name') }}.<br>
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
