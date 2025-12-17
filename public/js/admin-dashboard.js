// ====== MODAL MANAGEMENT ======
class ModalManager {
    constructor() {
        this.modals = {
            logout: document.getElementById("logoutModal"),
        };

        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Logout modal
        const logoutBtn = document.getElementById("logoutBtn");
        const cancelLogout = document.getElementById("cancelLogout");
        const confirmLogout = document.getElementById("confirmLogout");

        if (logoutBtn) {
            logoutBtn.addEventListener("click", (e) => {
                e.preventDefault();
                this.showModal("logout");
            });
        }

        if (cancelLogout) {
            cancelLogout.addEventListener("click", () => {
                this.hideModal("logout");
            });
        }

        if (confirmLogout) {
            confirmLogout.addEventListener("click", () => {
                this.handleLogout();
            });
        }

        // Kelola Admin link
        const newAdminBtn = document.getElementById("newAdminBtn");
        if (newAdminBtn) {
            newAdminBtn.addEventListener("click", (e) => {
                e.preventDefault();
                window.location.href = "/admin/kelolaadmin/kelolaadmin";
            });
        }

        // Close modal when clicking outside
        Object.values(this.modals).forEach((modal) => {
            if (modal) {
                modal.addEventListener("click", (e) => {
                    if (e.target === modal) {
                        this.hideModal(modal.id.replace("Modal", ""));
                    }
                });
            }
        });

        // Close modal with Escape key
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                this.hideAllModals();
            }
        });
    }

    showModal(modalName) {
        const modal = this.modals[modalName];
        if (modal) {
            modal.classList.add("show");
            document.body.style.overflow = "hidden";
        }
    }

    hideModal(modalName) {
        const modal = this.modals[modalName];
        if (modal) {
            modal.classList.remove("show");
            document.body.style.overflow = "";
        }
    }

    hideAllModals() {
        Object.keys(this.modals).forEach((modalName) => {
            this.hideModal(modalName);
        });
    }

    handleLogout() {
        console.log("Logging out...");

        const confirmBtn = document.getElementById("confirmLogout");
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = "Logging out...";
        confirmBtn.disabled = true;

        this.hideModal("logout");

        // Logout via fetch to prevent history
        fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            credentials: 'same-origin'
        })
        .then(response => {
            // Clear session storage
            try {
                sessionStorage.clear();
                localStorage.removeItem('cookieConsent');
            } catch(e) {}
            
            // Replace history and redirect
            window.history.replaceState(null, '', '/login');
            window.location.replace('/login');
        })
        .catch(error => {
            console.error('Logout error:', error);
            window.location.replace('/login');
        });
    }
}

// ====== NAVIGATION MANAGEMENT ======
class NavigationManager {
    constructor() {
        this.navItems = document.querySelectorAll(".nav-item");
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        this.navItems.forEach((item) => {
            item.addEventListener("click", (e) => {
                // Hanya prevent default jika href adalah "#" atau kosong
                if (
                    item.getAttribute("href") === "#" ||
                    !item.getAttribute("href")
                ) {
                    e.preventDefault();
                }

                // Update state aktif
                this.setActiveItem(item);

                // Jika href adalah "#" atau kosong, tetap di halaman yang sama
                if (
                    item.getAttribute("href") === "#" ||
                    !item.getAttribute("href")
                ) {
                    console.log("Navigasi internal:", item.textContent);
                }
            });
        });
    }

    setActiveItem(activeItem) {
        this.navItems.forEach((item) => item.classList.remove("active"));
        activeItem.classList.add("active");
    }
}

// ====== USER PROFILE MANAGEMENT ======
class UserProfileManager {
    constructor() {
        this.userAvatar = document.querySelector(".user-avatar");
        this.dropdownMenu = document.querySelector(".dropdown-menu");
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        if (this.userAvatar && this.dropdownMenu) {
            this.userAvatar.addEventListener("click", (e) => {
                e.stopPropagation();
                this.toggleDropdown();
            });

            // Tutup dropdown ketika klik di luar
            document.addEventListener("click", () => {
                this.hideDropdown();
            });
        }
    }

    toggleDropdown() {
        const isVisible = this.dropdownMenu.style.visibility === "visible";

        if (isVisible) {
            this.hideDropdown();
        } else {
            this.showDropdown();
        }
    }

    showDropdown() {
        this.dropdownMenu.style.opacity = "1";
        this.dropdownMenu.style.visibility = "visible";
        this.dropdownMenu.style.transform = "translateY(0)";
    }

    hideDropdown() {
        this.dropdownMenu.style.opacity = "0";
        this.dropdownMenu.style.visibility = "hidden";
        this.dropdownMenu.style.transform = "translateY(-10px)";
    }
}

// ====== INITIALIZATION ======
document.addEventListener("DOMContentLoaded", function () {
    // Initialize all managers
    const modalManager = new ModalManager();
    const navigationManager = new NavigationManager();
    const userProfileManager = new UserProfileManager();

    console.log("Dashboard Admin initialized successfully");
});

// ====== UTILITY FUNCTIONS ======
// Fungsi untuk menampilkan notifikasi
function showNotification(message, type = "info") {
    // Implementasi notifikasi bisa ditambahkan di sini
    console.log(`${type.toUpperCase()}: ${message}`);
}

// Fungsi untuk format angka
function formatNumber(num) {
    return new Intl.NumberFormat("id-ID").format(num);
}

// Fungsi untuk format tanggal
function formatDate(date) {
    return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    }).format(date);
}
