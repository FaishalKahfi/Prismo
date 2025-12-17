// ===== PRISMO SALDO MANAGER =====
class PrismoSaldoManager {
    constructor() {
        this.isInitialized = false;
        this.isMobileMenuOpen = false;
        this.currentPage = "saldo";

        // Notifikasi data
        this.notifications = {
            antrian: 3,
            review: 5,
        };

        // Load real data from server - NO MORE MOCK DATA
        this.availableBalance = window.availableBalance || 0;
        this.totalEarnings = window.totalEarnings || 0;
        this.totalWithdrawn = window.totalWithdrawn || 0;
        this.pendingWithdrawals = window.pendingWithdrawals || 0;
        this.todayEarnings = window.todayEarnings || 0;
        this.hasWithdrawnToday = window.hasWithdrawnToday || false;
        this.hasProcessingWithdrawal = window.hasProcessingWithdrawal || false;

        // Debug log
        console.log("💰 Saldo Data Loaded:", {
            availableBalance: this.availableBalance,
            hasWithdrawnToday: this.hasWithdrawnToday,
            hasProcessingWithdrawal: this.hasProcessingWithdrawal,
        });

        this.minimumWithdrawal = 50000;
        this.adminFee = 2500;

        // Batasan waktu penarikan: 08:00 - 23:00
        this.withdrawalStartHour = 8;
        this.withdrawalEndHour = 23;

        // Load withdrawal history from API
        this.withdrawalHistory = [];
        this.loadWithdrawalHistory();
    }

    async loadWithdrawalHistory() {
        try {
            const response = await fetch("/api/withdrawals", {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                credentials: "same-origin",
            });

            if (!response.ok) {
                console.error("Failed to load withdrawal history");
                return;
            }

            const data = await response.json();
            this.withdrawalHistory = data.map((withdrawal) => ({
                id: withdrawal.id,
                amount: parseFloat(withdrawal.amount),
                date: new Date(withdrawal.created_at)
                    .toISOString()
                    .split("T")[0],
                time: new Date(withdrawal.created_at).toLocaleTimeString(
                    "id-ID",
                    { hour: "2-digit", minute: "2-digit" }
                ),
                bank: withdrawal.bank_name || "QRIS",
                accountNumber: withdrawal.account_number
                    ? withdrawal.account_number.slice(-4)
                    : "QRIS",
                status: this.mapWithdrawalStatus(withdrawal.status),
                estimatedDate:
                    withdrawal.status === "pending" ||
                    withdrawal.status === "approved"
                        ? this.getEstimatedDate(withdrawal.created_at)
                        : null,
                receivedDate: withdrawal.processed_at
                    ? new Date(withdrawal.processed_at)
                          .toISOString()
                          .split("T")[0]
                    : null,
                receivedTime: withdrawal.processed_at
                    ? new Date(withdrawal.processed_at).toLocaleTimeString(
                          "id-ID",
                          { hour: "2-digit", minute: "2-digit" }
                      )
                    : null,
            }));

            console.log(
                "✅ Loaded withdrawal history:",
                this.withdrawalHistory.length,
                "items"
            );
            this.renderWithdrawalHistory();
        } catch (error) {
            console.error("Error loading withdrawal history:", error);
        }
    }

    mapWithdrawalStatus(status) {
        const statusMap = {
            pending: "proses",
            approved: "proses",
            completed: "berhasil",
            rejected: "ditolak",
        };
        return statusMap[status] || status;
    }

    getEstimatedDate(createdAt) {
        const date = new Date(createdAt);
        date.setDate(date.getDate() + 3); // 1-3 hari kerja, ambil maksimal 3 hari
        return date.toISOString().split("T")[0];
    }

