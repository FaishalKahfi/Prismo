<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - PRISMO</title>
    <meta name="description" content="Syarat dan Ketentuan penggunaan Platform Prismo untuk Customer dan Mitra penyedia jasa cuci steam.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --text-dark: #1e293b;
            --text-gray: #475569;
            --text-light: #64748b;
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Container */
        .container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 2rem 2rem;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            padding: 3rem 2.5rem;
            text-align: center;
        }

        .card-header h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .card-header p {
            font-size: 1.125rem;
            opacity: 0.95;
            font-weight: 300;
        }

        .card-body {
            padding: 3rem 2.5rem;
        }

        /* Typography */
        .greeting-section {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border-left: 4px solid var(--primary-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .greeting-section h3 {
            color: var(--primary-color);
            font-size: 1.25rem;
            margin-bottom: 0.75rem;
        }

        h2.section-title {
            color: var(--primary-color);
            font-size: 1.75rem;
            font-weight: 600;
            margin-top: 2.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--primary-color);
        }

        h3 {
            color: var(--text-dark);
            font-size: 1.35rem;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        h4 {
            color: var(--primary-dark);
            font-size: 1.125rem;
            font-weight: 600;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        h4::before {
            content: '';
            width: 4px;
            height: 1.125rem;
            background: var(--primary-color);
            border-radius: 2px;
        }

        p {
            color: var(--text-gray);
            margin-bottom: 1rem;
            line-height: 1.8;
        }

        strong {
            color: var(--text-dark);
            font-weight: 600;
        }

        em {
            color: var(--text-light);
            font-style: italic;
        }

        /* Special Sections */
        .highlight-box {
            background: #fef3c7;
            border-left: 4px solid var(--warning-color);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .highlight-box p {
            margin: 0;
        }

        .warning-box {
            background: #fee2e2;
            border-left: 4px solid var(--danger-color);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .warning-box strong {
            color: var(--danger-color);
        }

        .closing-statement {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border: 2px solid var(--primary-color);
            padding: 2rem;
            border-radius: 12px;
            margin: 3rem 0 2rem;
            text-align: center;
        }

        .closing-statement p {
            font-size: 1.125rem;
            color: var(--primary-dark);
            margin: 0;
        }

        /* Contact Section */
        .contact-section {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .contact-section h3 {
            color: var(--primary-color);
            margin-top: 0;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .contact-section h3 i {
            font-size: 1.5rem;
        }

        .contact-section ul {
            list-style: none;
            padding: 0;
        }

        .contact-section li {
            padding: 0.75rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-gray);
            border-bottom: 1px solid var(--border-color);
        }

        .contact-section li:last-child {
            border-bottom: none;
        }

        .contact-section li i {
            color: var(--primary-color);
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .footer-text {
            text-align: center;
            color: var(--text-light);
            font-size: 0.9rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                padding: 1rem 1.5rem;
            }

            .logo {
                font-size: 1.25rem;
            }

            .logo img {
                width: 32px;
                height: 32px;
            }

            .container {
                padding: 0 1rem 1rem;
                margin: 1rem auto;
            }

            .card-header {
                padding: 2rem 1.5rem;
            }

            .card-header h1 {
                font-size: 1.75rem;
            }

            .card-header p {
                font-size: 1rem;
            }

            .card-body {
                padding: 2rem 1.5rem;
            }

            h2.section-title {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.25rem;
            }

            h4 {
                font-size: 1.0625rem;
            }

            .back-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }

            .header,
            .back-btn {
                display: none;
            }

            .container {
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            .content-card {
                box-shadow: none;
                border: 1px solid #e2e8f0;
            }

            .card-header {
                background: white !important;
                color: var(--text-dark) !important;
                border-bottom: 2px solid var(--primary-color);
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="{{ url('/') }}" class="logo">
                <img src="{{ asset('images/Icon-prismo.png') }}" alt="Prismo">
                <span>PRISMO</span>
            </a>
            <a href="{{ url('/login#register') }}" class="back-btn">
                <i class="ph ph-arrow-left"></i>
                Kembali
            </a>
        </div>
    </header>

    <div class="container">
        <div class="content-card">
            <div class="card-header">
                <h1>Syarat & Ketentuan</h1>
                <p>Platform Prismo untuk Customer dan Mitra</p>
            </div>

            <div class="card-body">
                <div class="greeting-section">
                    <h3><i class="ph ph-hand-waving"></i> Salam dari Prismo!</h3>
                    <p>Halo, perkenalkan kami Prismo adalah platform penyedia layanan teknologi yang memfasilitasi pemesanan (booking) jasa cuci steam, menghubungkan pengguna ("Customer") dengan penyedia jasa cuci steam ("Mitra"). Dengan mengakses dan menggunakan Layanan Prismo, Pelanggan dan Mitra menyatakan setuju untuk terikat pada Ketentuan ini secara keseluruhan.</p>
                </div>

                <h2 class="section-title">1. Ketentuan Umum Layanan Platform</h2>

                <h4>Penerimaan Ketentuan</h4>
                <p>Pelanggan dan Mitra dianggap telah membaca, memahami, dan menyetujui seluruh isi ketentuan ini sejak pembuatan akun, pengaksesan atau penggunaan Layanan Prismo.</p>

                <h4>Sifat Layanan Prismo</h4>
                <p>Prismo bertindak sebagai penyedia platform teknologi dan mediator. Prismo bukan penyedia jasa cuci steam secara langsung. Jasa cuci steam sepenuhnya disediakan oleh pihak mitra.</p>

                <h4>Perubahan Syarat & Ketentuan</h4>
                <p>Prismo (selaku penyedia platform) berhak mengubah, menambah, atau mengurangi syarat & ketentuan ini sewaktu-waktu. Pelanggan dan Mitra bertanggung jawab untuk meninjau perubahan syarat & ketentuan secara berkala.</p>

                <h4>Hukum yang Berlaku</h4>
                <p>Syarat & Ketentuan ini tunduk pada hukum dan peraturan yang berlaku di Negara Republik Indonesia.</p>

                <h2 class="section-title">2. Akun dan Keamanan</h2>

                <h4>Persyaratan Pengguna</h4>
                <p>Pengguna wajib berusia minimal 18 (delapan belas) tahun atau menggunakan layanan dengan pengawasan orang tua/wali yang sah.</p>

                <h4>Tanggung Jawab Akun</h4>
                <p>Customer dan Mitra bertanggung jawab penuh atas kerahasiaan data akun, termasuk kata sandi dan seluruh aktivitas yang terjadi dibawah akun masing-masing.</p>

                <h4>Larangan Penggunaan</h4>
                <p>Pengguna dilarang menggunakan platform Prismo untuk tujuan yang melanggar hukum, penipuan, merusak sistem atau merugikan reputasi Prismo.</p>

                <h2 class="section-title">3. Ketentuan Pemesanan (Booking), Harga dan Pembayaran</h2>

                <h4>Pemesanan</h4>
                <p>Customer dapat melakukan pemesanan (booking) jasa cuci steam dari mitra melalui platform Prismo.</p>

                <h4>Konfirmasi</h4>
                <p>Pemesanan dianggap sah setelah Customer menerima konfirmasi resmi booking melalui Platform Prismo dan surat elektronik (email).</p>

                <h4>Harga</h4>
                <p>Harga layanan sepenuhnya ditetapkan oleh Mitra dan ditampilkan oleh Platform.</p>

                <div class="highlight-box">
                    <h4 style="margin-top: 0;">💳 Pembayaran (QRIS Prismo)</h4>
                    <p><strong>Pembayaran total biaya wajib dilakukan melalui metode QRIS Prismo yang disediakan.</strong> Sistem pembayaran terpusat ini bertujuan untuk mengamankan transaksi, terutama dalam kasus pembatalan sepihak oleh pelanggan.</p>
                </div>

                <div class="highlight-box">
                    <h4 style="margin-top: 0;">🎉 Kebijakan Biaya (Periode Awal Kemitraan)</h4>
                    <p><strong>Sebagai bentuk dukungan, Prismo tidak memungut komisi atau biaya layanan dari pelanggan maupun mitra selama periode awal yang telah ditentukan.</strong></p>
                </div>

                <h4>Penarikan Dana Mitra</h4>
                <p>Mitra dapat mengajukan penarikan dana hasil transaksi yang telah diselesaikan kapan saja (on-demand) melalui sistem yang disediakan oleh Prismo.</p>

                <h2 class="section-title">4. Pembatalan</h2>

                <h4>Pembatalan oleh Customer</h4>
                <p>Platform Prismo berhak memberlakukan biaya pembatalan jika Customer membatalkan booking dalam jangka waktu yang terlalu dekat dengan jadwal yang disepakati.</p>

                <h4>Pembatalan oleh Mitra</h4>
                <p>Jika Mitra membatalkan pemesanan, Prismo akan berupaya maksimal untuk memberikan notifikasi segera kepada Customer dan memfasilitasi penjadwalan ulang atau pencarian Mitra alternatif.</p>

                <h2 class="section-title">5. Batasan dan Distribusi Tanggung Jawab</h2>

                <h3>A. Tanggung Jawab Mitra (Penyedia Jasa)</h3>

                <h4>Kualitas Layanan</h4>
                <p>Mitra bertanggung jawab penuh atas kualitas layanan cuci steam yang disediakan.</p>

                <h4>Klaim Kerusakan</h4>
                <p>Mitra bertanggung jawab penuh untuk menangani dan menyelesaikan klaim atau keluhan terkait kerusakan (seperti goresan atau kerusakan komponen lainnya) yang timbul pada kendaraan Customer selama proses pencucian.</p>

                <h4>Ketersediaan</h4>
                <p>Mitra wajib menghormati dan memenuhi booking yang telah dikonfirmasi sesuai jadwal dan spesifikasi layanan.</p>

                <h3>B. Batasan Tanggung Jawab Platform Prismo</h3>

                <div class="warning-box">
                    <h4 style="margin-top: 0;">⚠️ Tidak Bertanggung Jawab atas Kerugian Langsung</h4>
                    <p><strong>Prismo tidak bertanggung jawab atas kerugian, kerusakan atau hal tak terduga yang terjadi pada objek pencucian (kendaraan) selama proses penyediaan jasa.</strong> Segala risiko yang timbul adalah sepenuhnya tanggung jawab antara Customer dan Mitra.</p>
                </div>

                <h4>Akurasi Informasi</h4>
                <p>Platform berupaya menyajikan informasi Mitra secara akurat, namun tidak menjamin bahwa semua informasi (termasuk ketersediaan dan jam operasional) selalu mutakhir.</p>

                <h4>Fungsi Komunikasi</h4>
                <p>Platform hanya memfasilitasi komunikasi selama transaksi sedang berlangsung antara Customer dan Mitra.</p>

                <h2 class="section-title">6. Penyelesaian Keluhan dan Masalah</h2>

                <h4>Keluhan Layanan Cuci Steam</h4>
                <p>Keluhan terkait kualitas atau pelaksanaan layanan cuci steam harus ditujukan langsung kepada pihak Mitra yang bersangkutan. Prismo dapat membantu memfasilitasi penyampaian keluhan tersebut.</p>

                <h4>Masalah Transaksi/Pembayaran</h4>
                <p>Masalah terkait pembayaran melalui QRIS Prismo dapat diajukan kepada layanan Pelanggan Prismo.</p>

                <h2 class="section-title">7. Ketentuan Khusus Mitra (Penyedia Jasa)</h2>

                <h4>Perjanjian Kemitraan</h4>
                <p>Mitra wajib tunduk pada Perjanjian Kemitraan terpisah yang mengatur secara rinci proses pembayaran, penarikan dana dan standar operasional yang telah ditetapkan oleh Prismo.</p>

                <h4>Standar Kualitas</h4>
                <p>Mitra wajib menjaga standar kualitas layanan yang ditetapkan oleh Platform. Pelanggaran terhadap standar ini dapat menyebabkan penangguhan atau pemutusan kerja sama kemitraan.</p>

                <div class="closing-statement">
                    <p><strong>Dengan ini, Customer dan Mitra menyatakan menyetujui, memahami dan tunduk pada seluruh Syarat & Ketentuan yang ditetapkan oleh Prismo.</strong></p>
                </div>

                <div class="contact-section">
                    <h3><i class="ph ph-phone"></i> Kontak Kami</h3>
                    <ul>
                        <li>
                            <i class="ph ph-envelope"></i>
                            <div>
                                <strong>Email:</strong> support@prismo.com
                            </div>
                        </li>
                        <li>
                            <i class="ph-fill ph-whatsapp-logo"></i>
                            <div>
                                <strong>WhatsApp:</strong> +62 822-2767-1561
                            </div>
                        </li>
                        <li>
                            <i class="ph ph-map-pin"></i>
                            <div>
                                <strong>Alamat:</strong> Jl. Cikeas No. 123, Bogor Timur, Jawa Barat
                            </div>
                        </li>
                    </ul>
                </div>

                <p class="footer-text"><em>Terakhir diperbarui: 15 Desember 2025</em></p>
            </div>
        </div>
    </div>
</body>
</html>
