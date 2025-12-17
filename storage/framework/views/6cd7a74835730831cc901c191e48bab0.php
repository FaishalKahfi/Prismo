<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="user-role" content="mitra">
    <meta name="authenticated" content="true">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- SEO Meta Tags -->
    <title>Dashboard Mitra - PRISMO | Kelola Layanan Jasa Anda</title>
    <meta name="description" content="Dashboard Mitra PRISMO - Kelola pesanan, antrian, saldo, dan review layanan jasa Anda. Platform terpercaya untuk penyedia jasa laundry, cuci motor, dan service AC.">
    <meta name="keywords" content="dashboard mitra, kelola layanan jasa, PRISMO, laundry online, cuci motor, service AC, booking online, manajemen pesanan">
    <meta name="author" content="PRISMO">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Dashboard Mitra - PRISMO">
    <meta property="og:description" content="Kelola layanan jasa Anda dengan mudah melalui Dashboard Mitra PRISMO">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url('/dashboard-mitra')); ?>">
    <meta property="og:image" content="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <meta property="og:site_name" content="PRISMO">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Dashboard Mitra - PRISMO">
    <meta name="twitter:description" content="Kelola layanan jasa Anda dengan mudah melalui Dashboard Mitra PRISMO">
    <meta name="twitter:image" content="<?php echo e(asset('images/Icon-prismo.png')); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>?v=20251214041932">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Force dark theme untuk notification panel - Enhanced */
        .notification-panel,
        #notifPanel,
        #notificationPanel {
            background: #0a0e1a !important;
            border: 1px solid rgba(96, 165, 250, 0.2) !important;
        }

        .notification-panel-header,
        #notifPanel .notification-panel-header,
        #notificationPanel .notification-panel-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            border-bottom: 1px solid rgba(96, 165, 250, 0.15) !important;
        }

        .notification-panel-header h3,
        #notifPanel h3,
        #notificationPanel h3 {
            color: #e0f2fe !important;
        }

        .notification-list,
        #notificationList {
            background: #0a0e1a !important;
        }

        .panel-item {
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(71, 85, 105, 0.3) !important;
            border-radius: 10px !important;
        }

        .panel-item:hover {
            background: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(96, 165, 250, 0.3) !important;
        }

        .panel-item.unread {
            background: rgba(30, 58, 138, 0.3) !important;
            border-color: rgba(96, 165, 250, 0.4) !important;
        }

        .panel-item.unread::before {
            background: linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%) !important;
            width: 3px !important;
            box-shadow: 0 0 10px rgba(96, 165, 250, 0.5) !important;
        }

        .panel-title {
            color: #e0f2fe !important;
        }

        .panel-description {
            color: #94a3b8 !important;
        }

        .panel-time {
            color: #64748b !important;
        }

        .panel-time::before {
            opacity: 0.7 !important;
        }

        .mark-all-read-btn,
        .delete-all-btn {
            background: rgba(96, 165, 250, 0.15) !important;
            color: #93c5fd !important;
            border: 1px solid rgba(96, 165, 250, 0.25) !important;
            border-radius: 8px !important;
            padding: 7px 14px !important;
            font-size: 11.5px !important;
            font-weight: 600 !important;
        }

        .delete-all-btn {
            background: rgba(248, 113, 113, 0.15) !important;
            color: #fca5a5 !important;
            border-color: rgba(248, 113, 113, 0.25) !important;
        }

        .loading-state i,
        .empty-state i {
            color: #60a5fa !important;
        }

        .empty-state h4 {
            color: #94a3b8 !important;
        }

        .empty-state p,
        .loading-state p {
            color: #64748b !important;
        }

        /* Override any white backgrounds */
        div[style*="background: white"],
        div[style*="background: #fff"],
        div[style*="background: #ffffff"] {
            background: #0a0e1a !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="header__content">
                <div class="header__left">
                    <div class="header__brand">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="PRISMO" class="logo" width="120" height="40">
                    </div>

                    <nav class="nav nav--main" aria-label="Navigasi utama">
                        <a href="<?php echo e(url('/dashboard-mitra')); ?>" class="nav__item nav__item--active" data-page="dashboard">
                            Dashboard
                        </a>
                        <a href="<?php echo e(url('/mitra/saldo/saldo')); ?>" class="nav__item" data-page="saldo">
                            Saldo
                        </a>
                        <a href="<?php echo e(url('/mitra/antrian/antrian')); ?>" class="nav__item" data-page="antrian">
                            Antrian
                        </a>
                        <a href="<?php echo e(url('/mitra/review/review')); ?>" class="nav__item" data-page="review">
                            Review
                        </a>
                    </nav>
                </div>

                <button class="notification-btn" id="notifBtn" aria-label="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" style="display: none;">0</span>
                </button>

                <div class="user-menu">
                    <button class="user-menu__toggle" aria-expanded="false" aria-label="Menu pengguna">
                        <div class="avatar">
                            <?php
                                $avatarUrl = auth()->user()->avatar ?? 'images/profile.png';
                                $isGoogleAvatar = str_starts_with($avatarUrl, 'http');
                                $finalUrl = $isGoogleAvatar ? $avatarUrl : asset($avatarUrl) . '?v=' . auth()->user()->updated_at->timestamp;
                            ?>
                            <img src="<?php echo e($finalUrl); ?>"
                                alt="Avatar Mitra PRISMO" class="avatar__image" width="40" height="40" onerror="this.src='<?php echo e(asset('images/profile.png')); ?>''">
                        </div>
                        <div class="user-info">
                            <span class="user-info__name"><?php echo e(auth()->user()->name); ?></span>
                            <span class="user-info__role">Mitra</span>
                        </div>
                    </button>
                </div>

                <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu mobile" aria-expanded="false">
                    <span class="menu-toggle__bar"></span>
                    <span class="menu-toggle__bar"></span>
                    <span class="menu-toggle__bar"></span>
                </button>
            </div>
        </header>

        <main class="main" id="mainContent">
            <!-- Status Buka/Tutup -->
            <section class="status-section">
                <div class="status-card">
                    <div class="status-header">
                        <div class="status-indicator">
                            <span class="status-label">Status Saat Ini:</span>
                            <span class="status-badge" id="statusBadge" data-status="<?php echo e(isset($mitraProfile) && $mitraProfile->is_open ? 'open' : 'closed'); ?>">
                                <?php echo e(isset($mitraProfile) && $mitraProfile->is_open ? 'Buka' : 'Tutup'); ?>

                            </span>
                        </div>

                        <div class="status-buttons">
                            <button class="status-btn status-btn--open <?php echo e(isset($mitraProfile) && $mitraProfile->is_open ? 'status-btn--selected' : ''); ?>" data-status="open"
                                id="openStatusBtn">
                                Buka Steam
                            </button>
                            <button class="status-btn status-btn--closed <?php echo e(isset($mitraProfile) && !$mitraProfile->is_open ? 'status-btn--selected' : ''); ?>" data-status="closed" id="closeStatusBtn">
                                Tutup Steam
                            </button>
                        </div>
                    </div>

                    <div class="status-content">
                        <div class="status-actions">
                            <button class="btn btn--primary btn--save-status" id="saveStatusBtn">
                                Simpan Status
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Jam Operasional -->
            <section class="operational-section">
                <div class="operational-card">
                    <h2 class="section-title">Jam Operasional</h2>

                    <!-- Pilih Hari Operasional -->
                    <div class="days-selection">
                        <h3 class="subsection-title">Pilih Hari Operasional</h3>
                        <div class="days-grid">
                            <label class="day-checkbox">
                                <input type="checkbox" name="operational-days" value="monday" checked>
                                <span class="day-label">Senin</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="checkbox" name="operational-days" value="tuesday" checked>
                                <span class="day-label">Selasa</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="checkbox" name="operational-days" value="wednesday" checked>
                                <span class="day-label">Rabu</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="checkbox" name="operational-days" value="thursday" checked>
                                <span class="day-label">Kamis</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="checkbox" name="operational-days" value="friday" checked>
                                <span class="day-label">Jumat</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="checkbox" name="operational-days" value="saturday" checked>
                                <span class="day-label">Sabtu</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="checkbox" name="operational-days" value="sunday">
                                <span class="day-label">Minggu</span>
                            </label>
                        </div>
                    </div>

                    <!-- Jam Operasional Per Hari -->
                    <div class="time-schedule" id="timeSchedule">
                        <!-- otomatis -->
                    </div>

                    <div class="save-container">
                        <button class="btn btn--primary btn--save" id="saveOperationalHours">
                            Simpan Jam Operasional
                        </button>
                    </div>
                </div>
            </section>

            <!-- Paket Layanan -->
            <section class="services-section">
                <div class="services-card">
                    <div class="services-header">
                        <h2 class="section-title">Paket Layanan</h2>
                    </div>

                    <div class="services-list" id="servicesList">
                        <!-- otomatis -->
                    </div>

                    <div class="services-footer">
                        <button class="btn btn--primary btn--add-service" id="addService">
                            + Tambah Layanan
                        </button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Add/Edit Service Modal -->
    <template id="serviceModalTemplate">
        <div class="modal-overlay" role="dialog" aria-labelledby="serviceModalTitle" aria-modal="true">
            <div class="modal modal--service">
                <div class="modal__header">
                    <h3 id="serviceModalTitle" class="modal__title">Tambah Layanan Baru</h3>
                    <button class="modal__close" aria-label="Tutup modal">✕</button>
                </div>
                <div class="modal__content">
                    <form class="service-form" id="serviceForm">
                        <input type="hidden" id="serviceId" value="">
                        <div class="form-group">
                            <label for="serviceName" class="form-label">Nama Layanan</label>
                            <input type="text" id="serviceName" class="form-input" placeholder="Basic Steam" required>
                        </div>

                        <div class="form-group">
                            <label for="servicePrice" class="form-label">Harga</label>
                            <div class="price-input-container">
                                <span class="currency-prefix">Rp</span>
                                <input type="number" id="servicePrice" class="form-input form-input--price"
                                    placeholder="40000" min="0" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="serviceCapacity" class="form-label">Kapasitas Slot Harian</label>
                            <div class="capacity-input-container">
                                <input type="number" id="serviceCapacity" class="form-input" value="7" min="1" max="50">
                                <button type="button" class="info-btn" id="serviceCapacityInfoBtn"
                                    aria-label="Informasi kapasitas slot">
                                    <img src="<?php echo e(asset('images/tanya.png')); ?>" alt="Info" width="20" height="20">
                                </button>
                            </div>
                            <div class="form-hint">Jumlah kendaraan yang dapat diproses per hari untuk layanan ini</div>
                        </div>

                        <div class="form-group">
                            <label for="serviceDescription" class="form-label">Deskripsi</label>
                            <textarea id="serviceDescription" class="form-textarea"
                                placeholder="Cuci eksterior mobil dengan sabun khusus dan air bersih" rows="3"
                                required></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn--secondary" data-action="cancel">Batal</button>
                            <button type="submit" class="btn btn--primary" id="serviceSubmitBtn">Simpan Layanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Delete Confirmation Modal Template -->
    <template id="deleteConfirmModalTemplate">
        <div class="modal-overlay" role="dialog" aria-labelledby="deleteConfirmModalTitle" aria-modal="true">
            <div class="modal modal--confirm">
                <div class="modal__content modal__content--centered">
                    <div class="confirm-icon">🗑️</div>
                    <h3 id="deleteConfirmModalTitle" class="modal__title">Hapus Layanan</h3>
                    <p class="modal__message" id="deleteConfirmMessage">Apakah Anda yakin ingin menghapus layanan ini?
                    </p>
                    <div class="confirm-actions">
                        <button type="button" class="btn btn--secondary" data-action="cancel">Batal</button>
                        <button type="button" class="btn btn--danger" data-action="confirm-delete">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Confirmation Modal Template -->
    <template id="confirmModalTemplate">
        <div class="modal-overlay" role="dialog" aria-labelledby="confirmModalTitle" aria-modal="true">
            <div class="modal modal--confirm">
                <div class="modal__content modal__content--centered">
                    <div class="confirm-icon" id="confirmIcon">⚠️</div>
                    <h3 id="confirmModalTitle" class="modal__title">Konfirmasi</h3>
                    <p class="modal__message" id="confirmMessage"></p>
                    <div class="confirm-actions">
                        <button type="button" class="btn btn--secondary" data-action="cancel">Batal</button>
                        <button type="button" class="btn btn--primary" data-action="confirm">Ya, Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Status Confirmation Modal Template -->
    <template id="statusConfirmModalTemplate">
        <div class="modal-overlay" role="dialog" aria-labelledby="statusConfirmModalTitle" aria-modal="true">
            <div class="modal modal--confirm">
                <div class="modal__content modal__content--centered">
                    <div class="confirm-icon" id="statusConfirmIcon">⚠️</div>
                    <h3 id="statusConfirmModalTitle" class="modal__title">Konfirmasi Status</h3>
                    <p class="modal__message" id="statusConfirmMessage"></p>
                    <div class="confirm-actions">
                        <button type="button" class="btn btn--secondary" data-action="cancel">Batal</button>
                        <button type="button" class="btn btn--primary" data-action="confirm-status">Ya, Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Success Modal Template -->
    <template id="successModalTemplate">
        <div class="modal-overlay" role="dialog" aria-labelledby="successModalTitle" aria-modal="true">
            <div class="modal modal--success">
                <div class="modal__content modal__content--centered">
                    <div class="success-icon" aria-hidden="true">✓</div>
                    <h3 id="successModalTitle" class="modal__title">Berhasil!</h3>
                    <p class="modal__message" id="successMessage">Perubahan berhasil disimpan</p>
                    <button type="button" class="btn btn--success" data-action="close">Tutup</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Capacity Info Modal Template -->
    <template id="capacityInfoModalTemplate">
        <div class="modal-overlay" role="dialog" aria-labelledby="capacityInfoModalTitle" aria-modal="true">
            <div class="modal modal--info">
                <div class="modal__content modal__content--centered">
                    <div class="info-icon" aria-hidden="true">ⓘ</div>
                    <h3 id="capacityInfoModalTitle" class="modal__title">Kapasitas Slot</h3>
                    <p class="modal__message">Jumlah kendaraan yang dapat diproses per hari untuk layanan ini. Pelanggan dapat memesan berdasarkan ketersediaan slot yang Anda tentukan untuk setiap layanan.</p>
                    <button type="button" class="btn btn--primary" data-action="close">Mengerti</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Break Info Modal Template -->
    <template id="breakInfoModalTemplate">
        <div class="modal-overlay" role="dialog" aria-labelledby="breakInfoModalTitle" aria-modal="true">
            <div class="modal modal--info">
                <div class="modal__content modal__content--centered">
                    <div class="info-icon" aria-hidden="true">ⓘ</div>
                    <h3 id="breakInfoModalTitle" class="modal__title">Sesi Istirahat</h3>
                    <p class="modal__message">Dengan mengatur sesi istirahat, customer tidak akan bisa booking pada jam yang ditentukan. Sistem akan secara otomatis memblokir slot booking selama waktu istirahat.</p>
                    <button type="button" class="btn btn--primary" data-action="close">Mengerti</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Mobile Menu Template -->
    <template id="mobileMenuTemplate">
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu__header">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="PRISMO" class="logo" width="120" height="40">
                <button class="mobile-menu__close" id="mobileMenuClose" aria-label="Tutup menu">
                    ✕
                </button>
            </div>

            <div class="mobile-user-profile" id="mobileUserProfile">
                <div class="avatar">
                    <?php
                        $avatarUrl = auth()->user()->avatar ?? 'images/profile.png';
                        $isGoogleAvatar = str_starts_with($avatarUrl, 'http');
                        $finalUrl = $isGoogleAvatar ? $avatarUrl : asset($avatarUrl) . '?v=' . auth()->user()->updated_at->timestamp;
                    ?>
                    <img src="<?php echo e($finalUrl); ?>"
                        alt="Avatar Mitra PRISMO" class="avatar__image" width="50" height="50" onerror="this.src='<?php echo e(asset('images/profile.png')); ?>'">
                </div>
                <div class="mobile-user-profile__info">
                    <span class="user-info__name"><?php echo e(auth()->user()->name); ?></span>
                    <span class="user-info__role">Mitra</span>
                </div>
            </div>

            <nav class="mobile-nav" aria-label="Navigasi mobile">
                <a href="<?php echo e(url('/dashboard-mitra')); ?>" class="mobile-nav__item mobile-nav__item--active" data-page="dashboard">
                    <div class="mobile-nav__item-content">
                        Dashboard
                    </div>
                </a>
                <a href="<?php echo e(url('/mitra/saldo/saldo')); ?>" class="mobile-nav__item" data-page="saldo">
                    <div class="mobile-nav__item-content">
                        Saldo
                    </div>
                </a>
                <a href="<?php echo e(url('/mitra/antrian/antrian')); ?>" class="mobile-nav__item" data-page="antrian">
                    <div class="mobile-nav__item-content">
                        Antrian
                    </div>
                </a>
                <a href="<?php echo e(url('/mitra/review/review')); ?>" class="mobile-nav__item" data-page="review">
                    <div class="mobile-nav__item-content">
                        Review
                    </div>
                </a>
            </nav>
        </div>
    </template>

    <!-- Notification Panel -->
    <div id="notifPanel" class="notification-panel">
        <div class="notification-panel-header">
            <h3>Notifikasi</h3>
            <div class="notification-actions">
                <button id="markAllReadBtn" class="mark-all-read-btn">Tandai Dibaca</button>
                <button id="deleteAllBtn" class="delete-all-btn">Hapus Semua</button>
            </div>
        </div>
        <div id="notificationList" class="notification-list">
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat notifikasi...</p>
            </div>
        </div>
    </div>
    <div id="notifOverlay" class="notification-overlay"></div>

    <!-- Floating WhatsApp Button -->
    <div class="floating-whatsapp" id="floatingWhatsApp">
        <a href="https://wa.me/6282227671561?text=Halo%20Prismo%2C%20saya%20butuh%20bantuan" class="whatsapp-button" id="whatsappButton" target="_blank">
            <img src="<?php echo e(asset('images/whatsapp.png')); ?>" alt="WhatsApp" class="whatsapp-button__icon">
            <span class="whatsapp-button__text">Hubungi Kami</span>
        </a>
    </div>


    <script>
        // Inject data dari backend
        window.initialOperationalHours = <?php echo json_encode($operationalHours, 15, 512) ?>;
        window.initialServicePackages = <?php echo json_encode($servicePackages, 15, 512) ?>;
        window.initialCustomServices = <?php echo json_encode($customServices, 15, 512) ?>;
        window.mitraIsOpen = <?php echo e(isset($mitraProfile) && $mitraProfile->is_open ? 'true' : 'false'); ?>;
    </script>
    <script src="<?php echo e(asset('js/browser-notification.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notification-system.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notification-permission.js')); ?>"></script>
    <script src="<?php echo e(asset('js/mitra-badge-manager.js')); ?>"></script>
    <script src="<?php echo e(asset('js/prevent-back.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
    <script>
        // Listen untuk update avatar dari halaman profil
        if (typeof BroadcastChannel !== 'undefined') {
            const channel = new BroadcastChannel('profile_update');
            channel.onmessage = (event) => {
                if (event.data.type === 'avatar_updated') {
                    document.querySelectorAll('.avatar__image').forEach(img => {
                        img.src = event.data.avatar;
                    });
                    console.log('🔄 Avatar synced from other tab');
                }
            };
        }
    </script>
</body>

</html>
<?php /**PATH C:\Users\Faishal\Documents\prismo - Copy\resources\views/mitra/dashboard/dashboard.blade.php ENDPATH**/ ?>