    // Remove old loadTodayWithdrawalData - not needed anymore
    canWithdrawNow() {
        const now = new Date();
        const currentHour = now.getHours();

        console.log("═══════════════════════════════════════");
        console.log("🔍 WITHDRAWAL CHECK - DETAILED DEBUG");
        console.log("═══════════════════════════════════════");
        console.log("⏰ Current Time:", now.toLocaleString("id-ID"));
        console.log("⏰ Current Hour:", currentHour);
        console.log(
            "⏰ Operation Hours:",
            `${this.withdrawalStartHour}:00 - ${this.withdrawalEndHour}:00`
        );
        console.log(
            "💰 Available Balance:",
            this.availableBalance.toLocaleString("id-ID")
        );
        console.log(
            "💰 Minimum Withdrawal:",
            this.minimumWithdrawal.toLocaleString("id-ID")
        );
        console.log("📅 Has Withdrawn Today:", this.hasWithdrawnToday);
        console.log(
            "⏳ Has Processing Withdrawal:",
            this.hasProcessingWithdrawal
        );
        console.log("═══════════════════════════════════════");

        // Cek jam operasional: 08:00 - 23:00
        if (
            currentHour < this.withdrawalStartHour ||
            currentHour >= this.withdrawalEndHour
        ) {
            console.log("❌ BLOCKED: Outside operation hours");
            console.log(
                `   Current: ${currentHour}:00, Allowed: ${
                    this.withdrawalStartHour
                }:00 - ${this.withdrawalEndHour - 1}:59`
            );
            return {
                allowed: false,
                reason: "time",
                message: `Penarikan saldo hanya dapat dilakukan pada jam ${String(
                    this.withdrawalStartHour
                ).padStart(2, "0")}:00 - ${String(
                    this.withdrawalEndHour - 1
                ).padStart(2, "0")}:59`,
            };
        }
        console.log("✅ PASS: Within operation hours");

        // Cek sudah menarik hari ini atau belum (dari backend)
        if (this.hasWithdrawnToday) {
            console.log("❌ BLOCKED: Already withdrawn today");
            return {
                allowed: false,
                reason: "daily_limit",
                message:
                    "Anda sudah melakukan penarikan hari ini. Penarikan dapat dilakukan kembali besok.",
            };
        }
        console.log("✅ PASS: No withdrawal today");

        // Cek apakah ada penarikan yang sedang diproses (dari backend)
        if (this.hasProcessingWithdrawal) {
            console.log("❌ BLOCKED: Has processing withdrawal");
            return {
                allowed: false,
                reason: "processing",
                message:
                    "Anda masih memiliki penarikan yang sedang diproses. Harap tunggu hingga selesai.",
            };
        }
        console.log("✅ PASS: No processing withdrawal");

        // Minimum balance check removed - allow withdrawal regardless of amount
        console.log(
            `💰 Current Balance: Rp ${this.availableBalance.toLocaleString(
                "id-ID"
            )}`
        );
        console.log("✅ PASS: Balance check skipped (always allowed)");

        console.log("═══════════════════════════════════════");
        console.log("✅ ALL CHECKS PASSED - WITHDRAWAL ALLOWED");
        console.log("═══════════════════════════════════════");
        return { allowed: true };
    }

    // ===== INITIALIZATION =====
    init() {
        if (this.isInitialized) return;

        try {
            this.setupEventListeners();
            this.setupMobileMenu();
            this.setupProfileNavigation();
            this.updateAllNotifications();
            this.renderWithdrawalHistory();
            this.updateWithdrawButtonState();

            // Update status setiap 30 detik
            setInterval(() => {
                this.updateWithdrawButtonState();
            }, 30000);

            this.isInitialized = true;

            console.log("PRISMO Saldo Manager initialized");
        } catch (error) {
            console.error("Failed to initialize PRISMO Saldo Manager:", error);
        }
    }

    // ===== EVENT HANDLERS =====
    setupEventListeners() {
        console.log("🔧 Setting up event listeners...");

        // Global click handler for modal close buttons, etc
        document.addEventListener("click", (event) => {
            this.handleGlobalClick(event);
        });

        document.addEventListener("keydown", (event) => {
            this.handleGlobalKeydown(event);
        });
    }

    setupProfileNavigation() {
        const userMenuToggle = document.querySelector(".user-menu__toggle");
        if (userMenuToggle) {
            userMenuToggle.addEventListener("click", (e) => {
                e.preventDefault();
                window.location.href = "/mitra/profil/profil";
            });
        }

        // Avatar now loaded from database via Blade template

        const mobileUserProfile = document.getElementById("mobileUserProfile");
        if (mobileUserProfile) {
            mobileUserProfile.addEventListener("click", (e) => {
                e.preventDefault();
                this.closeMobileMenu();
                window.location.href = "/mitra/profil/profil";
            });
        }
    }

