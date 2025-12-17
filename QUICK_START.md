# QUICK START GUIDE - Implementasi Optimasi

## 📋 Checklist Implementasi

### 1. Backend Setup (5 menit)

```bash
# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 2. Update Blade Templates (10-15 menit per file)

#### A. Gunakan Layout Baru

Ubah semua blade files untuk menggunakan layout app.blade.php:

```blade
@extends('layouts.app')

@section('title', 'Judul Halaman - Prismo')
@section('description', 'Deskripsi halaman untuk SEO')
@section('keywords', 'keyword1, keyword2, keyword3')

@section('content')
    <!-- Konten halaman -->
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/page-specific.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/page-specific.js') }}" defer></script>
@endpush
```

#### B. Optimasi Gambar dengan Lazy Loading

Ubah tag `<img>` menjadi:

```blade
<!-- Before -->
<img src="{{ asset('images/photo.jpg') }}" alt="Photo">

<!-- After -->
<img data-src="{{ asset('images/photo.jpg') }}"
     alt="Photo"
     loading="lazy"
     width="800"
     height="600">
```

#### C. Tambahkan CSS & JS Optimization Files

Di setiap halaman, tambahkan di bagian head atau sebelum closing body:

```blade
<!-- Di <head> setelah CSS lainnya -->
<link rel="stylesheet" href="{{ asset('css/optimization.css') }}">
<link rel="stylesheet" href="{{ asset('css/components.css') }}">
<link rel="stylesheet" href="{{ asset('css/utilities.css') }}">

<!-- Sebelum </body> -->
<script src="{{ asset('js/performance.js') }}" defer></script>
<script src="{{ asset('js/accessibility.js') }}" defer></script>
<script src="{{ asset('js/ui-components.js') }}" defer></script>

<!-- Optional: Performance monitoring (development only) -->
<script src="{{ asset('js/performance-monitor.js') }}" defer></script>
```

### 3. Form Validation Update (5 menit per form)

```blade
<!-- Tambahkan data-validate attribute -->
<form method="POST" action="..." data-validate>
    @csrf

    <div class="form-group">
        <label for="email" class="form-label required">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            class="form-control"
            required
            aria-required="true">
        <!-- Error message akan ditampilkan otomatis -->
    </div>

    <button type="submit" class="btn btn-primary">
        Submit
    </button>
</form>
```

### 4. Toast Notifications (Mengganti Alert)

```javascript
// Before
alert("Data berhasil disimpan");

// After
window.toast.success("Data berhasil disimpan");
window.toast.error("Terjadi kesalahan");
window.toast.warning("Perhatian!");
window.toast.info("Informasi penting");
```

### 5. Accessibility Improvements

#### A. Tambahkan ARIA Labels pada Button Icon

```blade
<!-- Before -->
<button onclick="deleteItem()">
    <i class="fa fa-trash"></i>
</button>

<!-- After -->
<button onclick="deleteItem()" aria-label="Hapus item">
    <i class="fa fa-trash" aria-hidden="true"></i>
</button>
```

#### B. Tambahkan Main Content ID

```blade
<!-- Di container utama halaman -->
<main id="main-content" class="container">
    <!-- Konten utama -->
</main>
```

### 6. SEO Meta Tags untuk Setiap Halaman

Contoh untuk halaman dashboard admin:

```blade
@section('title', 'Dashboard Admin - Prismo')
@section('description', 'Dashboard admin Prismo untuk mengelola mitra, customer, booking, dan laporan.')
@section('keywords', 'admin dashboard, prismo admin, kelola mitra, kelola booking')

@section('og_title', 'Dashboard Admin - Prismo')
@section('og_description', 'Kelola semua aspek platform Prismo dari satu dashboard.')
```

## 🎯 Prioritas Implementasi

### High Priority (Implementasi Dulu)

1. ✅ Middleware sudah terimplementasi (SecurityHeaders, PerformanceOptimization)
2. 🔄 Update 5 halaman utama:
    - Landing page
    - Login/Register
    - Admin Dashboard
    - Mitra Dashboard
    - Customer Dashboard

### Medium Priority

3. 🔄 Update halaman detail:
    - Form pages
    - Profile pages
    - Booking pages

### Low Priority

4. 🔄 Update halaman lainnya:
    - Help/FAQ
    - Terms & Conditions
    - Privacy Policy

## 📝 Template Contoh

### Contoh: Dashboard Admin dengan Full Optimization

```blade
@extends('layouts.app')

@section('title', 'Dashboard Admin - Prismo')
@section('description', 'Dashboard admin untuk mengelola mitra, customer, booking, voucher, dan laporan platform Prismo.')
@section('keywords', 'admin dashboard, prismo, kelola mitra, kelola customer, kelola booking')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')
<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar" role="navigation" aria-label="Menu utama">
        <!-- Sidebar content -->
    </aside>

    <!-- Main Content -->
    <main id="main-content" class="main-content">
        <header class="topbar">
            <h1 class="sr-only">Dashboard Admin</h1>
            <!-- Topbar content -->
        </header>

        <section class="content">
            <!-- Dashboard cards -->
            <div class="stats-grid">
                <article class="stat-card">
                    <h2>Total Mitra</h2>
                    <p class="stat-value">{{ $totalMitra }}</p>
                </article>
                <!-- More cards -->
            </div>
        </section>
    </main>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-dashboard.js') }}" defer></script>
@endpush
```

## 🧪 Testing Checklist

Setelah implementasi, test:

### Performance

-   [ ] Jalankan Lighthouse audit (target: >90 score)
-   [ ] Cek loading time < 3 detik
-   [ ] Verify GZIP compression aktif
-   [ ] Check cache headers

### Accessibility

-   [ ] Test dengan keyboard navigation (Tab, Enter, ESC)
-   [ ] Test dengan screen reader
-   [ ] Verify semua images punya alt text
-   [ ] Check color contrast ratio

### SEO

-   [ ] Check meta tags di setiap halaman
-   [ ] Verify sitemap.xml accessible
-   [ ] Check robots.txt
-   [ ] Test Open Graph tags (Facebook debugger)

### Functionality

-   [ ] Test form validation
-   [ ] Test toast notifications
-   [ ] Test lazy loading images
-   [ ] Test modal interactions

## 🚀 Quick Commands

```bash
# Development
npm run dev

# Production build
npm run build

# Clear caches
php artisan optimize:clear

# Recreate caches
php artisan optimize

# Optimize images
php artisan images:optimize --quality=80

# Run tests
php artisan test
```

## 📞 Need Help?

Jika ada error atau pertanyaan:

1. Check browser console untuk error JS
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify semua files sudah ter-upload
4. Clear browser cache

## ✨ Next Steps

Setelah semua terimplementasi:

1. Monitor performance dengan Google Analytics
2. Set up error tracking (Sentry)
3. Regular performance audits
4. Update dependencies regularly
