<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="user-role" content="admin">
    <title>Kelola Voucher - Prismo</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('css/admin-kelolavoucher.css')); ?>?v=<?php echo e(time()); ?>">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
      /* Force dark theme untuk notification panel */
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
      .mark-all-read-btn,
      .delete-all-btn {
        background: rgba(96, 165, 250, 0.15) !important;
        color: #93c5fd !important;
        border: 1px solid rgba(96, 165, 250, 0.25) !important;
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
    </style>
</head>
<body>
    <!-- MODAL LOGOUT -->
    <div class="modal" id="logoutModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi Logout</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin keluar dari akun ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelLogout">Batal</button>
                <button class="btn btn-warning" id="confirmLogout">Ya, Logout</button>
            </div>
        </div>
    </div>

    <!-- MODAL SUCCESS VOUCHER -->
    <div class="modal" id="voucherSuccessModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Berhasil</h3>
            </div>
            <div class="modal-body">
                <p>Voucher berhasil disimpan!</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="voucherOkBtn">OK</button>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE CONFIRMATION -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Konfirmasi Hapus</h3>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus voucher ini?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelDelete">Batal</button>
                <button class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>

    <!-- TOPBAR -->
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

    <!-- NAV ROW (TAB MENU) -->
    <main class="page">
        <section class="nav-row">
            <div class="card">
                <nav class="nav" id="mainNav">
                    <a href="<?php echo e(url('/admin/dashboard')); ?>" class="nav-item">
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
                    <a href="<?php echo e(url('/admin/kelolavoucher/kelolavoucher')); ?>" class="nav-item active">
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

        <!-- HEADER KONTEN -->
        <section class="content-header">
            <h1 class="page-title">Kelola Voucher</h1>
            <p class="page-subtitle">Buat dan kelola voucher untuk customer</p>
        </section>

        <!-- FORM VOUCHER -->
        <section class="card voucher-form-section">
            <h2 class="section-title">Buat Voucher Baru</h2>

            <form id="voucherForm" class="voucher-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="namaVoucher">Nama Voucher <span class="required">*</span></label>
                        <input type="text" id="namaVoucher" name="namaVoucher" class="form-control" placeholder="Contoh: Voucher Akhir Tahun" required>
                    </div>

                    <div class="form-group">
                        <label for="kodeVoucher">Kode Voucher <span class="required">*</span></label>
                        <input type="text" id="kodeVoucher" name="kodeVoucher" class="form-control" placeholder="Contoh: PRISMO2024" required maxlength="20">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="masaBerlaku">Masa Berlaku <span class="required">*</span></label>
                        <input type="date" id="masaBerlaku" name="masaBerlaku" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="minTransaksi">Minimal Transaksi (Opsional)</label>
                        <div class="input-group">
                            <span class="input-addon">Rp</span>
                            <input type="number" id="minTransaksi" name="minTransaksi" class="form-control" placeholder="50000" min="1">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="persentasePotongan">Persentase Potongan (Opsional)</label>
                        <div class="input-group">
                            <input type="number" id="persentasePotongan" name="persentasePotongan" class="form-control" placeholder="20" min="1" max="100">
                            <span class="input-addon">%</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="maksPotongan">Maksimal Potongan <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-addon">Rp</span>
                            <input type="number" id="maksPotongan" name="maksPotongan" class="form-control" placeholder="100000" min="1" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="maxClaims">Maksimal Klaim (Opsional)</label>
                        <div class="input-group">
                            <input type="number" id="maxClaims" name="max_claims" class="form-control" placeholder="100" min="1">
                            <span class="input-addon">pengguna</span>
                        </div>
                        <small class="form-help">Voucher akan otomatis terhapus setelah diklaim sejumlah ini. Kosongkan untuk unlimited.</small>
                    </div>

                    <div class="form-group">
                        <label for="warnaVoucher">Warna Voucher <span class="required">*</span></label>
                        <div class="color-picker-group">
                            <input type="color" id="warnaVoucher" name="warnaVoucher" class="color-input" value="#1c98f5" required>
                            <input type="text" id="warnaVoucherText" class="form-control color-text" value="#1c98f5" placeholder="#1c98f5" maxlength="7" required>
                        </div>
                        <small class="form-help">Pilih warna untuk tampilan voucher di aplikasi customer</small>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn btn-secondary" id="btnReset">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan Voucher</button>
                </div>
            </form>
        </section>

        <!-- DAFTAR VOUCHER -->
        <section class="card voucher-list-section">
            <div class="section-header">
                <h2 class="section-title">Daftar Voucher</h2>
            </div>

            <div class="voucher-table-wrapper">
                <table class="voucher-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Voucher</th>
                            <th>Warna</th>
                            <th>Potongan</th>
                            <th>Min. Transaksi</th>
                            <th>Klaim</th>
                            <th>Masa Berlaku</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="voucherTableBody">
                        <!-- Data voucher akan dimuat di sini -->
                    </tbody>
                </table>
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

    <script>
        // Inject real voucher data from server
        window.vouchersData = <?php echo json_encode($vouchers, 15, 512) ?>;
    </script>
    <script src="<?php echo e(asset('js/browser-notification.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notification-system.js')); ?>"></script>
    <script src="<?php echo e(asset('js/kelolavoucher.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\Faishal\Documents\prismo - Copy\resources\views/admin/kelolavoucher/kelolavoucher.blade.php ENDPATH**/ ?>