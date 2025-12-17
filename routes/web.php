<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\MitraDashboardController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\Customer\EditProfilController;
use App\Http\Controllers\Mitra\ProfilController;
use App\Http\Controllers\Customer\VoucherController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\DetailMitraController;
use App\Http\Controllers\Mitra\AntrianController;
use App\Http\Controllers\Mitra\ReviewController as MitraReviewController;
use App\Http\Controllers\Mitra\SaldoController;
use App\Http\Controllers\Mitra\ProfileUpdateController;
use App\Http\Controllers\Admin\KelolaCustomerController;
use App\Http\Controllers\Admin\KelolaAdminController;
use App\Http\Controllers\Admin\KelolaMitraController;
use App\Http\Controllers\Admin\KelolaVoucherController;
use App\Http\Controllers\Admin\KelolaBookingController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenarikanController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TermsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

// Storage file serving route (untuk development - menangani symlink di Windows)
Route::get('/storage/{path}', function ($path) {
    // Decode path untuk menangani karakter khusus
    $path = urldecode($path);

    $filePath = storage_path('app/public/' . $path);

    if (!file_exists($filePath)) {
        Log::warning('File not found: ' . $filePath . ' | Requested path: ' . $path);
        abort(404, 'File not found');
    }

    // Check if file is readable
    if (!is_readable($filePath)) {
        Log::error('File not readable: ' . $filePath);
        abort(403, 'File not accessible');
    }

    $mimeType = mime_content_type($filePath);

    return Response::file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
        'Access-Control-Allow-Origin' => '*',
    ]);
})->where('path', '.*')->name('storage.file');
// Landing Page
Route::get('/', function () {
    return view('landingpage.lp');
})->name('home')->middleware('guest');

Route::get('/tentang', function () {
    return view('landingpage.tentang');
})->name('tentang');

// Syarat dan Ketentuan Page
Route::get('/syarat-ketentuan', function () {
    return view('landingpage.syarat-ketentuan');
})->name('syarat.ketentuan');

// Syarat dan Ketentuan Page (New - Professional)
Route::get('/syarat-dan-ketentuan', [TermsController::class, 'index'])->name('terms');

// Cookie Policy Page
Route::get('/cookie-policy', function () {
    return view('cookie-policy');
})->name('cookie.policy');

// Form Pendaftaran Mitra (Protected - setelah verifikasi email)
Route::middleware('auth')->group(function () {
    Route::get('/mitra/form-mitra', [App\Http\Controllers\MitraProfileController::class, 'showForm'])->name('mitra.form');
    Route::post('/mitra/form-mitra', [App\Http\Controllers\MitraProfileController::class, 'submitForm'])->name('mitra.form.submit');
});

Route::get('/mitra/form-mitra-pending', function () {
    if (!Auth::check() || Auth::user()->role !== 'mitra') {
        return redirect('/login');
    }

    // Jika status rejected, redirect ke form untuk mengisi ulang
    if (Auth::user()->approval_status === 'rejected') {
        return redirect('/mitra/form-mitra')->with('info', 'Pendaftaran Anda ditolak. Silakan perbaiki dan kirim ulang formulir.');
    }

    return view('mitra.form-mitra-pending');
})->name('mitra.form.pending')->middleware('auth');

