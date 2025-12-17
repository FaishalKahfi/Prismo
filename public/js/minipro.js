// Load real data from server - NO MORE MOCK DATA
const mockData = {
    business: window.mitraBusinessData || {},
    galleryImages: window.mitraGalleryImages || [],
    services: window.mitraServices || [],
    reviews: window.mitraReviews || [],
};

// Global Variables
let currentSlide = 0;
let currentServiceSlide = 0;
let currentReviewSlide = 0;
let autoSlideInterval;
// Dynamic cards per slide based on screen width
let currentCardsPerSlide = 3;

// Update cards per slide based on window width
function updateCardsPerSlide() {
    if (window.innerWidth < 650) {
        currentCardsPerSlide = 1;
    } else if (window.innerWidth < 1000) {
        currentCardsPerSlide = 2;
    } else {
        currentCardsPerSlide = 3;
    }
}

// Helper function to extract mitraId from current URL
function extractMitraIdFromUrl() {
    const pathParts = window.location.pathname.split("/");
    // URL format: /customer/detail-mitra/minipro/{id}
    const mitraIdIndex = pathParts.indexOf("minipro") + 1;
    return pathParts[mitraIdIndex] || "1";
}

// Check refund method before allowing booking
function checkRefundMethodBeforeBooking(button) {
    const serviceName = button.dataset.serviceName;
    const servicePrice = button.dataset.servicePrice;
    const mitraId = button.dataset.mitraId;

    // Check if user has set refund method
    fetch("/api/customer/refund-method/check", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
        },
        credentials: "same-origin",
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.has_refund_method) {
                // User has refund method, proceed to booking
                window.location.href = `/customer/atur-booking/booking/${mitraId}?service=${encodeURIComponent(
                    serviceName
                )}&price=${servicePrice}`;
            } else {
                // Show modal asking user to set refund method
                showRefundMethodModal();
            }
        })
        .catch((error) => {
            console.error("Error checking refund method:", error);
            // On error, allow booking (fail-safe)
            window.location.href = `/customer/atur-booking/booking/${mitraId}?service=${encodeURIComponent(
                serviceName
            )}&price=${servicePrice}`;
        });
}

function showRefundMethodModal() {
    const modal = document.getElementById("refundMethodModal");
    if (modal) {
        modal.style.display = "flex";

        // Setup event listeners
        document.getElementById("cancelRefundSetup").onclick = function () {
            modal.style.display = "none";
        };

        document.getElementById("goToProfile").onclick = function () {
            window.location.href = "/customer/profil/uprofil";
        };

        // Close on overlay click
        modal.onclick = function (e) {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        };
    }
}

// Initialize the page
document.addEventListener("DOMContentLoaded", function () {
    updateCardsPerSlide();
    initializeBusinessInfo();
    initializeGallery();
    initializeServices();
    initializeReviews();
    startAutoSlide();
});

// Reinitialize on window resize
let resizeTimeout;
window.addEventListener("resize", function () {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        const oldCardsPerSlide = currentCardsPerSlide;
        updateCardsPerSlide();
        if (oldCardsPerSlide !== currentCardsPerSlide) {
            initializeServices();
            initializeReviews();
        }
    }, 300);
});

// Generate star rating HTML
function generateStars(rating) {
    return '<span class="star filled">★</span>';
}

