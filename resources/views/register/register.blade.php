<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Prismo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Icon-prismo.png') }}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}?v={{ time() }}">
</head>

<body>
    <!-- SIGN UP -->
    <div class="form-container sign-up">
        <!-- Pilihan Customer/Mitra di kanan atas -->
        <div class="customer-type-toggle">
            <div class="toggle-buttons">
                <button type="button" class="type-btn active" data-type="customer">Customer</button>
                <button type="button" class="type-btn" data-type="mitra">Mitra</button>
            </div>
        </div>

        <form id="signup-form">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Prismo Logo" class="logo">
            </div>
            <h1 class="form-title">Daftar</h1>

            <div class="input-container email">
                <input type="email" name="email" placeholder="Email" autocomplete="email" required>
            </div>

            <div class="input-container password">
                <input type="password" id="signup-password" name="password" class="password-input" placeholder="Password" required>
                <i class="ph ph-eye toggle-password"></i>
            </div>

            <div class="input-container password">
                <input type="password" name="confirmPassword" class="password-input" placeholder="Confirm Password" required>
                <i class="ph ph-eye toggle-password"></i>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">Dengan membuat akun, Anda Menyetujui <a href="{{ route('terms') }}" target="_blank">Syarat & Ketentuan</a></label>
            </div>

            <button type="submit" class="main-btn">
                <span class="btn-text">Daftar</span>
                <div class="loading-spinner">
                    <i class="ph ph-circle-notch"></i>
                </div>
            </button>

            <div class="divider">
                <span>or</span>
            </div>

            <button type="button" class="google-btn" onclick="if(!document.getElementById('terms').checked){alert('Harap centang Syarat & Ketentuan terlebih dahulu');return false;}var role=document.querySelector('.type-btn.active')?.dataset?.type||'customer';window.location.href='/auth/google?action=register&role='+role;">
                <img src="{{ asset('images/google.png') }}" alt="Google" class="google-icon">
                Lanjut dengan Google
            </button>

            <div class="login-link">
                <small>Sudah punya akun?</small>
                <a href="#" id="login">Masuk</a>
            </div>
        </form>
    </div>

    <!-- SIGN IN -->
    <div class="form-container sign-in">
        <form id="signin-form">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Prismo Logo" class="logo">
            </div>
            <h1 class="form-title">Masuk</h1>

            <div class="input-container email">
                <input type="email" name="email" placeholder="Email" autocomplete="email" required>
            </div>

            <div class="input-container password">
                <input type="password" name="password" class="password-input" placeholder="Password" required>
                <i class="ph ph-eye toggle-password"></i>
            </div>

            <div class="forgot-password-container">
                <button type="button" id="forgot-password">Lupa Password?</button>
            </div>

            <button type="submit" class="main-btn">
                <span class="btn-text">Masuk</span>
                <div class="loading-spinner">
                    <i class="ph ph-circle-notch"></i>
                </div>
            </button>

            <div class="divider">
                <span>or</span>
            </div>

            <button type="button" class="google-btn" onclick="window.location.href='{{ url('/auth/google?action=login') }}'">
                <img src="{{ asset('images/google.png') }}" alt="Google" class="google-icon">
                Lanjut dengan Google
            </button>

            <div class="login-link">
                <small>Belum punya akun?</small>
                <a href="#" id="register">Buat Akun</a>
            </div>
        </form>
    </div>

    <!-- GAMBAR PANEL -->
    <div class="toggle">
        <div class="image-container"></div>
    </div>

    <!-- MODAL LUPA PASSWORD -->
    <div class="forgot-password-modal" id="forgotPasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Lupa Password?</h2>
                <button type="button" class="close-modal" id="closeForgotPasswordModal">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="modal-logo-container">
                    <img src="{{ asset('images/lupapassword.png') }}" alt="Prismo Logo" class="modal-logo">
                </div>

                <p class="modal-description">Masukkan email Anda dan kami akan mengirimkan link untuk reset password</p>

                <form class="forgot-password-form" id="forgot-password-form">
                    <div class="input-container">
                        <input type="email" name="email" placeholder="Email" autocomplete="email" required>
                    </div>

                    <button type="submit" class="main-btn">
                        <span class="btn-text">Kirim link reset password</span>
                        <div class="loading-spinner">
                            <i class="ph ph-circle-notch"></i>
                        </div>
                    </button>
                </form>

                <div class="confirmation-message">
                    <div class="success-icon">
                        <i class="ph ph-check-circle"></i>
                    </div>
                    <h3>Email Terkirim!</h3>
                    <p>Kami telah mengirimkan link reset password ke email Anda. Silakan cek inbox atau folder spam Anda.</p>
                    <button type="button" class="main-btn" id="closeAfterSuccess">Tutup</button>
                </div>
            </div>

            <div class="modal-footer">
                <div class="support-links">
                    <p>Butuh Bantuan?</p>
                    <div class="support-buttons">
                        <a href="/support">Hubungi Support</a>
                        <span>|</span>
                        <a href="/faq">FAQ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SYARAT & KETENTUAN -->
    <div class="terms-modal" id="termsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Syarat & Ketentuan</h2>
                <button type="button" class="close-modal" id="closeTermsModal">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="terms-content">
                    <h2 class="main-title">Syarat & Ketentuan Platform Prismo</h2>
                    <p class="subtitle">Untuk Customer dan Mitra</p>

                    <div class="greeting-section">
                        <h3>Salam dari Prismo!</h3>
                        <p>Halo, perkenalkan kami Prismo adalah platform penyedia layanan teknologi yang memfasilitasi pemesanan (booking) jasa cuci steam, menghubungkan pengguna ("Customer") dengan penyedia jasa cuci steam ("Mitra"). Dengan mengakses dan menggunakan Layanan Prismo, Pelanggan dan Mitra menyatakan setuju untuk terikat pada Ketentuan ini secara keseluruhan.</p>
                    </div>

                    <h3>1. Ketentuan Umum Layanan Platform</h3>

                    <h4>Penerimaan Ketentuan</h4>
                    <p>Pelanggan dan Mitra dianggap telah membaca, memahami, dan menyetujui seluruh isi ketentuan ini sejak pembuatan akun, pengaksesan atau penggunaan Layanan Prismo.</p>

                    <h4>Sifat Layanan Prismo</h4>
                    <p>Prismo bertindak sebagai penyedia platform teknologi dan mediator. Prismo bukan penyedia jasa cuci steam secara langsung. Jasa cuci steam sepenuhnya disediakan oleh pihak mitra.</p>

                    <h4>Perubahan Syarat & Ketentuan</h4>
                    <p>Prismo (selaku penyedia platform) berhak mengubah, menambah, atau mengurangi syarat & ketentuan ini sewaktu-waktu. Pelanggan dan Mitra bertanggung jawab untuk meninjau perubahan syarat & ketentuan secara berkala.</p>

                    <h4>Hukum yang Berlaku</h4>
                    <p>Syarat & Ketentuan ini tunduk pada hukum dan peraturan yang berlaku di Negara Republik Indonesia.</p>

                    <h3>2. Akun dan Keamanan</h3>

                    <h4>Persyaratan Pengguna</h4>
                    <p>Pengguna wajib berusia minimal 18 (delapan belas) tahun atau menggunakan layanan dengan pengawasan orang tua/wali yang sah.</p>

                    <h4>Tanggung Jawab Akun</h4>
                    <p>Customer dan Mitra bertanggung jawab penuh atas kerahasiaan data akun, termasuk kata sandi dan seluruh aktivitas yang terjadi dibawah akun masing-masing.</p>

                    <h4>Larangan Penggunaan</h4>
                    <p>Pengguna dilarang menggunakan platform Prismo untuk tujuan yang melanggar hukum, penipuan, merusak sistem atau merugikan reputasi Prismo.</p>

                    <h3>3. Ketentuan Pemesanan (Booking), Harga dan Pembayaran</h3>

                    <h4>Pemesanan</h4>
                    <p>Customer dapat melakukan pemesanan (booking) jasa cuci steam dari mitra melalui platform Prismo.</p>

                    <h4>Konfirmasi</h4>
                    <p>Pemesanan dianggap sah setelah Customer menerima konfirmasi resmi booking melalui Platform Prismo dan surat elektronik (email).</p>

                    <h4>Harga</h4>
                    <p>Harga layanan sepenuhnya ditetapkan oleh Mitra dan ditampilkan oleh Platform.</p>

                    <h4>💳 Pembayaran (QRIS Prismo)</h4>
                    <p>Pembayaran total biaya wajib dilakukan melalui metode QRIS Prismo yang disediakan. Sistem pembayaran terpusat ini bertujuan untuk mengamankan transaksi, terutama dalam kasus pembatalan sepihak oleh pelanggan.</p>

                    <h4>🎉 Kebijakan Biaya (Periode Awal Kemitraan)</h4>
                    <p>Sebagai bentuk dukungan, Prismo tidak memungut komisi atau biaya layanan dari pelanggan maupun mitra selama periode awal yang telah ditentukan.</p>

                    <h4>Penarikan Dana Mitra</h4>
                    <p>Mitra dapat mengajukan penarikan dana hasil transaksi yang telah diselesaikan kapan saja (on-demand) melalui sistem yang disediakan oleh Prismo.</p>

                    <h3>4. Pembatalan</h3>

                    <h4>Pembatalan oleh Customer</h4>
                    <p>Platform Prismo berhak memberlakukan biaya pembatalan jika Customer membatalkan booking dalam jangka waktu yang terlalu dekat dengan jadwal yang disepakati.</p>

                    <h4>Pembatalan oleh Mitra</h4>
                    <p>Jika Mitra membatalkan pemesanan, Prismo akan berupaya maksimal untuk memberikan notifikasi segera kepada Customer dan memfasilitasi penjadwalan ulang atau pencarian Mitra alternatif.</p>

                    <h3>5. Batasan dan Distribusi Tanggung Jawab</h3>

                    <h4>A. Tanggung Jawab Mitra (Penyedia Jasa)</h4>

                    <p><strong>Kualitas Layanan</strong></p>
                    <p>Mitra bertanggung jawab penuh atas kualitas layanan cuci steam yang disediakan.</p>

                    <p><strong>Klaim Kerusakan</strong></p>
                    <p>Mitra bertanggung jawab penuh untuk menangani dan menyelesaikan klaim atau keluhan terkait kerusakan (seperti goresan atau kerusakan komponen lainnya) yang timbul pada kendaraan Customer selama proses pencucian.</p>

                    <p><strong>Ketersediaan</strong></p>
                    <p>Mitra wajib menghormati dan memenuhi booking yang telah dikonfirmasi sesuai jadwal dan spesifikasi layanan.</p>

                    <h4>B. Batasan Tanggung Jawab Platform Prismo</h4>

                    <p><strong>⚠️ Tidak Bertanggung Jawab atas Kerugian Langsung</strong></p>
                    <p>Prismo tidak bertanggung jawab atas kerugian, kerusakan atau hal tak terduga yang terjadi pada objek pencucian (kendaraan) selama proses penyediaan jasa. Segala risiko yang timbul adalah sepenuhnya tanggung jawab antara Customer dan Mitra.</p>

                    <p><strong>Akurasi Informasi</strong></p>
                    <p>Platform berupaya menyajikan informasi Mitra secara akurat, namun tidak menjamin bahwa semua informasi (termasuk ketersediaan dan jam operasional) selalu mutakhir.</p>

                    <p><strong>Fungsi Komunikasi</strong></p>
                    <p>Platform hanya memfasilitasi komunikasi selama transaksi sedang berlangsung antara Customer dan Mitra.</p>

                    <h3>6. Penyelesaian Keluhan dan Masalah</h3>

                    <h4>Keluhan Layanan Cuci Steam</h4>
                    <p>Keluhan terkait kualitas atau pelaksanaan layanan cuci steam harus ditujukan langsung kepada pihak Mitra yang bersangkutan. Prismo dapat membantu memfasilitasi penyampaian keluhan tersebut.</p>

                    <h4>Masalah Transaksi/Pembayaran</h4>
                    <p>Masalah terkait pembayaran melalui QRIS Prismo dapat diajukan kepada layanan Pelanggan Prismo.</p>

                    <h3>7. Ketentuan Khusus Mitra (Penyedia Jasa)</h3>

                    <h4>Perjanjian Kemitraan</h4>
                    <p>Mitra wajib tunduk pada Perjanjian Kemitraan terpisah yang mengatur secara rinci proses pembayaran, penarikan dana dan standar operasional yang telah ditetapkan oleh Prismo.</p>

                    <h4>Standar Kualitas</h4>
                    <p>Mitra wajib menjaga standar kualitas layanan yang ditetapkan oleh Platform. Pelanggaran terhadap standar ini dapat menyebabkan penangguhan atau pemutusan kerja sama kemitraan.</p>

                    <div class="closing-statement">
                        <p><strong>Dengan ini, Customer dan Mitra menyatakan menyetujui, memahami dan tunduk pada seluruh Syarat & Ketentuan yang ditetapkan oleh Prismo.</strong></p>
                    </div>

                    <div class="contact-section">
                        <h3>Kontak Kami</h3>
                        <ul>
                            <li><strong>Email:</strong> support@prismo.com</li>
                            <li><strong>WhatsApp:</strong> +62 822-2767-1561</li>
                            <li><strong>Alamat:</strong> Jl. Cikeas No. 123, Bogor Timur, Jawa Barat</li>
                        </ul>
                    </div>

                    <p class="footer-text"><em>Terakhir diperbarui: 15 Desember 2025</em></p>
                </div>
            </div>
        </div>
    </div>

    <!-- NOTIFICATION TOAST -->
    <div class="notification-toast" id="notificationToast">
        <div class="toast-content">
            <div class="toast-icon"></div>
            <div class="toast-message"></div>
            <button class="toast-close">
                <i class="ph ph-x"></i>
            </button>
        </div>
    </div>

    <script src="{{ asset('js/register.js') }}?v={{ time() }}"></script>
</body>

</html>