    handleGlobalClick(event) {
        const target = event.target;

        // DEBUG: Log all clicks for troubleshooting
        if (target.id === "tarikSaldoBtn" || target.closest("#tarikSaldoBtn")) {
            console.log("🖱️ TARIK SALDO BUTTON CLICKED!", {
                target: target,
                disabled: target.disabled,
                hasProcessingWithdrawal: this.hasProcessingWithdrawal,
                hasWithdrawnToday: this.hasWithdrawnToday,
            });
        }

        if (
            target.matches("#tarikSaldoBtn") ||
            target.closest("#tarikSaldoBtn")
        ) {
            event.preventDefault();
            event.stopPropagation();
            console.log("✅ Calling showWithdrawModal...");
            this.showWithdrawModal();
            return;
        }

        if (target.matches(".modal-overlay")) {
            this.closeModal(target);
            return;
        }

        if (target.closest('[data-action="cancel"]')) {
            event.preventDefault();
            this.closeCurrentModal();
            return;
        }

        if (target.closest('[data-action="close"]')) {
            event.preventDefault();
            this.closeCurrentModal();
            return;
        }

        if (target.closest('[data-action="submit"]')) {
            event.preventDefault();
            this.submitWithdrawRequest();
            return;
        }

        if (target.matches("#withdrawAmount")) {
            this.handleAmountInput(target);
            return;
        }

        if (target.closest(".menu-toggle")) {
            this.toggleMobileMenu();
            return;
        }

        if (target.closest(".mobile-menu__close")) {
            this.closeMobileMenu();
            return;
        }

        if (target.closest(".modal__close")) {
            event.preventDefault();
            this.closeCurrentModal();
            return;
        }
    }

    handleGlobalKeydown(event) {
        if (event.key === "Escape") {
            const openModal = document.querySelector(".modal-overlay");
            if (openModal) {
                this.closeModal(openModal);
            }
            if (this.isMobileMenuOpen) {
                this.closeMobileMenu();
            }
        }
    }