// Business Information
function initializeBusinessInfo() {
    const business = mockData.business;

    // Add completed bookings info if available
    const completedInfo =
        business.completedBookings > 0
            ? `<span style="color: #666; font-size: 14px; margin-left: 8px;">• ${business.completedBookings} booking</span>`
            : "";

    document.getElementById("businessName").innerHTML = `
        ${business.name}
        <span class="rating">${generateStars(
            business.rating
        )} <span class="rating-value">${business.rating}</span> (${
        business.reviewCount
    }) ${completedInfo}</span>
    `;
    document.getElementById("businessDescription").textContent =
        business.description;

    // Add map location link if available
    const addressHTML = business.mapLocation
        ? `<i class="fas fa-map-marker-alt"></i><span>${business.location}<br>${business.address} <a href="${business.mapLocation}" target="_blank" style="color: #4285f4; text-decoration: none;">[Lihat di Maps]</a></span>`
        : `<i class="fas fa-map-marker-alt"></i><span>${business.location}<br>${business.address}</span>`;

    document.getElementById("businessAddress").innerHTML = addressHTML;
    document.getElementById("businessPhone").textContent = business.phone;

    // Format operational hours properly
    let hoursHTML = "Operasional:<br>";
    const dayNames = {
        monday: "Senin",
        tuesday: "Selasa",
        wednesday: "Rabu",
        thursday: "Kamis",
        friday: "Jumat",
        saturday: "Sabtu",
        sunday: "Minggu",
        senin: "Senin",
        selasa: "Selasa",
        rabu: "Rabu",
        kamis: "Kamis",
        jumat: "Jumat",
        sabtu: "Sabtu",
        minggu: "Minggu",
    };

    // Sort days from Monday to Sunday
    const daysOrder = [
        "monday",
        "tuesday",
        "wednesday",
        "thursday",
        "friday",
        "saturday",
        "sunday",
    ];
    const daysOrderIndo = [
        "senin",
        "selasa",
        "rabu",
        "kamis",
        "jumat",
        "sabtu",
        "minggu",
    ];

    if (
        business.operationalHours &&
        typeof business.operationalHours === "object"
    ) {
        // Try English keys first, then Indonesian keys
        let orderedDays = daysOrder;
        const firstKey = Object.keys(business.operationalHours)[0];
        if (firstKey && daysOrderIndo.includes(firstKey.toLowerCase())) {
            orderedDays = daysOrderIndo;
        }

        orderedDays.forEach((day) => {
            if (business.operationalHours[day]) {
                const hours = business.operationalHours[day];
                const dayName = dayNames[day] || day;

                // Handle object format (new format)
                if (typeof hours === "object" && hours !== null) {
                    if (hours.enabled) {
                        const timeStr = `${hours.open} - ${hours.close}`;
                        // Check if there are break schedules
                        let breakStr = "";
                        if (
                            hours.hasBreakSchedule &&
                            hours.breakSchedules &&
                            hours.breakSchedules.length > 0
                        ) {
                            // Display all break schedules
                            const breaks = hours.breakSchedules
                                .map((b) => `${b.open}-${b.close}`)
                                .join(", ");
                            breakStr = ` (Istirahat: ${breaks})`;
                        }
                        // Fallback for old format with breakStart/breakEnd
                        else if (
                            hours.hasBreakSchedule &&
                            hours.breakStart &&
                            hours.breakEnd
                        ) {
                            breakStr = ` (Istirahat: ${hours.breakStart} - ${hours.breakEnd})`;
                        }
                        hoursHTML += `<strong>${dayName}:</strong> ${timeStr}${breakStr}<br>`;
                    } else {
                        hoursHTML += `<strong>${dayName}:</strong> Tutup<br>`;
                    }
                }
                // Handle string format (old format like "08:00-17:00" or "Tutup")
                else if (typeof hours === "string") {
                    hoursHTML += `<strong>${dayName}:</strong> ${hours.replace(
                        "-",
                        " - "
                    )}<br>`;
                } else {
                    hoursHTML += `<strong>${dayName}:</strong> ${hours}<br>`;
                }
            }
        });
    }
    document.getElementById("businessHours").innerHTML = hoursHTML;
}

