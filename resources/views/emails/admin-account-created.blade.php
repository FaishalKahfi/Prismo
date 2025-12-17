<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Admin Prismo Anda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f6f8;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }
        .logo {
            font-size: 32px;
            font-weight: 700;
            color: #2ea0ff;
            margin-bottom: 10px;
        }
        .icon-admin {
            font-size: 64px;
            margin: 20px 0;
        }
        h1 {
            color: #222;
            font-size: 26px;
            margin: 20px 0 10px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            margin: 25px 0;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .welcome-box h2 {
            margin: 0 0 10px 0;
            font-size: 22px;
        }
        .welcome-box p {
            margin: 0;
            font-size: 15px;
            opacity: 0.95;
        }
        .credentials-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .credentials-box h3 {
            color: #856404;
            margin-top: 0;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .credential-item {
            background: white;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }
        .credential-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .credential-value {
            font-size: 16px;
            color: #222;
            font-weight: 600;
            font-family: 'Courier New', monospace;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .info-box h3 {
            color: #1976d2;
            margin-top: 0;
            font-size: 18px;
        }
        .info-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 8px 0;
            color: #555;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        .warning-box {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .warning-box h3 {
            color: #c62828;
            margin-top: 0;
            font-size: 16px;
        }
        .warning-box p {
            margin: 8px 0;
            color: #666;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
            color: #888;
            font-size: 13px;
        }
        .footer a {
            color: #2ea0ff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">PRISMO</div>
            <div class="icon-admin">🔐</div>
            <h1>Selamat Datang, Admin!</h1>
        </div>

        <div class="welcome-box">
            <h2>Akun Admin Anda Telah Dibuat</h2>
            <p>Anda sekarang memiliki akses ke panel admin Prismo</p>
        </div>

        <p style="font-size: 16px; color: #555;">
            Halo <strong>{{ $admin->name }}</strong>,
        </p>

        <p style="font-size: 15px; color: #555;">
            Akun administrator Anda telah berhasil dibuat di sistem Prismo. Berikut adalah informasi login Anda:
        </p>

        <div class="credentials-box">
            <h3>⚠️ Informasi Login Anda</h3>

            <div class="credential-item">
                <div class="credential-label">Email</div>
                <div class="credential-value">{{ $admin->email }}</div>
            </div>

            <div class="credential-item">
                <div class="credential-label">Password</div>
                <div class="credential-value">{{ $plainPassword }}</div>
            </div>
        </div>

        <div class="warning-box">
            <h3>🔒 Keamanan Penting</h3>
            <p><strong>Harap segera ubah password Anda</strong> setelah login pertama kali untuk menjaga keamanan akun.</p>
            <p>Jangan bagikan informasi login ini kepada siapa pun.</p>
        </div>

        <div class="btn-container">
            <a href="{{ url('/login') }}" class="btn">Login ke Dashboard Admin</a>
        </div>

        <div class="info-box">
            <h3>📋 Sebagai Admin, Anda Dapat:</h3>
            <ul>
                <li>Mengelola data customer dan mitra</li>
                <li>Menyetujui atau menolak pendaftaran mitra baru</li>
                <li>Mengelola voucher dan promosi</li>
                <li>Memantau booking dan transaksi</li>
                <li>Mengelola penarikan saldo mitra</li>
                <li>Melihat laporan dan statistik platform</li>
                <li>Menambah atau menghapus admin lain</li>
            </ul>
        </div>

        <p style="font-size: 14px; color: #666; margin-top: 30px;">
            Jika Anda memiliki pertanyaan atau mengalami kesulitan dalam mengakses akun, silakan hubungi administrator utama.
        </p>

        <div class="footer">
            <p>
                Email ini dikirim secara otomatis oleh sistem Prismo.<br>
                Jangan balas email ini.
            </p>
            <p style="margin-top: 15px;">
                © 2025 Prismo. All rights reserved.<br>
                <a href="{{ url('/') }}">www.prismo.com</a>
            </p>
        </div>
    </div>
</body>
</html>
