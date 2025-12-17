<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Prismo - Platform Booking Layanan Cuci Motor Terpercaya')</title>
    <meta name="description" content="@yield('description', 'Prismo adalah platform booking layanan cuci motor terpercaya di Indonesia. Temukan mitra cuci motor terdekat, booking online, dan nikmati layanan terbaik.')">
    <meta name="keywords" content="@yield('keywords', 'cuci motor, booking cuci motor, layanan cuci motor, cuci motor online, prismo, motor cleaning')">
    <meta name="author" content="Prismo">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Prismo - Platform Booking Layanan Cuci Motor')">
    <meta property="og:description" content="@yield('og_description', 'Platform booking layanan cuci motor terpercaya dengan berbagai pilihan mitra dan paket layanan.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="Prismo">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Prismo - Platform Booking Layanan Cuci Motor')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Platform booking layanan cuci motor terpercaya dengan berbagai pilihan mitra dan paket layanan.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/logo.png'))">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    <!-- Preload critical assets -->
    <link rel="preload" href="{{ asset('images/logo.png') }}" as="image">
    <link rel="preload" href="{{ asset('css/optimization.css') }}" as="style">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/optimization.css') }}">
    @stack('styles')

    <!-- Structured Data - Organization -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Prismo",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "description": "Platform booking layanan cuci motor terpercaya di Indonesia",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+62-822-2767-1561",
            "contactType": "Customer Service",
            "availableLanguage": ["Indonesian"]
        },
        "sameAs": [
            "@yield('social_facebook', '')",
            "@yield('social_instagram', '')",
            "@yield('social_twitter', '')"
        ]
    }
    </script>

    @stack('structured_data')
</head>
<body>
    <!-- Skip to main content link -->
    <a href="#main-content" class="skip-to-main">Skip to main content</a>

    <!-- ARIA live region for announcements -->
    <div id="aria-live-region" class="sr-only" aria-live="polite" aria-atomic="true"></div>

    @yield('content')

    <!-- Performance Scripts -->
    <script src="{{ asset('js/performance.js') }}" defer></script>
    <script src="{{ asset('js/accessibility.js') }}" defer></script>

    @stack('scripts')
</body>
</html>