// API endpoint untuk cek status approval
Route::get('/mitra/check-approval-status', function () {
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json([
        'approval_status' => Auth::user()->approval_status
    ]);
})->name('check-approval-status')->middleware('auth');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.auth', ['isLogin' => true]);
    })->name('login');

    // Rate limit login attempts: 5 per minute
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.submit');

    Route::get('/register', function () {
        return view('auth.auth', ['isLogin' => false]);
    })->name('register');

    // Rate limit registration: 10 per 5 minutes (lebih longgar untuk development)
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:10,5')
        ->name('register.store');

    // Google OAuth Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth-google-callback', [AuthController::class, 'handleGoogleCallback']);

    // Magic Link Routes - Rate limit to prevent abuse
    Route::post('/auth/magic-link', [AuthController::class, 'sendMagicLink'])
        ->middleware('throttle:3,60')
        ->name('auth.magic-link');
    Route::get('/auth/magic-link/verify', [AuthController::class, 'verifyMagicLink'])->name('auth.magic-link.verify');

    // Reset Password Routes
    Route::get('/auth/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('auth.reset-password');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Account Disabled Page
Route::get('/account-disabled', function () {
    return view('account-disabled');
})->name('account.disabled')->middleware('auth');

// Email Verification Routes (tidak perlu auth - user belum login saat register)
Route::get('/email/verify', [AuthController::class, 'verifyEmail'])->name('verification.verify');
Route::get('/verifemail', [AuthController::class, 'verificationNotice'])->name('verification.notice');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('verification.resend');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Dashboard mitra - cek apakah profil sudah dilengkapi dan approved
    Route::get('/dashboard-mitra', [MitraDashboardController::class, 'index'])->name('dashboard.mitra');

    // Profile Photo Routes
    Route::post('/profile/photo/upload', [ProfilePhotoController::class, 'upload'])->name('profile.photo.upload');
    Route::get('/profile/photo', [ProfilePhotoController::class, 'getPhoto'])->name('profile.photo.get');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'delete'])->name('profile.photo.delete');

    // Admin Routes - Require authentication and admin role
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/kelolaadmin/kelolaadmin', [KelolaAdminController::class, 'index'])->name('admin.kelolaadmin');
        Route::post('/kelolaadmin/store', [KelolaAdminController::class, 'store'])->name('admin.kelolaadmin.store');
        Route::delete('/kelolaadmin/{id}', [KelolaAdminController::class, 'destroy'])->name('admin.kelolaadmin.destroy');

        Route::get('/kelolamitra/kelolamitra', [KelolaMitraController::class, 'index'])->name('admin.kelolamitra');
        Route::get('/kelolamitra/{id}/form', [KelolaMitraController::class, 'show'])->name('admin.kelolamitra.form');
        Route::post('/kelolamitra/{id}/approve', [KelolaMitraController::class, 'approve'])->name('admin.kelolamitra.approve');
        Route::post('/kelolamitra/{id}/reject', [KelolaMitraController::class, 'reject'])->name('admin.kelolamitra.reject');

        Route::get('/kelolacustomer/kelolacustomer', [KelolaCustomerController::class, 'index'])->name('admin.kelolacustomer');

        Route::get('/kelolavoucher/kelolavoucher', [KelolaVoucherController::class, 'index'])->name('admin.kelolavoucher');

        Route::get('/kelolabooking/kelolabooking', [KelolaBookingController::class, 'index'])->name('admin.kelolabooking');

        // Booking Management Actions
        Route::post('/kelolabooking/{id}/confirm', [KelolaBookingController::class, 'confirmBooking'])->name('admin.booking.confirm');
        Route::post('/kelolabooking/{id}/cancel', [KelolaBookingController::class, 'cancelBooking'])->name('admin.booking.cancel');
        Route::post('/kelolabooking/{id}/refund', [KelolaBookingController::class, 'completeRefund'])->name('admin.booking.refund');

        Route::get('/laporan/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('admin.laporan.export');

        Route::get('/dashboard/penarikan', [PenarikanController::class, 'index'])->name('admin.penarikan');

        // Reports
        Route::get('/reports/earnings-export', [\App\Http\Controllers\Admin\EarningsReportController::class, 'exportEarnings'])->name('admin.reports.earnings-export');

        // User Status Management
        Route::post('/user/{userId}/toggle-status', [\App\Http\Controllers\Admin\UserStatusController::class, 'toggleStatus'])->name('admin.user.toggle-status');
    });

    // Mitra Routes
    Route::get('/mitra/saldo/saldo', [SaldoController::class, 'index'])->name('mitra.saldo');

    Route::get('/mitra/saldo/history', [SaldoController::class, 'history'])->name('mitra.saldo.history');
    Route::get('/mitra/saldo/export', [SaldoController::class, 'exportReport'])->name('mitra.saldo.export');

    // Financial Reports
    Route::get('/mitra/reports/data', [\App\Http\Controllers\Mitra\FinancialReportController::class, 'getReportData'])->name('mitra.reports.data');
    Route::get('/mitra/reports/export-pdf', [\App\Http\Controllers\Mitra\FinancialReportController::class, 'exportPDF'])->name('mitra.reports.pdf');
    Route::get('/mitra/reports/export-excel', [\App\Http\Controllers\Mitra\FinancialReportController::class, 'exportExcel'])->name('mitra.reports.excel');

    Route::get('/mitra/antrian/antrian', [AntrianController::class, 'index'])->name('mitra.antrian');
    Route::post('/mitra/booking/update-status', [AntrianController::class, 'updateStatus'])->name('mitra.booking.update-status');

    Route::get('/mitra/review/review', [MitraReviewController::class, 'index'])->name('mitra.review');
    Route::post('/mitra/review/reply', [MitraReviewController::class, 'reply'])->name('mitra.review.reply');
    Route::post('/mitra/review/update-reply', [MitraReviewController::class, 'updateReply'])->name('mitra.review.update-reply');
    Route::post('/mitra/review/delete-reply', [MitraReviewController::class, 'deleteReply'])->name('mitra.review.delete-reply');

    // Mitra Profile Update API endpoints
    Route::post('/mitra/update-operational-hours', [ProfileUpdateController::class, 'updateOperationalHours'])->name('mitra.update.operational');
    Route::post('/mitra/update-service-prices', [ProfileUpdateController::class, 'updateServicePrices'])->name('mitra.update.prices');
    Route::post('/mitra/update-status', [ProfileUpdateController::class, 'updateStatus'])->name('mitra.update.status');
    Route::post('/mitra/update-custom-services', [ProfileUpdateController::class, 'updateCustomServices'])->name('mitra.update.services');

    Route::get('/mitra/profil/profil', [ProfilController::class, 'show'])->name('mitra.profil');

    Route::get('/mitra/profil/edit-profile', [ProfilController::class, 'edit'])->name('mitra.profil.edit');

    Route::post('/mitra/profil/update', [ProfilController::class, 'update'])->name('mitra.profil.update');

    // Change Password Routes
    Route::get('/profile/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [ChangePasswordController::class, 'changePassword'])->name('profile.change-password.submit');

    // Help & FAQ Route
    Route::get('/help/faq', function () {
        return view('mitra.profil.help-faq');
    })->name('help.faq');

    // Customer Routes
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/dashboard/dashU', [CustomerDashboardController::class, 'index']); // Alias untuk backward compatibility

    Route::get('/customer/booking/Rbooking', [BookingController::class, 'index'])->name('customer.booking');
    Route::post('/customer/booking/reschedule', [BookingController::class, 'reschedule'])->name('customer.booking.reschedule');
    Route::post('/customer/review/submit', [BookingController::class, 'submitReview'])->name('customer.review.submit');
    Route::post('/customer/review/update', [BookingController::class, 'updateReview'])->name('customer.review.update');

    Route::get('/customer/voucher/voucher', [VoucherController::class, 'index'])->name('customer.voucher');
    Route::post('/customer/voucher/claim/{id}', [VoucherController::class, 'claim'])->name('customer.voucher.claim');

    Route::get('/customer/profil/uprofil', [CustomerProfileController::class, 'index'])->name('customer.profil');

    Route::get('/customer/profil/eprofil', [EditProfilController::class, 'index'])->name('customer.profil.edit');

    // Customer Change Password & Help Routes
    Route::get('/customer/profile/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('customer.change-password');
    Route::get('/customer/help/faq', function () {
        return view('customer.profil.help-faq');
    })->name('customer.help.faq');

    Route::get('/customer/detail-mitra/minipro/{id}', [DetailMitraController::class, 'show'])->name('customer.detail-mitra');

    Route::get('/customer/atur-booking/booking/{mitraId}', [\App\Http\Controllers\Customer\AturBookingController::class, 'show'])->name('customer.atur-booking');
});