// Gallery Slider
function initializeGallery() {
    const galleryTrack = document.getElementById("galleryTrack");
    const dotsContainer = document.getElementById("dotsContainer");

    // Ensure galleryImages is an array
    const galleryImages = Array.isArray(mockData.galleryImages)
        ? mockData.galleryImages
        : [];

    // If no images, use default
    if (galleryImages.length === 0) {
        galleryImages.push("/images/gambar2.png");
    }

    // Create slides
    galleryImages.forEach((image, index) => {
        const slide = document.createElement("div");
        slide.className = "gallery-slide";
        slide.innerHTML = `<img src="${image}" alt="AutoSpa Premium ${
            index + 1
        }" onerror="this.src='/images/gambar2.png'">`;
        galleryTrack.appendChild(slide);
    });

    // Create dots
    galleryImages.forEach((_, index) => {
        const dot = document.createElement("div");
        dot.className = "dot";
        if (index === 0) dot.classList.add("active");
        dot.onclick = () => goToSlide(index);
        dotsContainer.appendChild(dot);
    });
}

function updateSlide() {
    const slides = document.querySelectorAll(".gallery-slide");
    const dots = document.querySelectorAll(".gallery-dots .dot");
    const track = document.getElementById("galleryTrack");

    track.style.transform = `translateX(-${currentSlide * 100}%)`;
    dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentSlide);
    });
}

function moveSlide(direction) {
    const slides = document.querySelectorAll(".gallery-slide");
    currentSlide += direction;
    if (currentSlide < 0) currentSlide = slides.length - 1;
    if (currentSlide >= slides.length) currentSlide = 0;
    updateSlide();
    resetAutoSlide();
}

function goToSlide(index) {
    const slides = document.querySelectorAll(".gallery-slide");
    currentSlide = index;
    updateSlide();
    resetAutoSlide();
}

function autoSlide() {
    const slides = document.querySelectorAll(".gallery-slide");
    currentSlide++;
    if (currentSlide >= slides.length) currentSlide = 0;
    updateSlide();
}

function startAutoSlide() {
    clearInterval(autoSlideInterval); // Clear any existing interval first
    autoSlideInterval = setInterval(autoSlide, 3000);
}

function resetAutoSlide() {
    clearInterval(autoSlideInterval);
    // Delay starting new interval to maintain 3-second rhythm
    setTimeout(() => {
        startAutoSlide();
    }, 3000);
}

// Services Slider - dynamic cards per slide
function initializeServices() {
    const servicesTrack = document.getElementById("servicesTrack");
    const dotsContainer = document.getElementById("servicesDots");
    const servicesTitle = document.querySelector(".services-title");

    // Clear existing content
    servicesTrack.innerHTML = "";
    dotsContainer.innerHTML = "";

    // Check if there are any services
    if (!mockData.services || mockData.services.length === 0) {
        // Hide services title when no services
        if (servicesTitle) {
            servicesTitle.style.display = "none";
        }

        // Show empty state message
        const emptyState = document.createElement("div");
        emptyState.className = "empty-services-state";
        emptyState.innerHTML = `
            <div class="empty-state-icon">📦</div>
            <h3>Belum Ada Paket Layanan</h3>
            <p>Mitra belum menambahkan paket layanan</p>
        `;
        servicesTrack.appendChild(emptyState);

        // Hide slider controls
        document
            .querySelectorAll(".services-container .slider-nav")
            .forEach((nav) => {
                nav.style.display = "none";
            });

        return; // Stop here if no services
    }

    // Show services title when services exist
    if (servicesTitle) {
        servicesTitle.style.display = "block";
    }

    // Show slider controls
    document
        .querySelectorAll(".services-container .slider-nav")
        .forEach((nav) => {
            nav.style.display = "flex";
        });

    // Group services into slides
    const serviceSlides = [];
    for (let i = 0; i < mockData.services.length; i += currentCardsPerSlide) {
        serviceSlides.push(
            mockData.services.slice(i, i + currentCardsPerSlide)
        );
    }

    // Create slides
    serviceSlides.forEach((slideServices, slideIndex) => {
        const slide = document.createElement("div");
        slide.className = "services-slide";

        slideServices.forEach((service) => {
            const card = document.createElement("div");
            card.className = "service-card";

            card.innerHTML = `
                <h3>${service.name}</h3>
                <p>${service.description}</p>
                <div class="price-section">
                    <span class="price">Harga Paket</span>
                    <span class="price-amount">Rp${service.price.toLocaleString(
                        "id-ID"
                    )}</span>
                </div>
                <button class="select-btn" data-service-name="${
                    service.name
                }" data-service-price="${service.price}" data-mitra-id="${
                mockData.business.mitraId || extractMitraIdFromUrl()
            }">
                    Pilih
                </button>
            `;

            // Add click handler for service selection
            const selectBtn = card.querySelector(".select-btn");
            selectBtn.addEventListener("click", function () {
                checkRefundMethodBeforeBooking(this);
            });
            slide.appendChild(card);
        });

        servicesTrack.appendChild(slide);
    });

    // Create dots for services
    const totalServiceSlides = serviceSlides.length;
    createDots(dotsContainer, totalServiceSlides, "service");

    // Reset to first slide
    currentServiceSlide = 0;
    updateServicesNavigation();
}

