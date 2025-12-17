<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Admin - PRISMO | Manajemen Sistem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="user-role" content="admin">
    <meta name="authenticated" content="true">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Dashboard Admin PRISMO - Kelola mitra, customer, booking, voucher, dan laporan sistem secara terpusat.">
    <meta name="keywords" content="admin dashboard, PRISMO, manajemen sistem, kelola mitra, kelola customer, laporan booking">
    <meta name="author" content="PRISMO">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Dashboard Admin - PRISMO">
    <meta property="og:description" content="Sistem Manajemen PRISMO untuk Administrator">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url('/admin/dashboard')); ?>">
    <meta property="og:image" content="<?php echo e(asset('images/logo.png')); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('css/admin-dashboard.css')); ?>?v=20251214041932">
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
    <!-- ====== MODAL KONFIRMASI ====== -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi Logout</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin logout dari sistem?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelLogout">Batal</button>
                <button class="btn btn-danger" id="confirmLogout">Ya, Logout</button>
            </div>
        </div>
    </div>

    <!-- ====== TOPBAR ====== -->
    <header class="topbar">
        <div class="logo">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Prismo Logo">
        </div>

        <div class="user-area">
            <div class="user-info">
                <span class="user-name"><?php echo e(auth()->user()->name); ?></span>
                <span class="user-role">Admin</span>
            </div>
            <!-- Notification Bell -->
            <button id="notifBtn" class="notification-btn" aria-label="Notifications" style="margin-right: 16px; background: none; border: none; cursor: pointer; position: relative;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span id="notifBadge" class="notification-badge" style="display: none; position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: flex; align-items: center; justify-content: center;">0</span>
            </button>
            <div class="user-dropdown">
                <div class="user-avatar">
                    <?php if(auth()->user()->avatar && str_starts_with(auth()->user()->avatar, 'http')): ?>
                        <img src="<?php echo e(auth()->user()->avatar); ?>" alt="<?php echo e(auth()->user()->name); ?>" onerror="this.src='<?php echo e(asset('images/profile.png')); ?>'">
                    <?php elseif(auth()->user()->avatar): ?>
                        <img src="<?php echo e(asset(auth()->user()->avatar)); ?>?v=<?php echo e(auth()->user()->updated_at->timestamp); ?>" alt="<?php echo e(auth()->user()->name); ?>" onerror="this.src='<?php echo e(asset('images/profile.png')); ?>'">
                    <?php else: ?>
                        <img src="<?php echo e(asset('images/profile.png')); ?>" alt="<?php echo e(auth()->user()->name); ?>">
                    <?php endif; ?>
                </div>
                <div class="dropdown-menu">
                    <a href="<?php echo e(url('/admin/kelolaadmin/kelolaadmin')); ?>" class="dropdown-item new-admin" id="newAdminBtn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="margin-right: 8px;">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Kelola Admin
                    </a>
                    <a href="#" class="dropdown-item logout" id="logoutBtn">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" style="margin-right: 8px;">
                            <path d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
                            <path d="M10.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L9.293 7.5H2.5a.5.5 0 0 0 0 1h6.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ====== ISI HALAMAN ====== -->
    <main class="page">
        <section class="nav-row">
            <div class="card">
                <nav class="nav" id="mainNav">
                    <a href="<?php echo e(url('/admin/dashboard')); ?>" class="nav-item active">
                        <span class="icon"><img src="<?php echo e(asset('images/dashboard.png')); ?>" alt="Dashboard" width="16" height="16"></span>
                        Dashboard
                    </a>
                    <a href="<?php echo e(url('/admin/kelolamitra/kelolamitra')); ?>" class="nav-item">
                        <span class="icon"><img src="<?php echo e(asset('images/kelolamitra.png')); ?>" alt="Kelola Mitra" width="16" height="16"></span>
                        Kelola Mitra
                    </a>
                    <a href="<?php echo e(url('/admin/kelolacustomer/kelolacustomer')); ?>" class="nav-item">
                        <span class="icon"><img src="<?php echo e(asset('images/kelolacustomer.png')); ?>" alt="Kelola Customer" width="16" height="16"></span>
                        Kelola Customer
                    </a>
                    <a href="<?php echo e(url('/admin/kelolavoucher/kelolavoucher')); ?>" class="nav-item">
                        <span class="icon"><img src="<?php echo e(asset('images/voucher.png')); ?>" alt="Kelola Voucher" width="16" height="16"></span>
                        Kelola Voucher
                    </a>
                    <a href="<?php echo e(url('/admin/kelolabooking/kelolabooking')); ?>" class="nav-item">
                        <span class="icon"><img src="<?php echo e(asset('images/kelolabooking.png')); ?>" alt="Kelola Booking" width="16" height="16"></span>
                        Kelola Booking
                    </a>
                    <a href="<?php echo e(url('/admin/laporan/laporan')); ?>" class="nav-item">
                        <span class="icon"><img src="<?php echo e(asset('images/laporan.png')); ?>" alt="Laporan" width="16" height="16"></span>
                        Laporan
                    </a>
                </nav>
            </div>
        </section>

        <!-- Kartu Statistik -->
        <section class="stats-row">
            <div class="card">
                <div class="card-header">
                    <div class="badge-icon badge-blue">
                        <img src="<?php echo e(asset('images/totalmitra.png')); ?>" alt="Mitra" width="24" height="24">
                    </div>
                    <div class="card-content">
                        <span class="card-title">Total Mitra</span>
                        <div class="card-value"><?php echo e($totalMitra); ?></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="badge-icon badge-green">
                        <img src="<?php echo e(asset('images/totalcustomer.png')); ?>" alt="Customer" width="24" height="24">
                    </div>
                    <div class="card-content">
                        <span class="card-title">Total Customers</span>
                        <div class="card-value"><?php echo e($totalCustomer); ?></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="badge-icon badge-yellow">
                        <img src="<?php echo e(asset('images/totaltransaksi.png')); ?>" alt="Transaksi" width="24" height="24">
                    </div>
                    <div class="card-content">
                        <span class="card-title">Total Transaksi</span>
                        <div class="card-value"><?php echo e($totalTransaksi); ?></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Permintaan Mitra & Penarikan -->
        <section class="requests-row">
            <div class="card">
                <div class="request-main">Persetujuan Mitra</div>
                <div class="request-number"><?php echo e($mitraPending); ?></div>
                <div class="request-desc">Menunggu Persetujuan</div>
                <div class="card-footer">
                    <a href="<?php echo e(url('/admin/kelolamitra/kelolamitra')); ?>" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>

            <div class="card">
                <div class="request-main">Permintaan Penarikan Saldo</div>
                <div class="request-number"><?php echo e($pendingWithdrawals); ?></div>
                <div class="request-desc">Menunggu Persetujuan</div>
                <div class="card-footer">
                    <a href="<?php echo e(url('/admin/dashboard/penarikan')); ?>" class="btn btn-warning">Lihat Detail</a>
                </div>
            </div>
        </section>
    </main>

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

    <script src="<?php echo e(asset('js/browser-notification.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notification-system.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notification-permission.js')); ?>"></script>
    <script src="<?php echo e(asset('js/prevent-back.js')); ?>?v=<?php echo e(time()); ?>"></script>
    <script src="<?php echo e(asset('js/admin-dashboard.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\Faishal\Documents\prismo - Copy\resources\views/admin/dashboard/dashboard.blade.php ENDPATH**/ ?>