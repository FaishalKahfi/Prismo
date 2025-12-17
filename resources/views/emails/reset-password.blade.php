<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Prismo</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header img {
            max-width: 120px;
            margin-bottom: 16px;
        }
        .email-header h1 {
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #212121;
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 16px 0;
        }
        .email-body p {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }
        .reset-button {
            display: inline-block;
            background: #2196f3;
            color: white !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: background 0.3s;
        }
        .reset-button:hover {
            background: #1976d2;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #2196f3;
            padding: 16px 20px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 16px 20px;
            margin: 24px 0;
            border-radius: 4px;
        }
        .warning-box p {
            margin: 0;
            font-size: 14px;
            color: #856404;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }
        .email-footer p {
            color: #999;
            font-size: 13px;
            margin: 0 0 8px 0;
        }
        .email-footer a {
            color: #2196f3;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background: #e0e0e0;
            margin: 30px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
            }
            .email-header, .email-body, .email-footer {
                padding: 30px 20px;
            }
            .reset-button {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('images/logo.png') }}" alt="Prismo Logo">
            <h1>Reset Password</h1>
        </div>

        <div class="email-body">
            <h2>Halo, {{ $userName }}!</h2>

            <p>Kami menerima permintaan untuk reset password akun Prismo Anda. Klik tombol di bawah ini untuk membuat password baru:</p>

            <div class="button-container">
                <a href="{{ $magicLink }}" class="reset-button">Reset Password Saya</a>
            </div>

            <div class="info-box">
                <p><strong>⏱️ Link ini akan kadaluarsa dalam {{ $expiresIn }}</strong></p>
                <p>Segera lakukan reset password sebelum link ini tidak dapat digunakan.</p>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #666;">Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</p>
            <p style="font-size: 13px; word-break: break-all; color: #2196f3; background: #f8f9fa; padding: 12px; border-radius: 6px;">{{ $magicLink }}</p>

            <div class="warning-box">
                <p><strong>🔒 Keamanan Akun Anda</strong></p>
                <p>Jika Anda tidak meminta reset password, abaikan email ini. Password Anda akan tetap aman dan tidak ada perubahan yang dilakukan.</p>
            </div>
        </div>

        <div class="email-footer">
            <p>Email ini dikirim secara otomatis oleh sistem Prismo</p>
            <p>Jika Anda memerlukan bantuan, hubungi <a href="mailto:support@prismo.com">support@prismo.com</a></p>
            <p style="margin-top: 16px;">&copy; {{ date('Y') }} Prismo. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
