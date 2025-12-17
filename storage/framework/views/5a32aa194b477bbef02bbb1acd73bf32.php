<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="authenticated" content="false">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?php echo e(isset($isLogin) && $isLogin ? 'Login' : 'Register'); ?> | Prismo</title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/Icon-prismo.png')); ?>">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="<?php echo e(asset('css/register.css')); ?>">
</head>

<body>
    <!-- SIGN UP -->
    <div class="form-container sign-up">
        <form id="signup-form" method="POST" action="<?php echo e(route('register.store')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="role" id="role-input" value="customer">
            <div class="logo-container">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Prismo Logo" class="logo">
                <!-- Pilihan Customer/Mitra -->
                <div class="customer-type-toggle">
                    <div class="toggle-buttons">
                        <button type="button" class="type-btn active" data-type="customer">Customer</button>
                        <button type="button" class="type-btn" data-type="mitra">Mitra</button>
                    </div>
                </div>
            </div>
            <h1 class="form-title">Daftar</h1>

            <?php if($errors->any()): ?>
                <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 14px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="input-container email">
                <input type="email" name="email" placeholder="Email" autocomplete="email" required value="<?php echo e(old('email')); ?>">
            </div>

            <div class="input-container password">
                <input type="password" name="password" class="password-input" placeholder="Password" required>
                <i class="ph ph-eye toggle-password"></i>
            </div>

            <div class="input-container password">
                <input type="password" name="confirmPassword" class="password-input" placeholder="Confirm Password" required>
                <i class="ph ph-eye toggle-password"></i>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="terms" name="terms" value="1" required>
                <label for="terms">Dengan membuat akun, Anda Menyetujui <a href="<?php echo e(route('terms')); ?>" target="_blank">Syarat & Ketentuan</a></label>
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
                <img src="<?php echo e(asset('images/google.png')); ?>" alt="Google" class="google-icon">
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
        <form id="signin-form" action="<?php echo e(route('login.submit')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="logo-container">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Prismo Logo" class="logo">
            </div>
            <h1 class="form-title">Masuk</h1>

            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-error">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="input-container email">
                <input type="email" name="email" placeholder="Email" autocomplete="email" required value="<?php echo e(old('email')); ?>">
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

            <button type="button" class="google-btn" onclick="window.location.href='<?php echo e(route('auth.google')); ?>'">
                <img src="<?php echo e(asset('images/google.png')); ?>" alt="Google" class="google-icon">
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
                    <img src="<?php echo e(asset('images/lupapassword.png')); ?>" alt="Prismo Logo" class="modal-logo">
                </div>

                <p class="modal-description">Masukkan email Anda dan kami akan mengirimkan link untuk reset password</p>

                <form class="forgot-password-form" id="forgot-password-form" method="POST" action="<?php echo e(route('auth.magic-link')); ?>">
                    <?php echo csrf_field(); ?>
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
                    <p>Dengan menggunakan layanan kami, Anda menyetujui syarat dan ketentuan berikut:</p>
                    <p>1</p>
                    <p>2</p>
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

    <script src="<?php echo e(asset('js/register.js')); ?>?v=<?php echo e(time()); ?>"></script>

    <?php if(request()->has('tab') && request()->get('tab') === 'register'): ?>
    <script>
        // Auto show register form if URL has ?tab=register
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.sign-up').classList.add('active');
            document.querySelector('.sign-in').classList.remove('active');
        });
    </script>
    <?php endif; ?>

    <?php if(request()->has('tab') && request()->get('tab') === 'login'): ?>
    <script>
        // Auto show login form if URL has ?tab=login
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.sign-in').classList.add('active');
            document.querySelector('.sign-up').classList.remove('active');
        });
    </script>
    <?php endif; ?>

    <?php if(session('success') || session('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show login form if there's a message
            document.querySelector('.sign-in').classList.add('active');
            document.querySelector('.sign-up').classList.remove('active');
        });
    </script>
    <?php endif; ?>

    <!-- Prevent Back Button for authenticated users -->
    <script src="<?php echo e(asset('js/prevent-back.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>

</html>
<?php /**PATH C:\Users\Faishal\Documents\prismo - Copy\resources\views/auth/auth.blade.php ENDPATH**/ ?>