function moveServices(direction) {
    const totalServiceSlides = Math.ceil(
        mockData.services.length / currentCardsPerSlide
    );
    currentServiceSlide += direction;
    if (currentServiceSlide < 0) currentServiceSlide = totalServiceSlides - 1;
    if (currentServiceSlide >= totalServiceSlides) currentServiceSlide = 0;
    updateServiceSlide();
}

function goToServiceSlide(index) {
    currentServiceSlide = index;
    updateServiceSlide();
}

function updateServiceSlide() {
    const servicesTrack = document.getElementById("servicesTrack");
    const dots = document.querySelectorAll(".services-dots .dot");

    servicesTrack.style.transform = `translateX(-${
        currentServiceSlide * 100
    }%)`;

    dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentServiceSlide);
    });

    updateServicesNavigation();
}

function updateServicesNavigation() {
    const navButtons = document.querySelectorAll(
        ".services-slider .slider-nav"
    );
    const dotsContainer = document.querySelector(".services-dots");

    // Hide navigation if no services
    if (!mockData.services || mockData.services.length === 0) {
        navButtons.forEach((btn) => (btn.style.display = "none"));
        if (dotsContainer) dotsContainer.style.display = "none";
        return;
    }

    const totalServiceSlides = Math.ceil(
        mockData.services.length / currentCardsPerSlide
    );

    // Hide navigation if only one slide
    if (totalServiceSlides <= 1) {
        navButtons.forEach((btn) => btn.classList.add("hidden"));
        if (dotsContainer) dotsContainer.style.display = "none";
    } else {
        navButtons.forEach((btn) => btn.classList.remove("hidden"));
        if (dotsContainer) dotsContainer.style.display = "flex";
    }
}

