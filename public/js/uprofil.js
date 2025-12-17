// Elements
const backBtn = document.getElementById("backBtn");
const logoutBtn = document.getElementById("logoutBtn");
const photoUpload = document.getElementById("photoUpload");
const avatarImg = document.getElementById("avatarImg");

// All profile data loaded from database via Blade template
// No localStorage needed

// Store selected file temporarily
let pendingAvatarFile = null;

// Photo upload handler
if (photoUpload) {
    photoUpload.addEventListener("change", (e) => {
        const file = e.target.files[0];
        if (file) {
            // Validasi file
            if (!file.type.startsWith("image/")) {
                alert("File harus berupa gambar!");
                photoUpload.value = ""; // Reset input
                return;
            }

            // Validasi ukuran file (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert("Ukuran file maksimal 5MB!");
                photoUpload.value = ""; // Reset input
                return;
            }

            // Preview image immediately
            const reader = new FileReader();
            reader.onload = function (e) {
                if (avatarImg) {
                    avatarImg.src = e.target.result;
                    console.log("👁️ Preview image loaded");
                }
            };
            reader.readAsDataURL(file);

            // Store file and show confirmation modal
            pendingAvatarFile = file;
            showAvatarConfirmModal();
        }
    });
}

// Show avatar confirmation modal
function showAvatarConfirmModal() {
    const modal = document.getElementById("avatarConfirmModal");
    if (modal) {
        modal.style.display = "flex";
    }
}

// Cancel avatar change
function cancelAvatarChange() {
    const modal = document.getElementById("avatarConfirmModal");
    if (modal) {
        modal.style.display = "none";
    }

    // Restore original avatar
    if (avatarImg && avatarImg.dataset.originalSrc) {
        avatarImg.src = avatarImg.dataset.originalSrc;
    }

    pendingAvatarFile = null;
    photoUpload.value = ""; // Reset file input
}

// Confirm avatar change
function confirmAvatarChange() {
    if (!pendingAvatarFile) return;

    // Close modal
    const modal = document.getElementById("avatarConfirmModal");
    if (modal) {
        modal.style.display = "none";
    }

    // Show loading state
    showNotification("Mengupload foto...", "info");

    // Upload foto menggunakan API
    const formData = new FormData();
    formData.append("photo", pendingAvatarFile);

    // Get CSRF token from meta tag or cookie
    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;

    fetch("/profile/photo/upload", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        body: formData,
    })
        .then((response) => {
            console.log("📡 Response status:", response.status);
            if (!response.ok) {
                return response.json().then((err) => {
                    console.error("❌ Server error:", err);
                    throw new Error(
                        err.message || `HTTP error! status: ${response.status}`
                    );
                });
            }
            return response.json();
        })
        .then((data) => {
            console.log("📦 Response data:", data);
            if (data.success) {
                console.log("✅ Profile photo updated and saved to storage");
                showNotification("Foto profil berhasil diperbarui!", "success");

                // Update avatar di halaman ini langsung dengan timestamp baru
                const newAvatarUrl = data.photo_url + "?v=" + data.cache_buster;
                console.log("🖼️ New avatar URL:", newAvatarUrl);

                const avatarImgs = document.querySelectorAll(
                    "#avatarImg, .avatar__image"
                );
                console.log(
                    "🔍 Found",
                    avatarImgs.length,
                    "avatar images to update"
                );

                avatarImgs.forEach((img, index) => {
                    console.log(`  Updating img ${index + 1}:`, img);
                    img.src = newAvatarUrl;
                    // Update data attribute with new original src
                    img.dataset.originalSrc = newAvatarUrl;
                });

                // Broadcast ke tab lain untuk update avatar
                if (typeof BroadcastChannel !== "undefined") {
                    const channel = new BroadcastChannel("profile_update");
                    channel.postMessage({
                        type: "avatar_updated",
                        avatar: newAvatarUrl,
                    });
                    channel.close();
                }

                console.log("🔄 Avatar updated to:", newAvatarUrl);
            } else {
                // If upload failed, restore original avatar
                if (avatarImg && avatarImg.dataset.originalSrc) {
                    avatarImg.src = avatarImg.dataset.originalSrc;
                }
                showNotification(
                    "Gagal mengupload foto: " +
                        (data.message || "Unknown error"),
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error uploading photo:", error);
            // Restore original avatar on error
            if (avatarImg && avatarImg.dataset.originalSrc) {
                avatarImg.src = avatarImg.dataset.originalSrc;
            }
            showNotification(
                "Terjadi kesalahan saat mengupload foto. Silakan coba lagi.",
                "error"
            );
        })
        .finally(() => {
            // Clear pending file
            pendingAvatarFile = null;
            photoUpload.value = ""; // Reset file input
        });
}

// Logout modal functions
function showLogoutModal() {
    const modal = document.getElementById("logoutModalOverlay");
    if (modal) {
        modal.style.display = "flex";
    }
}

function cancelLogout() {
    const modal = document.getElementById("logoutModalOverlay");
    if (modal) {
        modal.style.display = "none";
    }
}

function confirmLogout() {
    // Logout via POST request
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/logout";

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = csrfToken.getAttribute("content");
        form.appendChild(csrfInput);
    }

    document.body.appendChild(form);
    form.submit();
}

