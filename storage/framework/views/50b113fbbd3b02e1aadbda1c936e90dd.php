<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="user-role" content="customer">
    <title>Status Pengerjaan & Booking</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/Rbooking.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header Status Pengerjaan -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Prismo Logo">
                </div>
                <ul class="nav-menu" id="mainNav">
                    <li class="mobile-profile-menu">
                        <div class="user-profile mobile-user-profile" onclick="window.location.href='<?php echo e(url('/customer/profil/uprofil')); ?>'" style="cursor: pointer;">
                            <?php
                                $avatar = auth()->user()->avatar;
                                if (!$avatar) {
                                    $avatarUrl = asset('images/profile.png');
                                } elseif (Str::startsWith($avatar, 'http')) {
                                    $avatarUrl = $avatar;
                                } elseif (Str::startsWith($avatar, 'storage/')) {
                                    $avatarUrl = asset($avatar);
                                } elseif (Str::startsWith($avatar, '/storage/')) {
                                    $avatarUrl = url($avatar);
                                } else {
                                    $avatarUrl = asset('storage/' . $avatar);
                                }
                            ?>
                            <img src="<?php echo e($avatarUrl); ?>?v=<?php echo e(auth()->user()->updated_at->timestamp); ?>" alt="User" class="user-icon-img" id="mobileBookingProfileImg" onerror="this.src='<?php echo e(asset('images/profile.png')); ?>'">
                            <div class="user-info">
                                <span class="user-name" id="mobileBookingUserName"><?php echo e(auth()->user()->name); ?></span>
                                <span class="user-role">User</span>
                            </div>
                        </div>
                    </li>
                    <li><a href="<?php echo e(url('/customer/dashboard/dashU')); ?>" class="nav-link">Beranda</a></li>
                    <li><a href="<?php echo e(url('/customer/booking/Rbooking')); ?>" class="nav-link active">Booking</a></li>
                    <li><a href="<?php echo e(url('/customer/voucher/voucher')); ?>" class="nav-link">Voucher</a></li>
                </ul>
                <div class="nav-right">
                    <button class="notification-btn" id="notifBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">5</span>
                    </button>
                    <div class="user-profile desktop-user-profile" onclick="window.location.href='<?php echo e(url('/customer/profil/uprofil')); ?>'" style="cursor: pointer;">
                        <?php
                            $avatar = auth()->user()->avatar;
                            if (!$avatar) {
                                $avatarUrl = asset('images/profile.png');
                            } elseif (Str::startsWith($avatar, 'http')) {
                                $avatarUrl = $avatar;
                            } elseif (Str::startsWith($avatar, 'storage/')) {
                                $avatarUrl = asset($avatar);
                            } elseif (Str::startsWith($avatar, '/storage/')) {
                                $avatarUrl = url($avatar);
                            } else {
                                $avatarUrl = asset('storage/' . $avatar);
                            }
                        ?>
                        <img src="<?php echo e($avatarUrl); ?>?v=<?php echo e(auth()->user()->updated_at->timestamp); ?>" alt="User" class="user-icon-img" id="bookingProfileImg" onerror="this.src='<?php echo e(asset('images/profile.png')); ?>'">
                        <div class="user-info">
                            <span class="user-name" id="bookingUserName"><?php echo e(auth()->user()->name); ?></span>
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

    <!-- Booking Sections -->
    <div class="booking-sections">
        <!-- Booking Saat Ini -->
        <section class="booking-current">
            <h3>Booking Saat ini</h3>

            <div class="booking-card">
            <!-- Status Pengerjaan -->
            <div class="status-section">
                <div class="status-header">
                    <h2>Status Pengerjaan</h2>
                </div>

                <!-- Progress Bar -->
                <div class="progress-wrapper">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                </div>

                <div class="status-tabs" style="pointer-events: none;">
                    <div class="tab active" data-step="1">
                        <div class="tab-connector"></div>
                        <div class="icon-wrapper">
                            <div class="icon-circle">
                                <img src="<?php echo e(asset('images/imenunggu.png')); ?>" alt="Cek Transaksi" class="status-icon">
                            </div>
                            <div class="pulse-ring"></div>
                            <div class="tab-badge">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="tab-content">
                            <span class="label">Cek Transaksi</span>
                            <span class="description">Admin melakukan pengecekan</span>
                            <span class="status-time" id="time-step-1"></span>
                        </div>
                    </div>

                    <div class="tab" data-step="2">
                        <div class="tab-connector"></div>
                        <div class="icon-wrapper">
                            <div class="icon-circle">
                                <img src="<?php echo e(asset('images/imulai.png')); ?>" alt="Menunggu" class="status-icon">
                            </div>
                            <div class="pulse-ring"></div>
                            <div class="tab-badge">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="tab-content">
                            <span class="label">Menunggu</span>
                            <span class="description">Pastikan tiba tepat waktu</span>
                            <span class="status-time" id="time-step-2"></span>
                        </div>
                    </div>

                    <div class="tab" data-step="3">
                        <div class="tab-connector"></div>
                        <div class="icon-wrapper">
                            <div class="icon-circle">
                                <img src="<?php echo e(asset('images/iproses.png')); ?>" alt="Dalam Proses" class="status-icon">
                            </div>
                            <div class="pulse-ring"></div>
                            <div class="tab-badge">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="tab-content">
                            <span class="label">Dalam Proses</span>
                            <span class="description">Kendaraan sedang dibersihkan</span>
                            <span class="status-time" id="time-step-3"></span>
                        </div>
                    </div>

                    <div class="tab" data-step="4">
                        <div class="tab-connector"></div>
                        <div class="icon-wrapper">
                            <div class="icon-circle">
                                <img src="<?php echo e(asset('images/iselesai.png')); ?>" alt="Selesai" class="status-icon">
                            </div>
                            <div class="pulse-ring"></div>
                            <div class="tab-badge">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="tab-content">
                            <span class="label">Selesai</span>
                            <span class="description">Kendaraan telah dibersihkan</span>
                            <span class="status-time" id="time-step-4"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="booking-header">
                <h4 id="currentPartner">Prismo Pro</h4>
                <span class="price" id="currentPrice">Total: Rp 150.000</span>
            </div>

            <p class="location" id="currentLocation">Jl. Sudirman No. 45, Jakarta Pusat</p>

            <div class="booking-details">
                <div class="detail-row">
                    <span class="label">Tanggal:</span>
                    <span class="value" id="currentDate">12 September 2025</span>
                </div>
                <div class="detail-row">
                    <span class="label">Jam:</span>
                    <span class="value" id="currentTime">14:00</span>
                </div>
                <div class="detail-row">
                    <span class="label">Layanan:</span>
                    <span class="value" id="currentTreatment">Cuci Mobil + Salon</span>
                </div>
                <div class="detail-row">
                    <span class="label">Tipe:</span>
                    <span class="value" id="currentType">4-Class</span>
                </div>
                <div class="detail-row">
                    <span class="label">Nomor Polisi:</span>
                    <span class="value" id="currentNopol">B 2348 NT</span>
                </div>
            </div>

            <div class="booking-actions">
                <button class="btn-cancel">Cancel</button>
                <button class="btn-reschedule">Reschedule</button>
            </div>
        </div>
    </section>

    <!-- Booking Terakhir -->
    <section class="booking-history">
        <h3>Booking Terakhir</h3>

        <div id="historyContainer">
            <!-- History cards will be dynamically generated here -->
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            <?php echo e($bookingHistory->links()); ?>

        </div>
    </section>
    </div>

    <!-- Cancel Modal -->
    <div class="modal-overlay" id="cancelModalOverlay">
        <div class="modal-container cancel-modal">
            <div class="modal-header">
                <h3>Cancel Booking</h3>
                <button class="modal-close" onclick="closeCancelModal()">×</button>
            </div>

            <div class="modal-body">
                <div class="booking-info">
                    <h4 id="cancelPartnerName">Prismo Pro</h4>
                    <p id="cancelServiceType">Cuci Mobil + Salon</p>
                </div>

                <div class="booking-details-info">
                    <div class="detail-item">
                        <span class="label">Tanggal</span>
                        <span class="value" id="cancelBookingDate">12 September 2025</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Waktu</span>
                        <span class="value" id="cancelBookingTime">14:00</span>
                    </div>
                </div>

                <div class="warning-box">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="warning-text">
                        <strong>Perhatian!</strong>
                        <p>Pengembalian dana akan dikirim ke metode refund yang Anda atur di profil. Biaya admin Rp 2.500 akan dipotong dari pengembalian dana.</p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeCancelModal()">Batal</button>
                <button class="btn-danger" onclick="confirmCancelBooking()">Konfirmasi Cancel</button>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div class="modal-overlay" id="rescheduleModalOverlay">
        <div class="modal-container reschedule-modal">
            <div class="modal-header">
                <h3>Reschedule Booking</h3>
                <button class="modal-close" onclick="closeRescheduleModal()">×</button>
            </div>

            <div class="modal-body">
                <div class="booking-info">
                    <h4 id="reschedulePartnerName">Prismo Pro</h4>
                    <p id="rescheduleServiceType">Cuci Mobil + Salon</p>
                </div>

                <div class="form-group">
                    <label>Pilih Tanggal Baru</label>
                    <div id="rescheduleCalendar" class="reschedule-calendar-container"></div>
                    <p class="calendar-note">* Tanggal merah = mitra tutup</p>
                </div>

                <div class="form-group">
                    <label>Masukkan Waktu Baru</label>
                    <select id="rescheduleTime" class="form-input">
                        <option value="">Pilih tanggal terlebih dahulu</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeRescheduleModal()">Batal</button>
                <button class="btn-primary" onclick="confirmReschedule()">Konfirmasi Reschedule</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal-overlay" id="successModalOverlay">
        <div class="modal-container success-modal">
            <div class="success-icon">✓</div>
            <h3>Jadwal telah berhasil diubah</h3>
            <button class="btn-primary" onclick="closeSuccessModal()">Ya</button>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal-overlay" id="reviewModalOverlay">
        <div class="modal-container review-modal">
            <div class="modal-header">
                <h3 id="reviewModalTitle">Beri Rating & Review</h3>
                <button class="modal-close" onclick="closeReviewModal()">×</button>
            </div>

            <div class="modal-body">
                <div class="booking-info">
                    <h4 id="reviewPartnerName">Prismo Pro</h4>
                    <p id="reviewServiceType">Cuci Mobil + Salon</p>
                </div>

                <div class="form-group">
                    <label class="modal-section-title">Bagikan Pengalaman Anda!</label>
                    <div class="star-rating">
                        <div class="stars" id="starRating">
                            <span class="star" data-rating="1">☆</span>
                            <span class="star" data-rating="2">☆</span>
                            <span class="star" data-rating="3">☆</span>
                            <span class="star" data-rating="4">☆</span>
                            <span class="star" data-rating="5">☆</span>
                        </div>
                        <div class="rating-text" id="ratingText">Pilih rating (1-5 bintang)</div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="review-input-container">
                        <div class="input-actions-left">
                            <button type="button" class="input-action-btn" id="uploadPhotoBtn" title="Tambah foto">
                                <i class="fas fa-camera"></i>
                            </button>
                            <input type="file" id="photoInput" accept="image/*" multiple hidden>
                        </div>
                        <textarea
                            id="reviewComment"
                            class="form-input review-textarea"
                            placeholder="Bagaimana pengalaman Anda menggunakan layanan ini?"
                            rows="3"
                        ></textarea>
                        <button type="button" class="send-review-btn" id="sendReviewBtn" title="Kirim review">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <div class="char-count">
                        <span id="charCount">0</span>/500 karakter
                    </div>

                    <!-- Preview uploaded images -->
                    <div class="uploaded-previews" id="uploadedPreviews" style="display: none;">
                        <div class="preview-label">Foto yang diunggah:</div>
                        <div class="preview-images" id="previewImages"></div>
                    </div>
                </div>

                <div class="current-review" id="currentReviewSection" style="display: none;">
                    <div class="review-display">
                        <h4>Review Saat Ini:</h4>
                        <div class="current-rating" id="currentRatingDisplay"></div>
                        <p class="current-comment" id="currentCommentDisplay"></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeReviewModal()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        // Inject real data from server - NO MORE MOCK DATA
        window.currentBookingData = <?php echo json_encode($currentBookingData, 15, 512) ?>;
        window.bookingHistory = <?php echo json_encode($bookingHistory->items(), 15, 512) ?>;
    </script>
    <script src="<?php echo e(asset('js/Rbooking.js')); ?>"></script>
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
    <script src="<?php echo e(asset('js/browser-notification.js')); ?>"></script>
    <script src="<?php echo e(asset('js/notification-system.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\Faishal\Documents\prismo - Copy\resources\views/customer/booking/Rbooking.blade.php ENDPATH**/ ?>