// Reviews Slider - dynamic cards per slide
function initializeReviews() {
    const reviewsTrack = document.getElementById("reviewsTrack");
    const dotsContainer = document.getElementById("reviewsDots");
    const reviewsTitle = document.querySelector(".reviews-header h2");

    // Clear existing content
    reviewsTrack.innerHTML = "";
    dotsContainer.innerHTML = "";

    // Check if there are any reviews
    if (!mockData.reviews || mockData.reviews.length === 0) {
        // Hide reviews title when no reviews
        if (reviewsTitle) {
            reviewsTitle.parentElement.style.display = "none";
        }

        // Show empty state message
        const emptyState = document.createElement("div");
        emptyState.className = "empty-reviews-state";
        emptyState.innerHTML = `
            <div class="empty-state-icon">💬</div>
            <h3>Belum Ada Review</h3>
            <p>Belum ada review dari pelanggan untuk mitra ini</p>
        `;
        reviewsTrack.appendChild(emptyState);

        // Hide slider controls
        document
            .querySelectorAll(".reviews-container .slider-nav")
            .forEach((nav) => {
                nav.style.display = "none";
            });

        return; // Stop here if no reviews
    }

    // Show reviews title when reviews exist
    if (reviewsTitle) {
        reviewsTitle.parentElement.style.display = "flex";
    }

    // Show slider controls
    document
        .querySelectorAll(".reviews-container .slider-nav")
        .forEach((nav) => {
            nav.style.display = "flex";
        });

    // Group reviews into slides (max 9 reviews)
    const reviewsToShow = mockData.reviews.slice(0, 9);
    console.log("📊 Reviews data:", reviewsToShow);
    console.log("📸 First review avatar:", reviewsToShow[0]?.avatar);

    const reviewSlides = [];
    for (let i = 0; i < reviewsToShow.length; i += currentCardsPerSlide) {
        reviewSlides.push(reviewsToShow.slice(i, i + currentCardsPerSlide));
    }

    // Create slides
    reviewSlides.forEach((slideReviews, slideIndex) => {
        const slide = document.createElement("div");
        slide.className = "reviews-slide";

        slideReviews.forEach((review) => {
            const card = document.createElement("div");
            card.className = "review-card";

            // Generate star rating like business rating
            let starsHTML = "";
            for (let i = 1; i <= 5; i++) {
                if (i <= review.rating) {
                    starsHTML += '<span class="star filled">★</span>';
                } else {
                    starsHTML += '<span class="star">★</span>';
                }
            }

            const imagesHTML =
                review.images && review.images.length > 0
                    ? `<div class="review-images">
                    ${review.images
                        .map(
                            (img) =>
                                `<img src="${img}" alt="Review Image" onerror="this.style.display='none'">`
                        )
                        .join("")}
                </div>`
                    : "";

            card.innerHTML = `
                <div class="review-header">
                    <div class="avatar"><img src="${review.avatar}" alt="${review.customerName}" onerror="this.src='/images/profile.png'"></div>
                    <div class="review-info">
                        <h4>${review.customerName}</h4>
                        <p class="review-date">${review.date} | ${review.time}</p>
                    </div>
                    <div class="review-stars">${starsHTML}</div>
                </div>
                <p class="review-text">${review.comment}</p>
                ${imagesHTML}
            `;
            slide.appendChild(card);
        });

        // Fill empty slots if less than currentCardsPerSlide cards in last slide
        while (slide.children.length < currentCardsPerSlide) {
            const emptyCard = document.createElement("div");
            emptyCard.className = "review-card";
            emptyCard.style.visibility = "hidden";
            emptyCard.innerHTML = `
                <div class="review-header">
                    <div class="avatar">&nbsp;</div>
                    <div class="review-info">
                        <h4>&nbsp;</h4>
                        <p class="review-date">&nbsp;</p>
                    </div>
                </div>
                <p class="review-text">&nbsp;</p>
            `;
            slide.appendChild(emptyCard);
        }

        reviewsTrack.appendChild(slide);
    });

    // Create dots for reviews
    const totalReviewSlides = reviewSlides.length;
    createDots(dotsContainer, totalReviewSlides, "review");

    // Reset to first slide
    currentReviewSlide = 0;
    updateReviewsNavigation();
}

function moveReviews(direction) {
    const totalReviewSlides = Math.ceil(
        Math.min(mockData.reviews.length, 10) / currentCardsPerSlide
    );
    currentReviewSlide += direction;
    if (currentReviewSlide < 0) currentReviewSlide = totalReviewSlides - 1;
    if (currentReviewSlide >= totalReviewSlides) currentReviewSlide = 0;
    updateReviewSlide();
}

function goToReviewSlide(index) {
    currentReviewSlide = index;
    updateReviewSlide();
}

function updateReviewSlide() {
    const reviewsTrack = document.getElementById("reviewsTrack");
    const dots = document.querySelectorAll(".reviews-dots .dot");

    reviewsTrack.style.transform = `translateX(-${currentReviewSlide * 100}%)`;

    dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === currentReviewSlide);
    });

    updateReviewsNavigation();
}

