<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="user-role" content="customer">
    <title>Voucher - PRISMO</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/voucher.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Notification Popup -->
    <div class="notification-popup" id="notificationPopup">
        <div class="notification-content">
            <i class="fas fa-bell notification-icon"></i>
            <div class="notification-text">
                <p class="notification-title">prismo.google.com ingin:</p>
                <p class="notification-message">Izinkan kami mengirimkan notifikasi terbaru</p>
            </div>
        </div>
        <div class="notification-buttons">
            <button class="btn-blokir">Blokir</button>
            <button class="btn-izinkan">Izinkan</button>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Prismo Logo">
                </div>
                <ul class="nav-menu" id="mainNav">
                    <li class="mobile-profile-menu">
                        <div class="user-profile mobile-user-profile" onclick="window.location.href='<?php echo e(url('/customer/profil/uprofil')); ?>'" style="cursor: pointer;">
                            <img src="<?php echo e(asset(auth()->user()->avatar ?? 'images/profile.png')); ?>?v=<?php echo e(auth()->user()->updated_at->timestamp); ?>" alt="User" class="user-icon-img" id="mobileVoucherProfileImg">
                            <div class="user-info">
                                <span class="user-name" id="mobileVoucherUserName"><?php echo e(auth()->user()->name); ?></span>
                                <span class="user-role">User</span>
                            </div>
                        </div>
                    </li>
                    <li><a href="<?php echo e(url('/customer/dashboard/dashU')); ?>" class="nav-link">Beranda</a></li>
                    <li><a href="<?php echo e(url('/customer/booking/Rbooking')); ?>" class="nav-link">Booking</a></li>
                    <li><a href="<?php echo e(url('/customer/voucher/voucher')); ?>" class="nav-link active">Voucher</a></li>
                </ul>
                <div class="nav-right">
                    <button class="notification-btn" id="notifBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </button>
                    <div class="user-profile desktop-user-profile" onclick="window.location.href='<?php echo e(url('/customer/profil/uprofil')); ?>'" style="cursor: pointer;">
                        <img src="<?php echo e(asset(auth()->user()->avatar ?? 'images/profile.png')); ?>?v=<?php echo e(auth()->user()->updated_at->timestamp); ?>" alt="User" class="user-icon-img" id="voucherProfileImg">
                        <div class="user-info">
                            <span class="user-name" id="voucherUserName"><?php echo e(auth()->user()->name); ?></span>
                            <span class="user-role">User</span>
                        </div>
                    </div>
                    <button class="mobile-menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                        ☰
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Notification Panel -->
    <div id="notifPanel" class="notification-panel">
        <div class="panel-header">
            <h2>Notifikasi</h2>
            <div class="notification-actions">
                <button id="markAllReadBtn" class="mark-all-read-btn">Tandai Dibaca</button>
                <button id="deleteAllBtn" class="delete-all-btn">Hapus Semua</button>
            </div>
        </div>
        <div id="notificationList">
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat notifikasi...</p>
            </div>
        </div>
    </div>
    <div id="notifOverlay" class="notification-overlay"></div>

    <!-- Overlay -->
    <div class="sort-overlay" id="sortOverlay"></div>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <div class="banner-content">
                <h1 class="banner-title">Voucher & Promo Spesial</h1>
                <p class="banner-subtitle">Hemat lebih banyak dengan voucher eksklusif dari Prismo</p>
            </div>
        </div>
    </section>

    <!-- Voucher Tabs -->
    <section class="voucher-section">
        <div class="container">
            <div class="voucher-info-banner" style="background:#eaf6ff;border-radius:8px;padding:12px 18px;margin-bottom:18px;display:flex;align-items:center;gap:10px;font-size:15px;color:#1976d2;">
            </div>
            <!-- Tab Navigation -->
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="available">Tersedia</button>
                <button class="tab-btn" data-tab="claimed">Voucher Saya</button>
                <button class="tab-btn" data-tab="used">Terpakai</button>
            </div>

            <!-- Tab Content - Available Vouchers -->
            <div class="tab-content active" id="available">
                <div class="vouchers-grid" id="availableVouchers">
                    <!-- Vouchers will be loaded dynamically -->
                </div>
                <div class="no-data" id="noAvailableVouchers" style="display: none;">
                    <i class="fas fa-ticket-alt"></i>
                    <p>Tidak ada voucher tersedia saat ini</p>
                </div>
            </div>

            <!-- Tab Content - Claimed Vouchers -->
            <div class="tab-content" id="claimed">
                <div class="vouchers-grid" id="claimedVouchers">
                    <!-- Claimed vouchers will be loaded dynamically -->
                </div>
                <div class="no-data" id="noClaimedVouchers" style="display: none;">
                    <i class="fas fa-inbox"></i>
                    <p>Anda belum mengklaim voucher apapun</p>
                </div>
            </div>

            <!-- Tab Content - Used Vouchers -->
            <div class="tab-content" id="used">
                <div class="vouchers-grid" id="usedVouchers">
                    <!-- Used vouchers will be loaded dynamically -->
                </div>
                <div class="no-data" id="noUsedVouchers" style="display: none;">
                    <i class="fas fa-history"></i>
                    <p>Belum ada voucher yang terpakai</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Voucher Detail Modal -->
    <div class="modal" id="voucherModal">
        <div class="modal-overlay" onclick="closeVoucherModal()"></div>
        <div class="modal-content">
            <button class="modal-close" onclick="closeVoucherModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-body">
                <h2 class="modal-title" id="modalTitle"></h2>
                <div class="modal-discount" id="modalDiscount"></div>
                <div class="voucher-details">
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>Berlaku hingga: <strong id="modalExpiry"></strong></span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Min. Transaksi: <strong id="modalMinTransaction"></strong></span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-tag"></i>
                        <span>Kode Voucher: <strong id="modalCode"></strong></span>
                        <button class="btn-copy" onclick="copyVoucherCode()" title="Copy kode voucher">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <button class="btn-claim" id="modalClaimBtn" onclick="claimVoucher()">
                    <i class="fas fa-gift"></i>
                    Klaim Voucher
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal" id="successModal">
        <div class="modal-overlay" onclick="closeSuccessModal()"></div>
        <div class="modal-content success-modal">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Voucher Berhasil Diklaim!</h2>
            <p>Voucher telah ditambahkan ke koleksi Anda dan siap digunakan</p>
            <button class="btn-primary" onclick="closeSuccessModal()">OK</button>
        </div>
    </div>

    <script>
        // Inject real data from server - NO MORE MOCK DATA
        window.availableVouchers = <?php echo json_encode($availableVouchers, 15, 512) ?>;
        window.myVouchers = <?php echo json_encode($myVouchers, 15, 512) ?>;
        window.usedVouchers = <?php echo json_encode($usedVouchers, 15, 512) ?>;
    </script>
    <script src="<?php echo e(asset('js/browser-notification.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notification-system.js')); ?>"></script>
    <script src="<?php echo e(asset('js/voucher.js')); ?>"></script>
    <script>
        // Listen untuk update avatar dari halaman profil
        if (typeof BroadcastChannel !== 'undefined') {
            const channel = new BroadcastChannel('profile_update');
            channel.onmessage = (event) => {
                if (event.data.type === 'avatar_updated') {
                    document.querySelectorAll('.user-icon-img, .avatar__image').forEach(img => {
                        img.src = event.data.avatar;
                    });
                    console.log('🔄 Avatar synced from other tab');
                }
            };
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Faishal\Documents\prismo - Copy\resources\views/customer/voucher/voucher.blade.php ENDPATH**/ ?>