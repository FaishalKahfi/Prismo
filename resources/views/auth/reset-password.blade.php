<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Prismo</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/Icon-prismo.png') }}">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 16px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 60px;
            height: auto;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        .header p {
            font-size: 15px;
            opacity: 0.9;
            margin: 0;
        }

        .content {
            padding: 40px 30px;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 16px 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }

        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #1565c0;
            line-height: 1.5;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #212121;
            font-size: 14px;
        }

        .required {
            color: #f44336;
            margin-left: 4px;
        }

        .input-wrapper {
            position: relative;
        }

        input[type="password"],
        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 14px 50px 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            outline: none;
            background: #fafafa;
        }

        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="text"]:focus {
            border-color: #2196f3;
            background: white;
            box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
        }

        input[type="email"] {
            background: #f5f5f5;
            color: #666;
            cursor: not-allowed;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            font-size: 22px;
            user-select: none;
            transition: color 0.2s;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: #2196f3;
        }

        .toggle-password i {
            display: block;
        }

        .password-requirements {
            margin-top: 8px;
            font-size: 13px;
            color: #666;
        }

        .password-requirements ul {
            margin: 8px 0 0 20px;
            padding: 0;
        }

        .password-requirements li {
            margin: 4px 0;
            color: #999;
        }

        .password-requirements li.valid {
            color: #4caf50;
        }

        .btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.4);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn .btn-text {
            display: inline-block;
        }

        .btn .loading-spinner {
            display: none;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .btn.loading .btn-text {
            opacity: 0;
        }

        .btn.loading .loading-spinner {
            display: block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #2196f3;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            margin: 0;
            font-size: 13px;
            color: #999;
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }

            .header {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Prismo">
            </div>
            <h1>Buat Password Baru</h1>
            <p>Masukkan password baru untuk akun Anda</p>
        </div>

        <div class="content">
            @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="info-box">
                <p><strong>📧 Email:</strong> {{ $email }}</p>
            </div>

            <form action="{{ route('auth.reset-password.submit') }}" method="POST" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="{{ $email }}" readonly>
                </div>

                <div class="form-group">
                    <label>Password Baru <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder="Masukkan password baru" required minlength="6">
                        <span class="toggle-password" onclick="togglePassword('password', this)">
                            <i class="ph ph-eye"></i>
                        </span>
                    </div>
                    <div class="password-requirements">
                        <p style="margin: 8px 0 4px 0; font-weight: 600; color: #555;">Password harus:</p>
                        <ul id="password-rules">
                            <li id="rule-length">Minimal 6 karakter</li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Masukkan ulang password baru" required minlength="6">
                        <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                            <i class="ph ph-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn" id="submitBtn">
                    <span class="btn-text">Reset Password</span>
                    <div class="loading-spinner"></div>
                </button>

                <a href="/login" class="back-link">← Kembali ke halaman login</a>
            </form>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Prismo. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            const icon = iconElement.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ph ph-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'ph ph-eye';
            }
        }

        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const form = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const ruleLength = document.getElementById('rule-length');

        // Validate password requirements
        passwordInput.addEventListener('input', function() {
            const password = this.value;

            // Check length
            if (password.length >= 6) {
                ruleLength.classList.add('valid');
            } else {
                ruleLength.classList.remove('valid');
            }
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (password.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter');
                return;
            }

            if (password !== confirm) {
                e.preventDefault();
                alert('Password dan Konfirmasi Password tidak sama');
                return;
            }

            // Show loading
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        // Prevent back button after success
        @if(session('success'))
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        setTimeout(function() {
            window.location.href = '/dashboard';
        }, 2000);
        @endif
    </script>
</body>
</html>
