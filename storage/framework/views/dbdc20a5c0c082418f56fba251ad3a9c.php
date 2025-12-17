<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="authenticated" content="false">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- SEO Meta Tags -->
    <title>PRISMO - Platform Booking Layanan Jasa Online Terpercaya | Laundry, Cuci Motor, Service AC</title>
    <meta name="description" content="PRISMO adalah platform booking layanan jasa online terpercaya di Indonesia. Temukan jasa laundry, cuci motor, dan service AC terdekat dengan harga terjangkau. Booking mudah, cepat, dan aman!">
    <meta name="keywords" content="PRISMO, booking jasa online, laundry online Indonesia, cuci motor terdekat, service AC murah, platform jasa, booking laundry, jasa cuci motor, mitra jasa terpercaya">
    <meta name="author" content="PRISMO">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo e(url('/')); ?>">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="PRISMO - Platform Booking Layanan Jasa Online Terpercaya">
    <meta property="og:description" content="Booking layanan jasa laundry, cuci motor, dan service AC dengan mudah. Mitra terpercaya, harga transparan, dan proses cepat.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url('/')); ?>">
    <meta property="og:image" content="<?php echo e(asset('images/logo.png')); ?>">
    <meta property="og:site_name" content="PRISMO">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="PRISMO - Platform Booking Layanan Jasa Online">
    <meta name="twitter:description" content="Booking layanan jasa laundry, cuci motor, dan service AC dengan mudah dan terpercaya">
    <meta name="twitter:image" content="<?php echo e(asset('images/logo.png')); ?>">

    <!-- Additional SEO Tags -->
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Indonesia">
    <meta name="language" content="Indonesian">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Structured Data (JSON-LD) for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "PRISMO",
        "description": "Platform booking layanan jasa online terpercaya di Indonesia untuk laundry, cuci motor, dan service AC",
        "url": "<?php echo e(url('/')); ?>",
        "logo": "<?php echo e(asset('images/logo.png')); ?>",
        "contactPoint": {
            "@type": "ContactPoint",
            "contactType": "Customer Service",
            "availableLanguage": ["Indonesian"]
        },
        "sameAs": [
            "https://prismobook.blogspot.com"
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "PRISMO",
        "url": "<?php echo e(url('/')); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo e(url('/customer/dashboard')); ?>?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
</head>
<body>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                     <a href="#">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Prismo Logo">
                    </a>
                </div>
                <nav class="nav" id="mainNav">
                    <ul>
                        <li><a href="<?php echo e(url('/')); ?>" class="active">Beranda</a></li>
                        <li><a href="<?php echo e(url('/tentang')); ?>">Tentang Kami</a></li>
                        <li><a href="https://prismobook.blogspot.com/2025/11/tren-kebersihan-kendaraan-meningkat" target="_blank">Artikel</a></li>

                    </ul>
                </nav>
                <div class="header-buttons">
                    <button class="btn-outline" onclick="window.location.href='<?php echo e(url('/login?tab=login')); ?>'">Masuk</button>
                    <button class="btn-primary" onclick="window.location.href='<?php echo e(url('/register?tab=register')); ?>'">Daftar</button>
                </div>
                <button class="mobile-menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                    ☰
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-image-wrapper">
            <img src="<?php echo e(asset('images/cvdash1.png')); ?>?v=<?php echo e(time()); ?>" alt="Cars" class="hero-cars-img">
        </div>
    </section>

    <!-- Services Section -->
    <section class="services">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Layanan Unggulan Kami</h2>
                <p class="section-subtitle">Nikmati pilihan layanan profesional kami untuk memenuhi kebutuhan perawatan kendaraan Anda</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon1">
                    </div>
                    <h3 class="service-title">Cuci Mobil</h3>
                    <p class="service-description">Layanan cuci mobil detail dengan peralatan modern dan produk berkualitas tinggi untuk hasil yang sempurna.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon2">
                    </div>
                    <h3 class="service-title">Detailing</h3>
                    <p class="service-description">Jasa detailing interior dan eksterior profesional untuk mengembalikan tampilan kendaraan Anda seperti baru.</p>
                </div>
                <div class="service-card">
                    <div class="service-icon3">
                    </div>
                    <h3 class="service-title">Instalasi Aksesoris</h3>
                    <p class="service-description">Pemasangan berbagai aksesoris kendaraan oleh teknisi berpengalaman dengan garansi kualitas terjamin.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Testimoni Pelanggan</h2>
                <p class="section-subtitle">Dengarkan kisah nyata dari pelanggan kami yang telah merasakan kepuasan layanan kami</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <img src="https://i.pravatar.cc/150?img=12" alt="Budi Santoso">
                    </div>
                    <div class="testimonial-content">
                        <h4 class="testimonial-name">Budi Santoso</h4>
                        <p class="testimonial-position">Pengusaha</p>
                        <p class="testimonial-text">"Pelayanan yang sangat memuaskan! Mobil saya terlihat seperti baru lagi. Tim Prisma sangat profesional dan ramah. Pasti akan kembali lagi!"</p>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <img src="https://i.pravatar.cc/150?img=45" alt="Siti Nurhaliza">
                    </div>
                    <div class="testimonial-content">
                        <h4 class="testimonial-name">Siti Nurhaliza</h4>
                        <p class="testimonial-position">Ibu Rumah Tangga</p>
                        <p class="testimonial-text">"Booking sangat mudah dan praktis! Hasilnya pun memuaskan. Harga yang ditawarkan juga sangat terjangkau. Sangat direkomendasikan!"</p>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-avatar">
                        <img src="https://i.pravatar.cc/150?img=33" alt="Ahmad Rizki">
                    </div>
                    <div class="testimonial-content">
                        <h4 class="testimonial-name">Ahmad Rizki</h4>
                        <p class="testimonial-position">Karyawan Swasta</p>
                        <p class="testimonial-text">"Sangat puas dengan layanan detailing. Interior mobil saya kembali bersih dan wangi. Prosesnya cepat dan hasilnya memuaskan!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Kenapa Pilih Prismo?</h2>
                <p class="section-subtitle">Platform terpercaya untuk booking steam mobil dengan berbagai keunggulan</p>
            </div>
            <div class="features-grid">
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-number">500+</h3>
                        <p class="feature-label">Mitra Langganan</p>
                        <p class="feature-desc">Tersebar di berbagai kota</p>
                    </div>
                </div>
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="fas fa-user-group"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-number">10K</h3>
                        <p class="feature-label">Customers Terdaftar</p>
                        <p class="feature-desc">Pelanggan setia kami</p>
                    </div>
                </div>
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="fas fa-location-dot"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-number">50+</h3>
                        <p class="feature-label">Kota Terdekat</p>
                        <p class="feature-desc">Jangkauan layanan luas</p>
                    </div>
                </div>
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-number">Terpercaya</h3>
                        <p class="feature-label">& Aman</p>
                        <p class="feature-desc">Semua mitra terverifikasi dengan standar kualitas tinggi</p>
                    </div>
                </div>
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-number">Booking</h3>
                        <p class="feature-label">Fleksibel</p>
                        <p class="feature-desc">Atur waktu booking sesuai kebutuhan Anda</p>
                    </div>
                </div>
                <div class="feature-box">
                    <div class="feature-icon-wrapper">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                    <div class="feature-content">
                        <h3 class="feature-number">Cepat</h3>
                        <p class="feature-label">& Efisien</p>
                        <p class="feature-desc">Tim profesional kami siap melayani dengan cepat</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="location">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Titik Mitra Tersebar di JABOTABEK</h2>
                <p class="section-subtitle">Prismo Akan Terus Melebarkan Sayap Agar Dapat Melayani Anda Lebih Dekat, Dimanapun Dan Kapanpun.</p>
            </div>
            <div class="map-container">
                <svg viewBox="0 0 1000 400" class="map-svg">

                </svg>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title">Gabung bersama Prismo sekarang!</h2>
                <p class="cta-subtitle">Nikmati kemudahan, kecepatan, dan kualitas layanan booking steam mobil terkemuka. Mari bergabung menjadi bagian dari keluarga besar kami!</p>
                <button class="btn-cta" onclick="window.location.href='<?php echo e(url('/register?tab=register')); ?>'">Gabung Sekarang</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-column">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Prismo Logo" class="footer-logo-img">
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
                    <li><a href="<?php echo e(url('/')); ?>">Cari Mitra Terdekat</a></li>
                    <li><a href="<?php echo e(url('/register?tab=register')); ?>">Daftar Sebagai Customer</a></li>
                    <li><a href="<?php echo e(url('/register?tab=register')); ?>">Bergabung Jadi Mitra</a></li>
                    <li><a href="<?php echo e(url('/tentang')); ?>">Tentang Kami</a></li>
                    <li><a href="<?php echo e(url('/syarat-ketentuan')); ?>">Syarat & Ketentuan</a></li>
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

     <!-- JavaScript di akhir body -->
    <script>
        // Set active nav berdasarkan URL saat halaman load
        window.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop() || 'lp=.html';
            const navLinks = document.querySelectorAll('.nav a');

            navLinks.forEach(link => {
                // Hapus semua active class
                link.classList.remove('active');

                // Ambil nama file dari href
                const linkPage = link.getAttribute('href');

                // Tambahkan active ke link yang sesuai
                if (linkPage === currentPage) {
                    link.classList.add('active');
                }

            });
        });


    </script>
    <script src="<?php echo e(asset('js/nav.js')); ?>"></script>
    <script src="<?php echo e(asset('js/navhead.js')); ?>"></script>
    <script src="<?php echo e(asset('js/prevent-back.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\Faishal\Documents\prismo - Copy\resources\views/landingpage/lp.blade.php ENDPATH**/ ?>