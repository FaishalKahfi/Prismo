<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Syarat dan Ketentuan Platform Prismo untuk Customer dan Mitra - Baca ketentuan penggunaan layanan booking jasa cuci steam.">
    <meta name="keywords" content="syarat ketentuan, terms and conditions, prismo, kebijakan platform">
    <title>Syarat & Ketentuan - Prismo</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Icon-prismo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .terms-container {
            max-width: 1000px;
            margin: 120px auto 50px;
            padding: 50px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .terms-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid #2ea0ff;
        }

        .terms-header h1 {
            color: #1a1a1a;
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .terms-header p {
            color: #666;
            font-size: 16px;
        }

        .greeting-box {
            background: linear-gradient(135deg, #2ea0ff 0%, #1c98f5 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(46, 160, 255, 0.3);
        }

        .greeting-box h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        .greeting-box p {
            margin: 0;
            line-height: 1.8;
            font-size: 15px;
        }

        .terms-content {
            color: #333;
            line-height: 1.8;
            font-size: 15px;
        }

        .terms-section {
            margin-bottom: 35px;
        }

        .terms-section h2 {
            color: #1a1a1a;
            font-size: 24px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 600;
        }

        .terms-section h3 {
            color: #2ea0ff;
            font-size: 18px;
            margin: 20px 0 10px 0;
            font-weight: 600;
        }

        .terms-section h4 {
            color: #444;
            font-size: 16px;
            margin: 15px 0 8px 0;
            font-weight: 600;
        }

        .terms-section p {
            margin-bottom: 12px;
            text-align: justify;
        }

        .terms-section ul,
        .terms-section ol {
            margin: 15px 0;
            padding-left: 30px;
        }

        .terms-section li {
            margin-bottom: 10px;
        }

        .highlight-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .highlight-box strong {
            color: #f57c00;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .success-box {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 6px;
        }

        .agreement-box {
            background: #f5f5f5;
            padding: 25px;
            border-radius: 10px;
            margin-top: 40px;
            border: 2px solid #2ea0ff;
        }

        .agreement-box p {
            margin: 0;
            font-weight: 600;
            color: #1a1a1a;
            text-align: center;
            font-size: 16px;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 12px 24px;
            background: #f5f5f5;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: #2ea0ff;
            color: white;
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            .terms-container {
                margin: 80px 20px 30px;
                padding: 25px 20px;
            }

            .terms-header h1 {
                font-size: 26px;
            }

            .terms-section h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Prismo Logo">
                    </a>
                </div>
                <nav class="nav" id="mainNav">
                    <ul>
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ url('/tentang') }}">Tentang Kami</a></li>
                        <li><a href="{{ url('/syarat-ketentuan') }}" class="active">Syarat & Ketentuan</a></li>
                    </ul>
                </nav>
                <div class="header-buttons">
                    @guest
                        <button class="btn-outline" onclick="window.location.href='{{ url('/login?tab=login') }}'">Masuk</button>
                        <button class="btn-primary" onclick="window.location.href='{{ url('/register?tab=register') }}'">Daftar</button>
                    @else
                        @if(Auth::user()->role === 'customer')
                            <button class="btn-primary" onclick="window.location.href='{{ url('/customer/dashboard') }}'">Dashboard</button>
                        @elseif(Auth::user()->role === 'mitra')
                            <button class="btn-primary" onclick="window.location.href='{{ url('/mitra/dashboard') }}'">Dashboard</button>
                        @elseif(Auth::user()->role === 'admin')
                            <button class="btn-primary" onclick="window.location.href='{{ url('/admin/dashboard') }}'">Dashboard</button>
                        @endif
                    @endguest
                </div>
                <button class="mobile-menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <div class="terms-container">
        <div class="terms-header">
            <h1>Syarat & Ketentuan Platform Prismo</h1>
            <p>Untuk Customer dan Mitra</p>
        </div>

        <div class="terms-content">
            <div class="greeting-box">
                <h2>Salam dari Prismo!</h2>
                <p>Halo, perkenalkan kami <strong>Prismo</strong> adalah platform penyedia layanan teknologi yang memfasilitasi pemesanan (booking) jasa cuci steam, menghubungkan pengguna ("Customer") dengan penyedia jasa cuci steam ("Mitra"). Dengan mengakses dan menggunakan Layanan Prismo, Pelanggan dan Mitra menyatakan setuju untuk terikat pada Ketentuan ini secara keseluruhan.</p>
            </div>

            <!-- 1. Ketentuan Umum -->
            <div class="terms-section">
                <h2>1. Ketentuan Umum Layanan Platform</h2>

                <h3>Penerimaan Ketentuan</h3>
                <p>Pelanggan dan Mitra dianggap telah membaca, memahami, dan menyetujui seluruh isi ketentuan ini sejak pembuatan akun, pengaksesan atau penggunaan Layanan Prismo.</p>

                <h3>Sifat Layanan Prismo</h3>
                <p>Prismo bertindak sebagai penyedia platform teknologi dan mediator. Prismo bukan penyedia jasa cuci steam secara langsung. Jasa cuci steam sepenuhnya disediakan oleh pihak mitra.</p>

                <h3>Perubahan Syarat & Ketentuan</h3>
                <p>Prismo (selaku penyedia platform) berhak mengubah, menambah, atau mengurangi syarat & ketentuan ini sewaktu-waktu. Pelanggan dan Mitra bertanggung jawab untuk meninjau perubahan syarat & ketentuan secara berkala.</p>

                <h3>Hukum yang Berlaku</h3>
                <p>Syarat & Ketentuan ini tunduk pada hukum dan peraturan yang berlaku di Negara Republik Indonesia.</p>
            </div>

            <!-- 2. Akun dan Keamanan -->
            <div class="terms-section">
                <h2>2. Akun dan Keamanan</h2>

                <h3>Persyaratan Pengguna</h3>
                <p>Pengguna wajib berusia minimal 18 (delapan belas) tahun atau menggunakan layanan dengan pengawasan orang tua/wali yang sah.</p>

                <h3>Tanggung Jawab Akun</h3>
                <p>Customer dan Mitra bertanggung jawab penuh atas kerahasiaan data akun, termasuk kata sandi dan seluruh aktivitas yang terjadi dibawah akun masing-masing.</p>

                <h3>Larangan Penggunaan</h3>
                <p>Pengguna dilarang menggunakan platform Prismo untuk tujuan yang melanggar hukum, penipuan, merusak sistem atau merugikan reputasi Prismo.</p>
            </div>

            <!-- 3. Pemesanan, Harga & Pembayaran -->
            <div class="terms-section">
                <h2>3. Ketentuan Pemesanan (Booking), Harga dan Pembayaran</h2>

                <h3>Pemesanan</h3>
                <p>Customer dapat melakukan pemesanan (booking) jasa cuci steam dari mitra melalui platform Prismo.</p>

                <h3>Konfirmasi</h3>
                <p>Pemesanan dianggap sah setelah Customer menerima konfirmasi resmi booking melalui Platform Prismo dan surat elektronik (email).</p>

                <h3>Harga</h3>
                <p>Harga layanan sepenuhnya ditetapkan oleh Mitra dan ditampilkan oleh Platform.</p>

                <div class="info-box">
                    <h4>💳 Pembayaran (QRIS Prismo)</h4>
                    <p>Pembayaran total biaya wajib dilakukan melalui metode QRIS Prismo yang disediakan. Sistem pembayaran terpusat ini bertujuan untuk mengamankan transaksi, terutama dalam kasus pembatalan sepihak oleh pelanggan.</p>
                </div>

                <div class="success-box">
                    <h4>🎉 Kebijakan Biaya (Periode Awal Kemitraan)</h4>
                    <p>Sebagai bentuk dukungan, Prismo tidak memungut komisi atau biaya layanan dari pelanggan maupun mitra selama periode awal yang telah ditentukan.</p>
                </div>

                <h3>Penarikan Dana Mitra</h3>
                <p>Mitra dapat mengajukan penarikan dana hasil transaksi yang telah diselesaikan kapan saja (on-demand) melalui sistem yang disediakan oleh Prismo.</p>
            </div>

            <!-- 4. Pembatalan -->
            <div class="terms-section">
                <h2>4. Pembatalan</h2>

                <h3>Pembatalan oleh Customer</h3>
                <p>Platform Prismo berhak memberlakukan biaya pembatalan jika Customer membatalkan booking dalam jangka waktu yang terlalu dekat dengan jadwal yang disepakati.</p>

                <h3>Pembatalan oleh Mitra</h3>
                <p>Jika Mitra membatalkan pemesanan, Prismo akan berupaya maksimal untuk memberikan notifikasi segera kepada Customer dan memfasilitasi penjadwalan ulang atau pencarian Mitra alternatif.</p>
            </div>

            <!-- 5. Tanggung Jawab -->
            <div class="terms-section">
                <h2>5. Batasan dan Distribusi Tanggung Jawab</h2>

                <h3>A. Tanggung Jawab Mitra (Penyedia Jasa)</h3>

                <h4>Kualitas Layanan</h4>
                <p>Mitra bertanggung jawab penuh atas kualitas layanan cuci steam yang disediakan.</p>

                <h4>Klaim Kerusakan</h4>
                <p>Mitra bertanggung jawab penuh untuk menangani dan menyelesaikan klaim atau keluhan terkait kerusakan (seperti goresan atau kerusakan komponen lainnya) yang timbul pada kendaraan Customer selama proses pencucian.</p>

                <h4>Ketersediaan</h4>
                <p>Mitra wajib menghormati dan memenuhi booking yang telah dikonfirmasi sesuai jadwal dan spesifikasi layanan.</p>

                <h3>B. Batasan Tanggung Jawab Platform Prismo</h3>

                <div class="highlight-box">
                    <h4>⚠️ Tidak Bertanggung Jawab atas Kerugian Langsung</h4>
                    <p>Prismo tidak bertanggung jawab atas kerugian, kerusakan atau hal tak terduga yang terjadi pada objek pencucian (kendaraan) selama proses penyediaan jasa. Segala risiko yang timbul adalah sepenuhnya tanggung jawab antara Customer dan Mitra.</p>
                </div>

                <h4>Akurasi Informasi</h4>
                <p>Platform berupaya menyajikan informasi Mitra secara akurat, namun tidak menjamin bahwa semua informasi (termasuk ketersediaan dan jam operasional) selalu mutakhir.</p>

                <h4>Fungsi Komunikasi</h4>
                <p>Platform hanya memfasilitasi komunikasi selama transaksi sedang berlangsung antara Customer dan Mitra.</p>
            </div>

            <!-- 6. Penyelesaian Keluhan -->
            <div class="terms-section">
                <h2>6. Penyelesaian Keluhan dan Masalah</h2>

                <h3>Keluhan Layanan Cuci Steam</h3>
                <p>Keluhan terkait kualitas atau pelaksanaan layanan cuci steam harus ditujukan langsung kepada pihak Mitra yang bersangkutan. Prismo dapat membantu memfasilitasi penyampaian keluhan tersebut.</p>

                <h3>Masalah Transaksi/Pembayaran</h3>
                <p>Masalah terkait pembayaran melalui QRIS Prismo dapat diajukan kepada layanan Pelanggan Prismo.</p>
            </div>

            <!-- 7. Ketentuan Khusus Mitra -->
            <div class="terms-section">
                <h2>7. Ketentuan Khusus Mitra (Penyedia Jasa)</h2>

                <h3>Perjanjian Kemitraan</h3>
                <p>Mitra wajib tunduk pada Perjanjian Kemitraan terpisah yang mengatur secara rinci proses pembayaran, penarikan dana dan standar operasional yang telah ditetapkan oleh Prismo.</p>

                <h3>Standar Kualitas</h3>
                <p>Mitra wajib menjaga standar kualitas layanan yang ditetapkan oleh Platform. Pelanggaran terhadap standar ini dapat menyebabkan penangguhan atau pemutusan kerja sama kemitraan.</p>
            </div>

            <!-- Persetujuan -->
            <div class="agreement-box">
                <p>Dengan ini, Customer dan Mitra menyatakan menyetujui, memahami dan tunduk pada seluruh Syarat & Ketentuan yang ditetapkan oleh Prismo.</p>
            </div>

            <div style="text-align: center; margin-top: 40px; color: #999; font-size: 14px;">
                <p>Terakhir diperbarui: {{ date('d F Y') }}</p>
                <p>© {{ date('Y') }} Prismo. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <img src="{{ asset('images/logo.png') }}" alt="Prismo Logo" class="footer-logo-img">
                    <p class="footer-description">Platform booking cuci steam kendaraan terpercaya dengan layanan profesional di seluruh Indonesia.</p>
                    <h4 class="footer-subtitle">Keep Connected</h4>
                    <div class="social-links">
                        <a href="https://www.instagram.com/prismo_id?igsh=d3d4Mmo3NHBhbTBz" target="_blank" rel="noopener noreferrer" class="social-link">
                            <i class="fab fa-instagram"></i>
                            <span>Prismo_id</span>
                        </a>
                        <a href="https://www.facebook.com/share/1G9mzz7TSH/" target="_blank" rel="noopener noreferrer" class="social-link">
                            <i class="fab fa-facebook"></i>
                            <span>Prismo.id</span>
                        </a>
                        <a href="https://www.tiktok.com/@prismo_id?_r=1&_t=ZS-91git6qegI3" target="_blank" rel="noopener noreferrer" class="social-link">
                            <i class="fab fa-tiktok"></i>
                            <span>Prismo_id</span>
                        </a>
                    </div>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Platform</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Cari Mitra Terdekat</a></li>
                        <li><a href="{{ url('/register?tab=register') }}">Daftar Sebagai Customer</a></li>
                        <li><a href="{{ url('/register?tab=register') }}">Bergabung Jadi Mitra</a></li>
                        <li><a href="{{ url('/tentang') }}">Tentang Kami</a></li>
                        <li><a href="{{ url('/syarat-ketentuan') }}">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4 class="footer-title">Kontak</h4>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>0822-2767-1561</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>prismobook@gmail.com</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jl. Cikeas No. 123, Bogor Timur</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Prismo. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/nav.js') }}"></script>
    <script src="{{ asset('js/navhead.js') }}"></script>
</body>
</html>