    // ===== WITHDRAWAL HISTORY RENDER =====
    renderWithdrawalHistory() {
        const riwayatCards = document.querySelector(".riwayat-cards");
        if (!riwayatCards) return;

        riwayatCards.innerHTML = "";

        // Show empty state if no withdrawal history
        if (this.withdrawalHistory.length === 0) {
            riwayatCards.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <h3 class="empty-state-title">Belum Ada Riwayat Penarikan</h3>
                    <p class="empty-state-message">Belum ada permintaan penarikan yang pernah dibuat</p>
                </div>
            `;
            return;
        }

        const sortedHistory = [...this.withdrawalHistory].sort(
            (a, b) =>
                new Date(b.date + " " + b.time) -
                new Date(a.date + " " + a.time)
        );

        sortedHistory.forEach((withdrawal) => {
            const card = this.createWithdrawalCard(withdrawal);
            riwayatCards.appendChild(card);
        });
    }

    createWithdrawalCard(withdrawal) {
        const card = document.createElement("div");
        card.className = "riwayat-card";

        const statusClass = this.getStatusClass(withdrawal.status);
        const statusText = this.getStatusText(withdrawal.status);
        const footerContent = this.getFooterContent(withdrawal);

        card.innerHTML = `
            <div class="riwayat-card-header">
                <div class="riwayat-amount">Rp${withdrawal.amount.toLocaleString(
                    "id-ID"
                )}</div>
                <div class="riwayat-status">
                    <span class="status-badge ${statusClass}">
                        <span class="status-dot"></span>
                        ${statusText}
                    </span>
                </div>
            </div>
            <div class="riwayat-card-content">
                <div class="riwayat-detail">
                    <span class="riwayat-date">${this.formatDate(
                        withdrawal.date
                    )} • ${withdrawal.time}</span>
                </div>
            </div>
            <div class="riwayat-card-footer">
                ${footerContent}
            </div>
        `;

        return card;
    }

    getStatusClass(status) {
        switch (status) {
            case "proses":
                return "status-badge--proses";
            case "berhasil":
                return "status-badge--berhasil";
            case "ditolak":
                return "status-badge--ditolak";
            default:
                return "status-badge--proses";
        }
    }

    getStatusText(status) {
        switch (status) {
            case "proses":
                return "Diproses";
            case "berhasil":
                return "Berhasil";
            case "ditolak":
                return "Ditolak";
            default:
                return "Diproses";
        }
    }

    getFooterContent(withdrawal) {
        switch (withdrawal.status) {
            case "proses":
                return `<span class="riwayat-estimasi">Estimasi: ${this.formatDate(
                    withdrawal.estimatedDate
                )}</span>`;
            case "berhasil":
                return `<span class="riwayat-diterima">Diterima: ${this.formatDateTime(
                    withdrawal.receivedDate,
                    withdrawal.receivedTime
                )}</span>`;
            case "ditolak":
                return `<span class="riwayat-ditolak">Ditolak: ${this.formatDateTime(
                    withdrawal.receivedDate,
                    withdrawal.receivedTime
                )}</span>`;
            default:
                return "";
        }
    }

    formatDate(dateString) {
        if (!dateString) return "";

        const date = new Date(dateString);
        const options = {
            day: "2-digit",
            month: "short",
            year: "numeric",
        };
        return date.toLocaleDateString("id-ID", options);
    }

    formatDateTime(dateString, timeString) {
        if (!dateString) return "";

        const date = new Date(dateString);
        const options = {
            day: "2-digit",
            month: "short",
            year: "numeric",
        };
        const formattedDate = date.toLocaleDateString("id-ID", options);

        return timeString ? `${formattedDate} • ${timeString}` : formattedDate;
    }

    // ===== WITHDRAWAL MANAGEMENT =====
    updateWithdrawButtonState() {
        const tarikSaldoBtn = document.getElementById("tarikSaldoBtn");
        if (!tarikSaldoBtn) {
            console.log("⚠️ Tombol tarikSaldoBtn tidak ditemukan");
            return;
        }

        const hasPendingWithdrawal = this.hasPendingWithdrawal();
        const withdrawCheck = this.canWithdrawNow();

        console.log("═══════════════════════════════════════");
        console.log("🔄 UPDATE BUTTON STATE - DEBUGGING");
        console.log("═══════════════════════════════════════");
        console.log("1️⃣ Pending Withdrawal Check:", hasPendingWithdrawal);
        console.log("2️⃣ Can Withdraw Now:", withdrawCheck.allowed);
        console.log("3️⃣ Reason:", withdrawCheck.reason || "allowed");
        console.log("4️⃣ Message:", withdrawCheck.message || "OK");
        console.log("═══════════════════════════════════════");

        if (hasPendingWithdrawal) {
            tarikSaldoBtn.disabled = true;
            tarikSaldoBtn.style.opacity = "0.6";
            tarikSaldoBtn.style.cursor = "not-allowed";
            tarikSaldoBtn.title = "Masih ada penarikan dalam proses";
            console.log("❌ RESULT: DISABLED - Ada pending withdrawal");
            console.log(
                '💡 SOLUSI: Ubah status "proses" menjadi "berhasil" atau "ditolak" di mock data'
            );
        } else if (!withdrawCheck.allowed) {
            tarikSaldoBtn.disabled = true;
            tarikSaldoBtn.style.opacity = "0.6";
            tarikSaldoBtn.style.cursor = "not-allowed";
            tarikSaldoBtn.title = withdrawCheck.message;
            console.log("❌ RESULT: DISABLED -", withdrawCheck.message);
            if (withdrawCheck.reason === "daily_limit") {
                console.log(
                    "💡 SOLUSI: Jalankan SaldoManager.resetDailyLimit() di console"
                );
            } else if (withdrawCheck.reason === "time") {
                console.log(
                    "💡 SOLUSI: Tunggu sampai jam 08:00 atau ubah jam sistem"
                );
            }
        } else {
            tarikSaldoBtn.disabled = false;
            tarikSaldoBtn.style.opacity = "1";
            tarikSaldoBtn.style.cursor = "pointer";
            tarikSaldoBtn.title = "Tarik saldo";
            console.log("✅ Tombol enabled");
        }
    }

    hasPendingWithdrawal() {
        // Use backend data instead of checking withdrawalHistory
        console.log("🔍 hasPendingWithdrawal check:", {
            hasProcessingWithdrawal: this.hasProcessingWithdrawal,
            value: !!this.hasProcessingWithdrawal,
        });
        return this.hasProcessingWithdrawal;
    }

    showWithdrawModal() {
        console.log("🎯 showWithdrawModal CALLED");

        // Cek apakah bisa menarik saldo sekarang
        const withdrawCheck = this.canWithdrawNow();
        console.log("🔍 withdrawCheck result:", withdrawCheck);

        if (!withdrawCheck.allowed) {
            console.log("❌ Withdrawal not allowed:", withdrawCheck.message);
            this.showAlert(
                "error",
                "Tidak Dapat Menarik Saldo",
                withdrawCheck.message
            );
            return;
        }

        if (this.hasPendingWithdrawal()) {
            console.log("❌ Has pending withdrawal");
            this.showAlert(
                "error",
                "Peringatan",
                "Masih ada penarikan dalam proses. Tunggu hingga selesai untuk melakukan penarikan baru."
            );
            return;
        }

        console.log("✅ Opening withdraw modal...");
        const template = document.getElementById("withdrawModalTemplate");
        if (!template) {
            console.error("❌ withdrawModalTemplate not found!");
            return;
        }

        const modal = template.content.cloneNode(true);
        document.body.appendChild(modal);

        // Update saldo tersedia dengan data dari database
        const saldoAmount = document.querySelector(
            ".saldo-available .saldo-amount"
        );
        if (saldoAmount) {
            saldoAmount.textContent = `Rp${this.availableBalance.toLocaleString(
                "id-ID"
            )}`;
        }

        const amountInput = document.getElementById("withdrawAmount");
        if (amountInput) {
            amountInput.removeAttribute("readonly");
            amountInput.value = "";
            amountInput.placeholder = "50000";
            amountInput.addEventListener("input", (e) =>
                this.handleAmountInput(e.target)
            );
        }

        const modalElement = document.querySelector(".modal-overlay");
        this.setupModalFocusTrap(modalElement);
        console.log("✅ Modal opened successfully");
    }

    handleAmountInput(input) {
        let value = input.value.replace(/[^\d]/g, "");
        let numericValue = parseInt(value) || 0;
        input.value =
            numericValue === 0 ? "" : numericValue.toLocaleString("id-ID");
        this.updateSubmitButtonState(numericValue);
    }

    updateSubmitButtonState(amount) {
        const submitButton = document.querySelector('[data-action="submit"]');
        if (!submitButton) return;

        const isValid =
            amount >= this.minimumWithdrawal && amount <= this.availableBalance;

        submitButton.disabled = !isValid;
        submitButton.style.opacity = isValid ? "1" : "0.6";
        submitButton.style.cursor = isValid ? "pointer" : "not-allowed";
    }

    submitWithdrawRequest() {
        const amountInput = document.getElementById("withdrawAmount");
        if (!amountInput) return;

        const amount = parseInt(amountInput.value.replace(/[^\d]/g, "")) || 0;

        if (amount < this.minimumWithdrawal) {
            this.showAlert(
                "error",
                "Error",
                `Minimum penarikan adalah Rp${this.minimumWithdrawal.toLocaleString(
                    "id-ID"
                )}`
            );
            return;
        }

        if (amount > this.availableBalance) {
            this.showAlert(
                "error",
                "Error",
                "Jumlah penarikan melebihi saldo tersedia"
            );
            return;
        }

        this.closeCurrentModal();
        this.processWithdrawal(amount);
    }

    async processWithdrawal(amount) {
        try {
            // Show loading
            this.showAlert(
                "info",
                "Memproses",
                "Sedang mengajukan penarikan..."
            );

            const formData = new FormData();
            formData.append("amount", amount);
            formData.append("bank_name", "BCA"); // Default, bisa diubah dari form nanti
            formData.append("account_number", "1234567890"); // Default, bisa diubah dari form nanti
            formData.append("account_name", "Mitra"); // Default, bisa diubah dari form nanti

            const response = await fetch("/api/withdrawals", {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
                credentials: "same-origin",
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                this.showAlert(
                    "error",
                    "Gagal",
                    data.message ||
                        "Terjadi kesalahan saat mengajukan penarikan"
                );
                return;
            }

            console.log("✅ Withdrawal submitted:", data);

            // Reload data from server
            await this.loadWithdrawalHistory();

            // Update status
            this.hasWithdrawnToday = true;
            this.hasProcessingWithdrawal = true;

            // Update UI
            this.updateWithdrawButtonState();

            // Show success
            this.showWithdrawConfirmation(amount);

            // Reload halaman setelah 2 detik untuk refresh saldo
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } catch (error) {
            console.error("Error submitting withdrawal:", error);
            this.showAlert(
                "error",
                "Error",
                "Terjadi kesalahan saat mengajukan penarikan"
            );
        }
    }

    showWithdrawConfirmation(amount) {
        const template = document.getElementById(
            "confirmWithdrawModalTemplate"
        );
        if (!template) return;

        const modal = template.content.cloneNode(true);

        const netAmount = amount - this.adminFee;
        const message = modal.querySelector(".modal__message");
        if (message) {
            message.innerHTML = `Saldo sebesar <strong>Rp${amount.toLocaleString(
                "id-ID"
            )}</strong> akan diproses oleh admin`;
        }

        document.body.appendChild(modal);

        const modalElement = document.querySelector(".modal-overlay");
        this.setupModalFocusTrap(modalElement);
    }

    // ===== NOTIFICATION SYSTEM =====
    updateAllNotifications() {
        this.updateNavBadges();
        this.updateMobileBadges();
    }

    updateNavBadges() {
        const antrianBadge = document.getElementById("antrian-badge");
        const reviewBadge = document.getElementById("review-badge");

        if (antrianBadge && this.notifications.antrian > 0) {
            antrianBadge.textContent = this.notifications.antrian;
            antrianBadge.style.display = "flex";
        }

        if (reviewBadge && this.notifications.review > 0) {
            reviewBadge.textContent = this.notifications.review;
            reviewBadge.style.display = "flex";
        }
    }

    updateMobileBadges() {
        const antrianBadge = document.getElementById("mobile-antrian-badge");
        const reviewBadge = document.getElementById("mobile-review-badge");

        if (antrianBadge && this.notifications.antrian > 0) {
            antrianBadge.textContent = this.notifications.antrian;
            antrianBadge.style.display = "flex";
        }

        if (reviewBadge && this.notifications.review > 0) {
            reviewBadge.textContent = this.notifications.review;
            reviewBadge.style.display = "flex";
        }
    }

    // ===== MOBILE MENU =====
    setupMobileMenu() {
        const template = document.getElementById("mobileMenuTemplate");
        if (template) {
            const mobileMenu = template.content.cloneNode(true);
            document.body.appendChild(mobileMenu);
            this.setupMobileMenuEvents();
        }
    }

    setupMobileMenuEvents() {
        const mobileMenu = document.getElementById("mobileMenu");
        const closeButton = document.getElementById("mobileMenuClose");

        if (!mobileMenu || !closeButton) return;

        closeButton.addEventListener("click", () => {
            this.closeMobileMenu();
        });

        this.updateMobileBadges();

        mobileMenu.addEventListener("click", (event) => {
            if (event.target === mobileMenu) {
                this.closeMobileMenu();
            }
        });
    }

    toggleMobileMenu() {
        if (this.isMobileMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    openMobileMenu() {
        const mobileMenu = document.getElementById("mobileMenu");
        const menuToggle = document.getElementById("menuToggle");

        if (mobileMenu && menuToggle) {
            mobileMenu.classList.add("mobile-menu--open");
            menuToggle.setAttribute("aria-expanded", "true");
            menuToggle.classList.add("menu-toggle--active");
            this.isMobileMenuOpen = true;
            document.body.style.overflow = "hidden";
        }
    }

    closeMobileMenu() {
        const mobileMenu = document.getElementById("mobileMenu");
        const menuToggle = document.getElementById("menuToggle");

        if (mobileMenu && menuToggle) {
            mobileMenu.classList.remove("mobile-menu--open");
            menuToggle.setAttribute("aria-expanded", "false");
            menuToggle.classList.remove("menu-toggle--active");
            this.isMobileMenuOpen = false;
            document.body.style.overflow = "";
        }
    }

    // ===== MODAL MANAGEMENT =====
    setupModalFocusTrap(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );

        if (focusableElements.length === 0) return;

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        modal.addEventListener("keydown", (event) => {
            if (event.key !== "Tab") return;

            if (event.shiftKey) {
                if (document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                }
            } else {
                if (document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }
        });

        firstElement.focus();
    }

    closeCurrentModal() {
        const modal = document.querySelector(".modal-overlay");
        if (modal && document.body.contains(modal)) {
            document.body.removeChild(modal);
        }
    }

    closeModal(modal) {
        if (document.body.contains(modal)) {
            document.body.removeChild(modal);
        }
    }

    // ===== ALERT SYSTEM =====
    showAlert(type, title, message) {
        const alert = document.createElement("div");
        alert.className = `alert alert--${type}`;

        const icon =
            type === "success"
                ? "✓"
                : type === "error"
                ? "✕"
                : type === "warning"
                ? "!"
                : "i";

        alert.innerHTML = `
            <div class="alert__icon">${icon}</div>
            <div class="alert__content">
                <div class="alert__title">${title}</div>
                <div class="alert__message">${message}</div>
            </div>
            <button class="alert__close">✕</button>
        `;

        document.body.appendChild(alert);

        const closeBtn = alert.querySelector(".alert__close");
        closeBtn.addEventListener("click", () => {
            this.closeAlert(alert);
        });

        setTimeout(() => {
            if (document.body.contains(alert)) {
                this.closeAlert(alert);
            }
        }, 5000);
    }

    closeAlert(alert) {
        alert.classList.add("alert--closing");
        setTimeout(() => {
            if (document.body.contains(alert)) {
                alert.remove();
            }
        }, 300);
    }
}

// ===== INITIALIZATION =====
document.addEventListener("DOMContentLoaded", () => {
    try {
        const saldoManager = new PrismoSaldoManager();
        saldoManager.init();

        // Expose to window for onclick handler
        window.prismoSaldoManager = saldoManager;
        console.log("✅ prismoSaldoManager exposed to window");
        window.prismoSaldo = saldoManager;
        window.SaldoManager = saldoManager; // Expose untuk testing

        console.log("PRISMO Saldo System loaded successfully");
    } catch (error) {
        console.error("Failed to load PRISMO Saldo System:", error);

        const main = document.getElementById("mainContent");
        if (main) {
            main.innerHTML = `
                <div style="text-align: center; padding: 2rem;">
                    <p>Terjadi kesalahan saat memuat halaman saldo. Silakan refresh halaman.</p>
                    <button onclick="location.reload()" class="btn btn--primary" style="margin-top: 1rem;">
                        Refresh Halaman
                    </button>
                </div>
            `;
        }
    }
});

// Add alert styles
const style = document.createElement("style");
style.textContent = `
    .alert {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        z-index: 1060;
        max-width: 400px;
        animation: alert-slide-in 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: white;
        border: 1px solid #e2e8f0;
        border-left: 4px solid;
    }

    .alert--success {
        border-left-color: #10b981;
    }

    .alert--error {
        border-left-color: #ef4444;
    }

    .alert--warning {
        border-left-color: #f59e0b;
    }

    .alert__icon {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .alert__content {
        flex: 1;
    }

    .alert__title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .alert__message {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .alert__close {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .alert__close:hover {
        opacity: 1;
    }

    @keyframes alert-slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .alert--closing {
        animation: alert-slide-out 0.3s ease forwards;
    }

    @keyframes alert-slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
