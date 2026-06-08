<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kode Verifikasi Reset Password</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f5f5f5; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #bc0007 0%, #e2241d 100%); padding: 40px 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 26px; font-weight: 800; letter-spacing: 4px; }
        .header p { color: rgba(255,255,255,0.75); font-size: 13px; margin-top: 6px; }
        .body { padding: 36px 32px; }
        .greeting { font-size: 16px; color: #1a1c1c; font-weight: 600; margin-bottom: 12px; }
        .text { font-size: 14px; color: #5d5e60; line-height: 1.7; margin-bottom: 28px; }
        .otp-box { background: linear-gradient(135deg, #fff5f5 0%, #fff0f0 100%); border: 2px dashed #e2241d; border-radius: 12px; padding: 24px; text-align: center; margin: 20px 0 28px; }
        .otp-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: #926f6a; margin-bottom: 12px; }
        .otp-code { font-size: 46px; font-weight: 900; letter-spacing: 12px; color: #bc0007; font-family: 'Courier New', monospace; }
        .expiry { font-size: 12px; color: #926f6a; margin-top: 10px; }
        .warning { background: #fff8e1; border-left: 4px solid #f9a825; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px; }
        .warning p { font-size: 13px; color: #5d4e00; line-height: 1.6; }
        .footer { background: #f9f9f9; border-top: 1px solid #e8e8e8; padding: 24px 32px; text-align: center; }
        .footer p { font-size: 12px; color: #926f6a; line-height: 1.8; }
        .footer strong { color: #bc0007; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>S I S T E R</h1>
            <p>Sistem Informasi Telkomsel Haji</p>
        </div>
        <div class="body">
            <p class="greeting">Halo, {{ $userName }} 👋</p>
            <p class="text">
                Kami menerima permintaan untuk mereset kata sandi akun Anda. 
                Gunakan kode verifikasi di bawah ini untuk melanjutkan proses reset password.
            </p>

            <div class="otp-box">
                <p class="otp-label">Kode Verifikasi Anda</p>
                <p class="otp-code">{{ $otp }}</p>
                <p class="expiry">⏱ Kode berlaku selama <strong>10 menit</strong></p>
            </div>

            <div class="warning">
                <p>
                    ⚠️ <strong>Jangan bagikan kode ini kepada siapapun.</strong><br>
                    Tim SISTER tidak akan pernah meminta kode verifikasi Anda.<br>
                    Jika Anda tidak meminta reset password, abaikan email ini.
                </p>
            </div>

            <p class="text">
                Jika Anda mengalami kendala, silakan hubungi tim support kami melalui halaman Bantuan & Dukungan.
            </p>
        </div>
        <div class="footer">
            <p>
                Email ini dikirim secara otomatis, mohon tidak membalas.<br>
                <strong>© 2026 Telkomsel RoaMAX Haji</strong> · Seluruh hak cipta dilindungi.
            </p>
        </div>
    </div>
</body>
</html>
