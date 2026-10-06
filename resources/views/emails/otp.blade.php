<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP</title>
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
            margin-bottom: 30px;
        }
        .logo {
            font-size: 22px;
            font-weight: 700;
            color: #f43f5e; /* Rose 500 */
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
            margin-bottom: 12px;
            text-align: center;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 24px 0;
        }
        .message-body {
            font-size: 15px;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 24px;
        }
        .otp-container {
            text-align: center;
            margin: 32px 0;
        }
        .otp-box {
            display: inline-block;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            padding: 16px 36px;
            border-radius: 12px;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 6px;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .alert-box {
            background-color: #fff1f2;
            border: 1px solid #ffe4e6;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
        }
        .alert-text {
            font-size: 13px;
            color: #e11d48;
            margin: 0;
            line-height: 1.5;
            text-align: center;
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
                    <span style="margin-right: 8px; font-size: 24px;">🔥</span>
                    {{ config('app.name', 'gian games') }}
                </a>
            </div>

            <!-- Heading -->
            <h1 class="heading">Verifikasi Identitas Anda</h1>
            
            <div class="divider"></div>

            <!-- Message Body -->
            <div class="message-body">
                Halo <strong>{{ $userName }}</strong>,
                <br><br>
                Kami menerima permintaan akses atau tindakan keamanan untuk akun Anda. Gunakan kode verifikasi OTP 6-digit berikut untuk melanjutkan proses verifikasi:
            </div>

            <!-- OTP Code Box -->
            <div class="otp-container">
                <div class="otp-box">{{ $otpCode }}</div>
            </div>

            <!-- Alert Expiry -->
            <div class="alert-box">
                <p class="alert-text">
                    ⚠️ <strong>Penting:</strong> Kode OTP ini hanya berlaku selama <strong>10 menit</strong>. 
                    Jangan pernah membagikan kode verifikasi ini kepada siapapun, termasuk pihak yang mengatasnamakan {{ config('app.name') }}.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                Jika Anda tidak meminta email ini, abaikan pesan ini dengan aman.<br>
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
