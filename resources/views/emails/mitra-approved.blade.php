<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Mitra Disetujui</title>
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
        .icon-success {
            font-size: 64px;
            margin: 20px 0;
        }
        h1 {
            color: #222;
            font-size: 26px;
            margin: 20px 0 10px;
        }
        .success-box {
            background: linear-gradient(135deg, #00c853 0%, #00e676 100%);
            color: white;
            padding: 25px;
            margin: 25px 0;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 200, 83, 0.3);
        }
        .success-box h2 {
            margin: 0 0 10px 0;
            font-size: 22px;
        }
        .success-box p {
            margin: 0;
            font-size: 15px;
            opacity: 0.95;
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
            background: linear-gradient(135deg, #2ea0ff 0%, #1c98f5 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(46, 160, 255, 0.4);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 160, 255, 0.5);
        }
        .features {
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .features h3 {
            color: #333;
            margin-top: 0;
            font-size: 18px;
        }
        .feature-item {
            display: flex;
            align-items: start;
            margin: 15px 0;
            padding: 12px;
            background-color: white;
            border-radius: 8px;
            border-left: 3px solid #2ea0ff;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 15px;
            min-width: 30px;
        }
        .feature-content h4 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 15px;
        }
        .feature-content p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
            color: #777;
            font-size: 13px;
        }
        .footer p {
            margin: 8px 0;
        }
        .footer a {
            color: #2ea0ff;
            text-decoration: none;
        }
        .support-info {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .support-info strong {
            color: #f57c00;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🎯 PRISMO</div>
            <p style="color: #666; margin: 0;">Platform Booking Jasa Terpercaya</p>
        </div>

        <div class="icon-success">🎉</div>

        <h1>Selamat, {{ $mitra->name }}!</h1>

        <div class="success-box">
            <h2>✅ Pendaftaran Mitra Anda Disetujui</h2>
            <p>Anda kini resmi menjadi Mitra PRISMO</p>
        </div>

        <p style="font-size: 15px; color: #555; line-height: 1.8;">
            Terima kasih telah mendaftar sebagai mitra di platform <strong>PRISMO</strong>.
            Kami dengan senang hati menginformasikan bahwa pendaftaran Anda telah <strong>disetujui</strong>
            oleh tim admin kami.
        </p>

        <div class="info-box">
            <h3>📋 Informasi Akun Mitra</h3>
            <ul>
                <li><strong>Nama:</strong> {{ $mitra->name }}</li>
                <li><strong>Email:</strong> {{ $mitra->email }}</li>
                <li><strong>Nama Usaha:</strong> {{ $mitra->mitraProfile->business_name ?? 'Belum diatur' }}</li>
                <li><strong>Status:</strong> <span style="color: #00c853; font-weight: 600;">Aktif</span></li>
            </ul>
        </div>

        <div class="btn-container">
            <a href="{{ url('/mitra/dashboard') }}" class="btn">
                🚀 Akses Dashboard Mitra
            </a>
        </div>

        <div class="features">
            <h3>🌟 Apa yang Bisa Anda Lakukan Sekarang?</h3>

            <div class="feature-item">
                <div class="feature-icon">📝</div>
                <div class="feature-content">
                    <h4>Kelola Profil Usaha</h4>
                    <p>Lengkapi informasi bisnis, jam operasional, dan foto usaha Anda</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">💼</div>
                <div class="feature-content">
                    <h4>Tambahkan Layanan</h4>
                    <p>Buat katalog layanan dengan harga dan deskripsi yang menarik</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">📅</div>
                <div class="feature-content">
                    <h4>Terima Booking</h4>
                    <p>Mulai menerima pesanan dari customer dan kelola jadwal Anda</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">💰</div>
                <div class="feature-content">
                    <h4>Monitor Pendapatan</h4>
                    <p>Pantau saldo dan lakukan penarikan dana dengan mudah</p>
                </div>
            </div>
        </div>

        <div class="support-info">
            <strong>💡 Tips untuk Memulai:</strong>
            <ol style="margin: 10px 0; padding-left: 20px; color: #555;">
                <li>Lengkapi profil bisnis Anda dengan foto berkualitas tinggi</li>
                <li>Tambahkan minimal 3-5 layanan dengan harga kompetitif</li>
                <li>Atur jam operasional yang jelas dan konsisten</li>
                <li>Berikan pelayanan terbaik untuk mendapat review positif</li>
            </ol>
        </div>

        <p style="font-size: 15px; color: #555; line-height: 1.8; margin-top: 30px;">
            Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi
            tim support kami di <a href="mailto:support@prismo.com" style="color: #2ea0ff;">support@prismo.com</a>
        </p>

        <div class="footer">
            <p><strong>Selamat Bergabung dan Sukses Selalu! 🎊</strong></p>
            <p>Tim PRISMO</p>
            <p style="margin-top: 20px;">
                <a href="{{ url('/') }}">Kunjungi Website</a> |
                <a href="{{ url('/mitra/dashboard') }}">Dashboard Mitra</a> |
                <a href="mailto:support@prismo.com">Hubungi Support</a>
            </p>
            <p style="color: #999; font-size: 12px; margin-top: 20px;">
                Email ini dikirim otomatis, mohon tidak membalas email ini.<br>
                © {{ date('Y') }} PRISMO. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
