<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Mini Profile - Prismo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Icon-prismo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/minipro.css') }}?v={{ time() }}">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Prismo Logo">
            </div>
            <a href="{{ url('/customer/dashboard/dashU') }}" class="back-btn">← Kembali</a>
        </div>
    </header>

    <div class="gallery-container">
        <div class="gallery-slider">
            <div class="gallery-track" id="galleryTrack">
                <!-- Slides will be populated by JavaScript -->
            </div>
            <button class="gallery-nav prev" onclick="moveSlide(-1)">‹</button>
            <button class="gallery-nav next" onclick="moveSlide(1)">›</button>
        </div>
        <div class="gallery-dots" id="dotsContainer"></div>
    </div>

    <div class="main-content">
        <div class="title-section">
            <h1 id="businessName">
                <!-- Business name will be populated by JavaScript -->
                <span class="rating" id="businessRating"></span>
            </h1>
        </div>

        <p class="description" id="businessDescription">
            <!-- Description will be populated by JavaScript -->
        </p>

        <div class="info-item address" id="businessAddress">
            <!-- Address will be populated by JavaScript -->
        </div>
        <div class="info-item phone" id="businessPhone">
            <!-- Phone will be populated by JavaScript -->
        </div>
        <div class="info-item time" id="businessHours">
            <!-- Hours will be populated by JavaScript -->
        </div>
    </div>

    <h2 class="services-title">Kategori Layanan</h2>

    <div class="services-container">
        <div class="services-slider">
            <button class="slider-nav prev" onclick="moveServices(-1)">‹</button>
            <div class="services-track" id="servicesTrack">
                <!-- Service cards will be populated by JavaScript -->
            </div>
            <button class="slider-nav next" onclick="moveServices(1)">›</button>
        </div>
        <div class="services-dots" id="servicesDots"></div>
    </div>

    <div class="reviews-section">
        <div class="reviews-header">
            <h2>Review Pelanggan</h2>
        </div>

        <div class="reviews-container">
            <button class="slider-nav prev" onclick="moveReviews(-1)">‹</button>
            <div class="reviews-track" id="reviewsTrack">
                <!-- Review cards will be populated by JavaScript -->
            </div>
            <button class="slider-nav next" onclick="moveReviews(1)">›</button>
        </div>
        <div class="reviews-dots" id="reviewsDots"></div>
    </div>

    <!-- Refund Method Modal -->
    <div id="refundMethodModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 450px; border-radius: 16px; padding: 30px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
            <h3 style="font-size: 20px; font-weight: 600; color: #1a1a1a; margin-bottom: 15px;">Metode Refund Belum Diatur</h3>
            <p style="color: #666; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">
                Anda belum mengatur metode refund. Metode ini diperlukan untuk pengembalian dana jika booking dibatalkan. Atur sekarang?
            </p>
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button id="cancelRefundSetup" class="cancel-btn" style="padding: 12px 24px; border: 1px solid #ddd; background: white; color: #666; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s;">
                    Nanti
                </button>
                <button id="goToProfile" class="primary-btn" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s;">
                    Atur Sekarang
                </button>
            </div>
        </div>
    </div>

    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cancel-btn:hover {
            background: #f5f5f5 !important;
            border-color: #999 !important;
        }

        .primary-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
    </style>

    <script>
        // Inject real data from server - NO MORE MOCK DATA
        window.mitraBusinessData = @json($business);
        window.mitraGalleryImages = @json($galleryImages);
        window.mitraServices = @json($services);
        window.mitraReviews = @json($reviews);
    </script>
    <script src="{{ asset('js/minipro.js') }}?v={{ time() }}"></script>
    <script>
        // Listen untuk update avatar dari halaman profil
        if (typeof BroadcastChannel !== 'undefined') {
            const channel = new BroadcastChannel('profile_update');
            channel.onmessage = (event) => {
                if (event.data.type === 'avatar_updated') {
                    // Update all avatar images in comments/reviews
                    document.querySelectorAll('.user-icon-img, .avatar__image, .reviewer-photo').forEach(img => {
                        img.src = event.data.avatar;
                    });
                    console.log('🔄 Avatar synced from other tab');
                }
            };
        }
    </script>
</body>
</html>
