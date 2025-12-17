<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Profil Pengguna - Prismo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/uprofil.css') }}?v={{ time() }}">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Prismo">
            </div>
            <button class="back-btn" id="backBtn" onclick="window.location.href='{{ url('/customer/dashboard/dashU') }}'" style="cursor: pointer;">
                <i class="ph ph-arrow-left"></i>
                Kembali
            </button>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar" onclick="document.getElementById('photoUpload').click()" style="cursor: pointer;" title="Klik untuk ganti foto">
                        @php
                            $avatar = auth()->user()->avatar;
                            if (!$avatar) {
                                $avatarUrl = asset('images/profile.png');
                            } elseif (Str::startsWith($avatar, 'http')) {
                                $avatarUrl = $avatar; // Full URL
                            } elseif (Str::startsWith($avatar, 'storage/')) {
                                $avatarUrl = asset($avatar); // Already has storage/ prefix
                            } elseif (Str::startsWith($avatar, '/storage/')) {
                                $avatarUrl = url($avatar); // Already has /storage/ prefix
                            } else {
                                $avatarUrl = asset('storage/' . $avatar); // Add storage/ prefix
                            }
                        @endphp
                        <img src="{{ $avatarUrl }}?v={{ auth()->user()->updated_at->timestamp }}" alt="Avatar" id="avatarImg" data-original-src="{{ $avatarUrl }}?v={{ auth()->user()->updated_at->timestamp }}" onerror="this.src='{{ asset('images/profile.png') }}'" style="object-fit: cover;">
                        <div class="avatar-badge">
                            <i class="ph-fill ph-camera"></i>
                        </div>
                        <input type="file" id="photoUpload" accept="image/*" style="display: none;">
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name" id="profileName">{{ auth()->user()->name }}</h2>
                        <p class="profile-email">
                            <i class="ph ph-envelope"></i>
                            <span id="profileEmail">{{ auth()->user()->email }}</span>
                        </p>
                        <p class="profile-phone">
                            <i class="ph ph-phone"></i>
                            <span id="profilePhone">{{ auth()->user()->phone ?? '-' }}</span>
                        </p>
                        @php
                            $profile = auth()->user()->customerProfile;
                        @endphp
                        @if($profile && $profile->address)
                        <p class="profile-address">
                            <i class="ph ph-map-pin"></i>
                            <span id="profileAddress">{{ $profile->address }}
                                @if($profile->city || $profile->province)
                                    , {{ $profile->city }}{{ $profile->province ? ', ' . $profile->province : '' }}
                                @endif
                            </span>
                        </p>
                        @endif
                        <p class="profile-member-since">
                            <i class="ph ph-calendar"></i>
                            Member since: <span id="memberSince">{{ auth()->user()->created_at->format('d F Y') }}</span>
                        </p>
                        <div class="profile-action-buttons">
                            <button class="edit-profile-btn" onclick="window.location.href='{{ url('/customer/profil/eprofil') }}'">
                                <i class="ph ph-pencil-simple"></i>
                                Edit Profil
                            </button>
                            <button class="refund-method-btn" id="refundMethodBtn">
                                <i class="ph ph-wallet"></i>
                                Metode Refund
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-icon booking">
                            <i class="ph ph-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Total Booking</p>
                            <p class="stat-value" id="totalBooking">{{ $totalBooking }}</p>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon points">
                            <i class="ph ph-coin"></i>
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Poin Saya</p>
                            <p class="stat-value" id="totalPoints">{{ $totalPoints }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="settings-card">
                <h3 class="settings-title">Pengaturan Akun</h3>

                <div class="settings-list">
                    @if(empty(auth()->user()->google_id))
                    <a href="{{ url('/customer/profile/change-password') }}" class="setting-item">
                        <div class="setting-icon">
                            <i class="ph ph-lock"></i>
                        </div>
                        <span>Ubah password</span>
                        <i class="ph ph-caret-right"></i>
                    </a>
                    @else
                    <div class="setting-item" style="opacity: 0.5; cursor: not-allowed;" title="Tidak tersedia untuk akun Google">
                        <div class="setting-icon">
                            <i class="ph ph-lock"></i>
                        </div>
                        <span>Ubah password (Login via Google)</span>
                    </div>
                    @endif

                    <a href="{{ url('/customer/help/faq') }}" class="setting-item">
                        <div class="setting-icon">
                            <i class="ph ph-question"></i>
                        </div>
                        <span>Bantuan & FAQ</span>
                        <i class="ph ph-caret-right"></i>
                    </a>

                    <button class="logout-btn" id="logoutBtn">
                        <i class="ph ph-sign-out"></i>
                        Keluar
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Edit Profil -->
    <div class="modal" id="editProfileModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Profil</h3>
                <button class="close-modal" id="closeEditModal">
                    <i class="ph ph-x"></i>
                </button>
            </div>
            <form class="edit-profile-form" id="editProfileForm">
                <div class="form-group">
                    <label for="editName">Nama Lengkap</label>
                    <input type="text" id="editName" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" id="editEmail" readonly style="background-color: #f5f5f5; cursor: not-allowed;" required>
                    <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">Email tidak dapat diubah</small>
                </div>
                <div class="form-group">
                    <label for="editPhone">No. Telepon</label>
                    <input type="tel" id="editPhone" required>
                </div>
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Simpan Perubahan</span>
                    <span class="btn-loader"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Ubah Password -->
    <div class="modal" id="changePasswordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ubah Password</h3>
                <button class="close-modal" id="closePasswordModal">
                    <i class="ph ph-x"></i>
                </button>
            </div>
            <form class="change-password-form" id="changePasswordForm">
                <div class="form-group">
                    <label for="currentPassword">Password Saat Ini</label>
                    <div class="password-input">
                        <input type="password" id="currentPassword" required>
                        <i class="ph ph-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="newPassword">Password Baru</label>
                    <div class="password-input">
                        <input type="password" id="newPassword" required>
                        <i class="ph ph-eye toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Konfirmasi Password Baru</label>
                    <div class="password-input">
                        <input type="password" id="confirmPassword" required>
                        <i class="ph ph-eye toggle-password"></i>
                    </div>
                </div>
                <button type="submit" class="submit-btn">
                    <span class="btn-text">Ubah Password</span>
                    <span class="btn-loader"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Avatar Confirmation Modal -->
    <div class="modal-overlay" id="avatarConfirmModal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Konfirmasi Perubahan Avatar</h3>
            </div>
            <div class="modal-body" style="padding: 20px; text-align: center;">
                <i class="fas fa-user-circle" style="font-size: 48px; color: #00bcd4; margin-bottom: 15px;"></i>
                <p style="font-size: 16px; color: #333; margin-bottom: 20px;">Apakah Anda yakin ingin mengubah avatar Anda?</p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button class="btn-cancel" onclick="cancelAvatarChange()" style="padding: 10px 20px; background: #ccc; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button class="btn-confirm" onclick="confirmAvatarChange()" style="padding: 10px 20px; background: #00bcd4; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        <i class="fas fa-check"></i> Ya, Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Refund Method Modal -->
    <div class="modal-overlay" id="refundMethodModalOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(5px); display: none;">
        <div class="modal-content" style="max-width: 500px; border-radius: 16px; background: white; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); position: relative; z-index: 10001;">
            <div class="modal-header" style="border-bottom: 1px solid #eee; padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; font-size: 20px; color: #333; font-weight: 600;">
                    <i class="ph ph-wallet" style="margin-right: 8px; color: #667eea;"></i>Metode Refund
                </h3>
                <button class="close-modal" onclick="closeRefundMethodModal()" style="background: none; border: none; font-size: 24px; color: #999; cursor: pointer; padding: 0; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                    <i class="ph ph-x"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <p style="color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.6;">
                    Atur metode pembayaran untuk menerima pengembalian dana jika booking dibatalkan.
                </p>
                <form id="refundMethodForm">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 500; color: #333; margin-bottom: 8px; font-size: 14px;">
                            <i class="ph ph-credit-card" style="margin-right: 5px;"></i>Metode Pembayaran
                        </label>
                        <div class="custom-select-wrapper" style="position: relative;">
                            <div id="selectedPaymentMethod" class="custom-select-trigger" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; color: #999; background: white; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                                <span>Pilih metode pembayaran</span>
                            </div>
                            <input type="hidden" id="refundPaymentMethod" name="refund_method" required>
                            <div id="paymentMethodOptions" class="custom-select-options" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 10px; margin-top: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 1000; max-height: 250px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                                <div class="payment-option" data-value="Dana" style="padding: 12px 15px; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: background 0.2s;">
                                    <img src="{{ asset('images/dana-logo.png') }}" alt="Dana" style="width: 32px; height: 32px; object-fit: contain;" onerror="this.style.display='none'">
                                    <span style="font-size: 14px; color: #333;">Dana</span>
                                </div>
                                <div class="payment-option" data-value="Gopay" style="padding: 12px 15px; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: background 0.2s;">
                                    <img src="{{ asset('images/gopay-logo.png') }}" alt="Gopay" style="width: 32px; height: 32px; object-fit: contain;" onerror="this.style.display='none'">
                                    <span style="font-size: 14px; color: #333;">Gopay</span>
                                </div>
                                <div class="payment-option" data-value="OVO" style="padding: 12px 15px; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: background 0.2s;">
                                    <img src="{{ asset('images/ovo-logo.png') }}" alt="OVO" style="width: 32px; height: 32px; object-fit: contain;" onerror="this.style.display='none'">
                                    <span style="font-size: 14px; color: #333;">OVO</span>
                                </div>
                                <div class="payment-option" data-value="ShopeePay" style="padding: 12px 15px; cursor: pointer; display: flex; align-items: center; gap: 12px; transition: background 0.2s;">
                                    <img src="{{ asset('images/shopee-logo.png') }}" alt="ShopeePay" style="width: 32px; height: 32px; object-fit: contain;" onerror="this.style.display='none'; console.log('ShopeePay logo not found');">
                                    <span style="font-size: 14px; color: #333;">ShopeePay</span>
                                </div>
                            </div>
                        </div>
                        <style>
                            #paymentMethodOptions::-webkit-scrollbar {
                                width: 0;
                                height: 0;
                            }
                        </style>
                    </div>
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label style="display: block; font-weight: 500; color: #333; margin-bottom: 8px; font-size: 14px;">
                            <i class="ph ph-hash" style="margin-right: 5px;"></i>Nomor Akun
                        </label>
                        <input type="text" id="refundAccountNumber" placeholder="Masukkan nomor akun (8-15 digit)" required pattern="[0-9]{8,15}" maxlength="15" style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 10px; font-size: 14px; color: #333;">
                        <small style="color: #999; font-size: 12px; display: block; margin-top: 5px;">Masukkan 8-15 digit nomor</small>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" class="btn-cancel" onclick="closeRefundMethodModal()" style="flex: 1; padding: 12px; background: #f5f5f5; color: #666; border: none; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500;">
                            <i class="ph ph-x"></i> Batal
                        </button>
                        <button type="submit" class="btn-submit" style="flex: 1; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500;">
                            <i class="ph ph-check"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logoutModalOverlay" style="display: none;">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3 style="margin: 0; font-size: 18px; color: #333;">
                    <i class="ph ph-sign-out" style="margin-right: 8px;"></i>Konfirmasi Logout
                </h3>
            </div>
            <div class="modal-body" style="padding: 20px; text-align: center;">
                <p style="margin: 0; color: #666; font-size: 15px;">Apakah Anda yakin ingin keluar dari akun Anda?</p>
            </div>
            <div class="modal-footer" style="display: flex; gap: 10px; justify-content: center; padding: 0 20px 20px;">
                <button class="btn-cancel" onclick="cancelLogout()" style="padding: 10px 20px; background: #f5f5f5; color: #666; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
                    <i class="ph ph-x"></i> Batal
                </button>
                <button class="btn-confirm" onclick="confirmLogout()" style="padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
                    <i class="ph ph-sign-out"></i> Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/uprofil.js') }}?v={{ time() }}"></script>
    <script>
        // Listen untuk update avatar dari halaman edit profil
        if (typeof BroadcastChannel !== 'undefined') {
            const channel = new BroadcastChannel('profile_update');
            channel.onmessage = (event) => {
                if (event.data.type === 'avatar_updated') {
                    document.querySelectorAll('.user-icon-img, .avatar__image, .profile-avatar img').forEach(img => {
                        img.src = event.data.avatar;
                    });
                    console.log('🔄 Avatar synced from other tab');
                }
            };
        }
    </script>
</body>
</html>