// Logout handler
if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
        showLogoutModal();
    });
}

// Notification system
function showNotification(message, type = "success") {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="ph ph-${getNotificationIcon(type)}"></i>
        <span>${message}</span>
    `;

    // Tambahkan styling inline dengan support untuk info type
    const colors = {
        success: { border: "#10B981", text: "#065F46" },
        error: { border: "#EF4444", text: "#7F1D1D" },
        info: { border: "#3B82F6", text: "#1E3A8A" },
    };

    const colorScheme = colors[type] || colors.success;

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 1001;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid ${colorScheme.border};
        color: ${colorScheme.text};
    `;

    document.body.appendChild(notification);

    // Hapus setelah 3 detik
    setTimeout(() => {
        notification.style.animation = "slideInRight 0.3s ease reverse";
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function getNotificationIcon(type) {
    switch (type) {
        case "success":
            return "check-circle";
        case "error":
            return "warning-circle";
        case "info":
            return "info";
        default:
            return "check-circle";
    }
}

// All data loaded from database - no initialization needed
console.log("✅ User Profile Page Initialized");

// Refund Method Modal Functions
document.addEventListener("DOMContentLoaded", function () {
    const refundMethodBtn = document.getElementById("refundMethodBtn");
    const refundMethodModalOverlay = document.getElementById(
        "refundMethodModalOverlay"
    );
    const refundMethodForm = document.getElementById("refundMethodForm");

    if (refundMethodBtn) {
        console.log("✅ Refund method button found");
        refundMethodBtn.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("🔘 Refund method button clicked");
            openRefundMethodModal();
        });
    } else {
        console.error("❌ Refund method button not found");
    }

    // Custom select dropdown functionality
    const selectedPaymentMethod = document.getElementById(
        "selectedPaymentMethod"
    );
    const paymentMethodOptions = document.getElementById(
        "paymentMethodOptions"
    );
    const hiddenInput = document.getElementById("refundPaymentMethod");
    const accountNumberInput = document.getElementById("refundAccountNumber");

    // Only allow numbers in account number input
    if (accountNumberInput) {
        accountNumberInput.addEventListener("input", function (e) {
            this.value = this.value.replace(/[^0-9]/g, "");
        });

        accountNumberInput.addEventListener("keypress", function (e) {
            if (
                !/[0-9]/.test(e.key) &&
                e.key !== "Backspace" &&
                e.key !== "Delete" &&
                e.key !== "ArrowLeft" &&
                e.key !== "ArrowRight" &&
                e.key !== "Tab"
            ) {
                e.preventDefault();
            }
        });
    }

    if (selectedPaymentMethod && paymentMethodOptions) {
        selectedPaymentMethod.addEventListener("click", function (e) {
            e.stopPropagation();
            paymentMethodOptions.style.display =
                paymentMethodOptions.style.display === "none"
                    ? "block"
                    : "none";
        });

        document.querySelectorAll(".payment-option").forEach((option) => {
            option.addEventListener("click", function () {
                const value = this.getAttribute("data-value");
                const img = this.querySelector("img").cloneNode(true);
                const text = this.querySelector("span").textContent;

                selectedPaymentMethod.innerHTML = "";
                selectedPaymentMethod.appendChild(img);
                const span = document.createElement("span");
                span.textContent = text;
                span.style.color = "#333";
                selectedPaymentMethod.appendChild(span);

                hiddenInput.value = value;
                paymentMethodOptions.style.display = "none";
            });

            option.addEventListener("mouseenter", function () {
                this.style.background = "#f5f5f5";
            });

            option.addEventListener("mouseleave", function () {
                this.style.background = "white";
            });
        });

        document.addEventListener("click", function (e) {
            if (
                !selectedPaymentMethod.contains(e.target) &&
                !paymentMethodOptions.contains(e.target)
            ) {
                paymentMethodOptions.style.display = "none";
            }
        });
    }

    async function openRefundMethodModal() {
        console.log("🚀 Opening refund method modal");
        console.log("Modal overlay element:", refundMethodModalOverlay);

        // Load existing refund method if any
        try {
            const response = await fetch("/api/customer/refund-method", {
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
            });

            if (response.ok) {
                const data = await response.json();
                if (data.refund_method) {
                    // Set selected payment method with logo
                    const option = document.querySelector(
                        `.payment-option[data-value="${data.refund_method}"]`
                    );
                    if (option) {
                        const img = option.querySelector("img").cloneNode(true);
                        const text = option.querySelector("span").textContent;

                        const trigger = document.getElementById(
                            "selectedPaymentMethod"
                        );
                        trigger.innerHTML = "";
                        trigger.appendChild(img);
                        const span = document.createElement("span");
                        span.textContent = text;
                        span.style.color = "#333";
                        trigger.appendChild(span);
                    }

                    hiddenInput.value = data.refund_method;
                    document.getElementById("refundAccountNumber").value =
                        data.account_number || "";
                }
            }
        } catch (error) {
            console.error("Error loading refund method:", error);
        }

        if (refundMethodModalOverlay) {
            console.log("✅ Setting modal display to flex");
            refundMethodModalOverlay.style.display = "flex";
            refundMethodModalOverlay.style.opacity = "1";
            refundMethodModalOverlay.style.visibility = "visible";
            refundMethodModalOverlay.style.pointerEvents = "auto";
            document.body.style.overflow = "hidden";
            console.log(
                "Modal display style:",
                refundMethodModalOverlay.style.display
            );
            console.log(
                "Modal computed display:",
                window.getComputedStyle(refundMethodModalOverlay).display
            );
            console.log(
                "Modal visibility:",
                window.getComputedStyle(refundMethodModalOverlay).visibility
            );
            console.log(
                "Modal opacity:",
                window.getComputedStyle(refundMethodModalOverlay).opacity
            );
            console.log(
                "Modal z-index:",
                window.getComputedStyle(refundMethodModalOverlay).zIndex
            );
        } else {
            console.error("❌ Modal overlay not found!");
        }
    }

    window.closeRefundMethodModal = function () {
        if (refundMethodModalOverlay) {
            refundMethodModalOverlay.style.display = "none";
            refundMethodModalOverlay.style.opacity = "0";
            refundMethodModalOverlay.style.visibility = "hidden";
            document.body.style.overflow = "";
        }
        if (refundMethodForm) {
            refundMethodForm.reset();
            // Reset custom select to default
            const trigger = document.getElementById("selectedPaymentMethod");
            if (trigger) {
                trigger.innerHTML =
                    '<span style="color: #999;">Pilih metode pembayaran</span>';
            }
            if (hiddenInput) {
                hiddenInput.value = "";
            }
        }
    };

    // Close modal when clicking outside
    if (refundMethodModalOverlay) {
        refundMethodModalOverlay.addEventListener("click", function (e) {
            if (e.target === refundMethodModalOverlay) {
                closeRefundMethodModal();
            }
        });
    }

    // Handle refund method form submission
    if (refundMethodForm) {
        refundMethodForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const refundMethod = document.getElementById(
                "refundPaymentMethod"
            ).value;
            const accountNumber = document.getElementById(
                "refundAccountNumber"
            ).value;

            // Validate account number format
            if (!/^[0-9]{8,15}$/.test(accountNumber)) {
                showNotification("Nomor akun harus 8-15 digit angka", "error");
                return;
            }

            try {
                const response = await fetch("/api/customer/refund-method", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        Accept: "application/json",
                    },
                    body: JSON.stringify({
                        refund_method: refundMethod,
                        account_number: accountNumber,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    showNotification(
                        data.message || "Metode refund berhasil disimpan",
                        "success"
                    );
                    closeRefundMethodModal();
                } else {
                    showNotification(
                        data.message || "Gagal menyimpan metode refund",
                        "error"
                    );
                }
            } catch (error) {
                console.error("Error saving refund method:", error);
                showNotification(
                    "Terjadi kesalahan. Silakan coba lagi.",
                    "error"
                );
            }
        });
    }
});