function updateReviewsNavigation() {
    const totalReviewSlides = Math.ceil(
        Math.min(mockData.reviews.length, 10) / currentCardsPerSlide
    );
    const navButtons = document.querySelectorAll(
        ".reviews-container .slider-nav"
    );

    // Hide navigation if only one slide
    if (totalReviewSlides <= 1) {
        navButtons.forEach((btn) => btn.classList.add("hidden"));
        document.querySelector(".reviews-dots").style.display = "none";
    } else {
        navButtons.forEach((btn) => btn.classList.remove("hidden"));
        document.querySelector(".reviews-dots").style.display = "flex";
    }
}

// Helper function to create dots
function createDots(container, totalSlides, type) {
    container.innerHTML = "";
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement("div");
        dot.className = "dot";
        if (i === 0) dot.classList.add("active");

        if (type === "service") {
            dot.onclick = () => goToServiceSlide(i);
        } else if (type === "review") {
            dot.onclick = () => goToReviewSlide(i);
        }

        container.appendChild(dot);
    }
}

// Service Selection
function selectService(serviceName, servicePrice) {
    // This function is now handled by the direct link in the HTML
    console.log(`Selected service: ${serviceName} with price: ${servicePrice}`);
}

// Touch Support for Mobile
const sliderContainer = document.querySelector(".gallery-slider");
let touchStartX = 0;
let touchEndX = 0;

sliderContainer.addEventListener("touchstart", (e) => {
    touchStartX = e.changedTouches[0].screenX;
    clearInterval(autoSlideInterval);
});

sliderContainer.addEventListener("touchend", (e) => {
    touchEndX = e.changedTouches[0].screenX;
    if (touchStartX - touchEndX > 50) {
        moveSlide(1);
    } else if (touchEndX - touchStartX > 50) {
        moveSlide(-1);
    } else {
        startAutoSlide();
    }
});

// Keyboard Navigation
document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") {
        moveSlide(-1);
    } else if (e.key === "ArrowRight") {
        moveSlide(1);
    }
});

// Pause auto-slide on hover
sliderContainer.addEventListener("mouseenter", () => {
    clearInterval(autoSlideInterval);
});

sliderContainer.addEventListener("mouseleave", () => {
    startAutoSlide();
});

// ========== IMAGE PREVIEW FUNCTIONALITY ==========
function initImagePreview() {
    // Create modal overlay if doesn't exist
    if (!document.getElementById("imageModalOverlay")) {
        const modalHTML = `
            <div class="image-modal-overlay" id="imageModalOverlay">
                <div class="image-modal-content">
                    <button class="image-modal-close" onclick="closeImageModal()">×</button>
                    <img id="modalPreviewImage" src="" alt="Preview">
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML("beforeend", modalHTML);
    }

    // Add click event to all review images
    document.addEventListener("click", function (e) {
        if (e.target.closest(".review-images") && e.target.tagName === "IMG") {
            openImageModal(e.target.src);
        }
    });

    // Close modal when clicking overlay
    const overlay = document.getElementById("imageModalOverlay");
    if (overlay) {
        overlay.addEventListener("click", function (e) {
            if (e.target === overlay) {
                closeImageModal();
            }
        });
    }

    // Close with ESC key
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            closeImageModal();
        }
    });
}

function openImageModal(imageSrc) {
    const overlay = document.getElementById("imageModalOverlay");
    const modalImage = document.getElementById("modalPreviewImage");
    if (overlay && modalImage) {
        modalImage.src = imageSrc;
        overlay.classList.add("active");
        document.body.style.overflow = "hidden";
    }
}

function closeImageModal() {
    const overlay = document.getElementById("imageModalOverlay");
    if (overlay) {
        overlay.classList.remove("active");
        document.body.style.overflow = "";
    }
}

// Initialize image preview
initImagePreview();
