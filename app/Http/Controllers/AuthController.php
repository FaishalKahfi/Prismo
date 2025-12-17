<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Services\NotificationService;

class AuthController extends Controller
{
    // Login with email and password
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Check if email is verified BEFORE regenerating session
            if (!Auth::user()->email_verified_at) {
                // Logout user karena belum verifikasi
                Auth::logout();

                // Kirim ulang email verifikasi
                $this->sendVerificationEmail($request->email);

                return redirect()->route('verification.notice')
                    ->with('error', 'Email Anda belum diverifikasi. Kami telah mengirim ulang email verifikasi.');
            }

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Invalidate previous session
            $request->session()->migrate(true);

            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            if (Auth::user()->role === 'mitra') {
                // Jika mitra belum complete profile, redirect ke form
                if (!Auth::user()->profile_completed) {
                    return redirect()->intended('/mitra/form-mitra');
                }

                // Jika status pending atau rejected, redirect ke form untuk mengisi ulang
                if (Auth::user()->approval_status === 'pending' || Auth::user()->approval_status === 'rejected') {
                    return redirect()->intended('/mitra/form-mitra');
                }

                return redirect()->intended('/dashboard-mitra');
            }

            // Customer - redirect to customer dashboard
            return redirect()->intended('/customer/dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    // Register new user
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
            'terms' => 'accepted',
            'role' => 'required|in:customer,mitra',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan login atau gunakan email lain.',
        ]);

        $user = User::create([
            'name' => explode('@', $request->email)[0],
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'avatar' => '/images/profile.png',
        ]);

        // DO NOT auto-login user - they must verify email first
        // Auth::login($user); // REMOVED: User harus verifikasi email dulu

        // Notify admins about new customer registration
        if ($request->role === 'customer') {
            NotificationService::newCustomerRegistration($user);
        }

        // Send verification email
        $this->sendVerificationEmail($user->email);

        // Store expiry time in cache (persists across sessions)
        cache()->put('verification_expiry_' . $user->id, now()->addMinutes(5)->timestamp, now()->addMinutes(10));

        // Store user info in session untuk ditampilkan di halaman verifikasi
        session(['pending_verification_email' => $user->email, 'pending_verification_role' => $user->role]);

        return redirect()->route('verification.notice')->with('success', 'Akun berhasil dibuat! Silakan cek email untuk verifikasi.');
    }

    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Google OAuth: Redirect to Google
    public function redirectToGoogle(Request $request)
    {
        // Save role and action (login/register) to session before redirecting
        if ($request->has('role')) {
            session(['oauth_role' => $request->role]);
        }

        if ($request->has('action')) {
            session(['oauth_action' => $request->action]);
        }

        return Socialite::driver('google')->redirect();
    }

    // Google OAuth: Handle callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $action = session('oauth_action', 'login'); // Default login jika tidak ada parameter

            // Check if email already exists
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            // CASE 1: LOGIN ACTION
            if ($action === 'login') {
                if ($existingUser) {
                    // If user exists but doesn't have google_id (registered via email/password)
                    if (!$existingUser->google_id) {
                        // Update google_id untuk enable OAuth login next time
                        $existingUser->google_id = $googleUser->getId();
                        // Set Google avatar if user doesn't have one or has default
                        if (!$existingUser->avatar || $existingUser->avatar === '/images/profile.png') {
                            $existingUser->avatar = $googleUser->getAvatar();
                        }
                        $existingUser->save();
                    } else {
                        // User already has google_id, update avatar to latest Google avatar
                        $existingUser->avatar = $googleUser->getAvatar();
                        $existingUser->save();
                    }

                    // Update email_verified_at if not set (Google auto-verifies)
                    if (!$existingUser->email_verified_at) {
                        $existingUser->email_verified_at = now();
                        $existingUser->save();
                    }

                    // If user exists, login
                    Auth::login($existingUser);

                    // Clear session
                    session()->forget(['oauth_role', 'oauth_action']);

                    // Redirect based on role
                    if ($existingUser->role === 'admin') {
                        return redirect()->intended('/admin/dashboard');
                    }

                    if ($existingUser->role === 'mitra') {
                        // Jika mitra belum complete profile, redirect ke form
                        if (!$existingUser->profile_completed) {
                            return redirect()->intended('/mitra/form-mitra');
                        }

                        // Jika status pending atau rejected, redirect ke form untuk mengisi ulang
                        if ($existingUser->approval_status === 'pending' || $existingUser->approval_status === 'rejected') {
                            return redirect()->intended('/mitra/form-mitra');
                        }

                        return redirect()->intended('/dashboard-mitra');
                    }

                    return redirect()->intended('/dashboard');
                } else {
                    // Email tidak terdaftar saat LOGIN
                    session()->forget(['oauth_role', 'oauth_action']);
                    return redirect('/login')->with('error', 'Email belum terdaftar. Silakan daftar terlebih dahulu.');
                }
            }

            // CASE 2: REGISTER ACTION
            if ($action === 'register') {
                if ($existingUser) {
                    // Email sudah terdaftar saat REGISTER
                    session()->forget(['oauth_role', 'oauth_action']);
                    return redirect('/register')->with('error', 'Email sudah terdaftar. Silakan login.');
                } else {
                    // Create new user
                    $role = session('oauth_role', 'customer');

                    $user = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar() ?: '/images/profile.png',
                        'role' => $role,
                        'email_verified_at' => now(), // Google OAuth auto-verifies email
                    ]);

                    Auth::login($user);

                    // Clear session
                    session()->forget(['oauth_role', 'oauth_action']);

                    // Redirect based on role
                    if ($user->role === 'mitra') {
                        // Mitra baru harus lengkapi profil
                        return redirect('/mitra/form-mitra');
                    }

                    return redirect()->intended('/dashboard');
                }
            }

            // Fallback
            session()->forget(['oauth_role', 'oauth_action']);
            return redirect('/login')->with('error', 'Invalid OAuth request');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Google OAuth Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            session()->forget(['oauth_role', 'oauth_action']);
            return redirect('/login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }

    // Magic Link: Send email
    public function sendMagicLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        // Check if user exists
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar dalam sistem.'
            ], 404);
        }

        $token = Str::random(64);
        $expiresAt = now()->addMinutes(30);

        // Delete old tokens for this email
        DB::table('magic_link_tokens')->where('email', $email)->delete();

        // Store new token in database
        DB::table('magic_link_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send email with magic link
        $magicLink = url('/auth/reset-password?token=' . $token);

        try {
            Mail::send('emails.reset-password', [
                'userName' => $user->name,
                'magicLink' => $magicLink,
                'expiresIn' => '30 menit'
            ], function ($message) use ($email, $user) {
                $message->to($email, $user->name)
                        ->subject('Reset Password - Prismo');
            });

            return response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirim ke email Anda.'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send reset password email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. Silakan coba lagi.'
            ], 500);
        }
    }

    // Show Reset Password Form
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect('/login')->with('error', 'Link reset password tidak valid');
        }

        // Verify token exists and not expired
        $magicLinkToken = DB::table('magic_link_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$magicLinkToken) {
            return redirect('/login')->with('error', 'Link reset password sudah kadaluarsa atau tidak valid');
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $magicLinkToken->email]);
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $token = $request->token;

        // Verify token
        $magicLinkToken = DB::table('magic_link_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$magicLinkToken) {
            return back()->with('error', 'Link reset password sudah kadaluarsa atau tidak valid');
        }

        // Find user
        $user = User::where('email', $magicLinkToken->email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan');
        }

        // Update password
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete used token
        DB::table('magic_link_tokens')->where('token', $token)->delete();

        // Auto login
        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Password berhasil diubah!');
    }

    // Magic Link: Verify and login
    public function verifyMagicLink(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect('/login')->with('error', 'Invalid magic link');
        }

        $magicLinkToken = DB::table('magic_link_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$magicLinkToken) {
            return redirect('/login')->with('error', 'Magic link expired or invalid');
        }

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $magicLinkToken->email],
            ['name' => explode('@', $magicLinkToken->email)[0]]
        );

        // Delete used token
        DB::table('magic_link_tokens')->where('token', $token)->delete();

        Auth::login($user);

        return redirect('/dashboard');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    // Dashboard (protected route)
    public function dashboard()
    {
        $user = Auth::user();

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role === 'mitra') {
            // Cek apakah mitra sudah melengkapi profil
            if (!$user->profile_completed) {
                return redirect('/mitra/form-mitra')->with('info', 'Silakan lengkapi profil Anda terlebih dahulu.');
            }

            // Cek status approval
            if ($user->approval_status === 'pending' || $user->approval_status === 'rejected') {
                return redirect('/mitra/form-mitra-pending');
            }

            if ($user->approval_status === 'approved') {
                return redirect('/dashboard-mitra');
            }
        }

        // Default: customer - redirect to CustomerDashboardController
        return redirect('/customer/dashboard');
    }

    // Send verification email
    protected function sendVerificationEmail($email)
    {
        $token = Str::random(64);
        $expiresAt = now()->addMinutes(5);

        // Delete old tokens
        DB::table('email_verification_tokens')
            ->where('email', $email)
            ->delete();

        // Store new token
        DB::table('email_verification_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get user name
        $user = User::where('email', $email)->first();
        $userName = $user ? $user->name : 'User';

        // Send email with HTML template
        $verificationLink = url('/email/verify?token=' . $token);

        Mail::send('emails.verify-email', [
            'verificationLink' => $verificationLink,
            'userName' => $userName
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Verifikasi Email - Prismo');
        });
    }

    // Show verification notice page
    public function verificationNotice()
    {
        // Jika user sedang login dan sudah terverifikasi, redirect ke dashboard
        if (Auth::check() && Auth::user()->email_verified_at) {
            // Redirect based on role
            if (Auth::user()->role === 'mitra') {
                return redirect('/dashboard-mitra');
            }
            return redirect('/dashboard');
        }

        // Get email dari session (untuk user yang baru register)
        $email = session('pending_verification_email');
        $role = session('pending_verification_role', 'customer');

        // Jika tidak ada email di session dan user tidak login, redirect ke register
        if (!$email && !Auth::check()) {
            return redirect()->route('register')->with('error', 'Silakan daftar terlebih dahulu.');
        }

        // Jika user sudah login, gunakan email dari auth
        if (Auth::check()) {
            $email = Auth::user()->email;
            $userId = Auth::id();
        } else {
            // Cari user berdasarkan email dari session
            $user = User::where('email', $email)->first();
            if (!$user) {
                return redirect()->route('register')->with('error', 'User tidak ditemukan.');
            }
            $userId = $user->id;
        }

        // Get expiry time from cache (persists across sessions/logout)
        $cacheKey = 'verification_expiry_' . $userId;
        $expiryTime = cache()->get($cacheKey);

        // If no expiry time exists, create one
        if (!$expiryTime) {
            $expiryTime = now()->addMinutes(5)->timestamp;
            cache()->put($cacheKey, $expiryTime, now()->addMinutes(10));
        }

        return view('auth.verifemail', [
            'expiryTime' => $expiryTime,
            'email' => $email,
            'role' => $role
        ]);
    }

    // Resend verification email
    public function resendVerification(Request $request)
    {
        // Jika user sedang login
        if (Auth::check()) {
            if (Auth::user()->email_verified_at) {
                return redirect('/dashboard');
            }

            $email = Auth::user()->email;
            $userId = Auth::id();
        } else {
            // Ambil email dari session
            $email = session('pending_verification_email');
            if (!$email) {
                return redirect()->route('register')->with('error', 'Silakan daftar terlebih dahulu.');
            }

            $user = User::where('email', $email)->first();
            if (!$user) {
                return redirect()->route('register')->with('error', 'User tidak ditemukan.');
            }
            $userId = $user->id;
        }

        $this->sendVerificationEmail($email);

        // Reset expiry time in cache (5 minutes from now)
        $cacheKey = 'verification_expiry_' . $userId;
        $expiryTime = now()->addMinutes(5)->timestamp;
        cache()->put($cacheKey, $expiryTime, now()->addMinutes(10));

        return back()->with('success', 'Email verifikasi telah dikirim ulang!');
    }

    // Verify email
    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('verification.notice')->with('error', 'Token verifikasi tidak valid');
        }

        $verificationToken = DB::table('email_verification_tokens')
            ->where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verificationToken) {
            return redirect()->route('verification.notice')->with('error', 'Link verifikasi kadaluarsa atau tidak valid');
        }

        // Update user email_verified_at
        $user = User::where('email', $verificationToken->email)->first();

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            // Delete used token
            DB::table('email_verification_tokens')->where('token', $token)->delete();

            // Clear session data
            session()->forget(['pending_verification_email', 'pending_verification_role']);

            // DO NOT auto-login - redirect to login page
            return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login dengan akun Anda.');
        }

        return redirect()->route('verification.notice')->with('error', 'Terjadi kesalahan');
    }
}
