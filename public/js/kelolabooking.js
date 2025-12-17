// kelolabooking.js - Sistem Kelola Booking Prismo

// ===== KONFIGURASI DAN DATA =====

// State untuk filter
let currentFilter = "all";

// State untuk pagination
let currentPage = 1;
const itemsPerPage = 30;

// Use real data from server - NO MORE MOCK DATA
// Deduplicate bookings by ID to prevent duplicate rows
const uniqueBookings = window.bookingsData
    ? Array.from(new Map(window.bookingsData.map((b) => [b.id, b])).values())
    : [];

console.log("🔍 RAW BOOKINGS DATA:", window.bookingsData);
console.log("🔍 UNIQUE BOOKINGS:", uniqueBookings);
console.log("🔍 SAMPLE BOOKING (first):", uniqueBookings[0]);

const mockData = {
    bookings: uniqueBookings,
};

// ===== VARIABEL GLOBAL =====
let currentBooking = null;

// ===== FUNGSI UTAMA =====

// Initialize aplikasi
function initializeApp() {
    console.log("🚀 Initializing Kelola Booking App...");

    // Load data dan render tabel
    loadMockData();

    // Setup event listeners
    setupEventListeners();

    // Setup search functionality
    initSearch();

    // Setup table actions
    initTableActions();

    // Setup payment proof controls
    initPaymentProofControls();

    // Setup copy wallet functionality
    initCopyWallet();

    // Setup copy refund account functionality
    initCopyRefundAccount();

    console.log("✅ Kelola Booking App initialized successfully");
}

// Load dan render data mock
function loadMockData() {
    console.log("📊 Loading mock data...");
    renderTableData();
}

// Fetch bookings from API
async function fetchBookingsFromAPI() {
    try {
        const response = await fetch("/api/bookings", {
            credentials: "include",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!response.ok) {
            throw new Error("Failed to fetch bookings");
        }

        const bookings = await response.json();

        // Map bookings to ensure all required fields exist
        const mappedBookings = bookings.map((booking) => ({
            ...booking,
            customer_name:
                booking.customer?.name || booking.customer_name || "-",
            customer_email:
                booking.customer?.email || booking.customer_email || "-",
            mitra_name: booking.mitra?.name || booking.mitra_name || "-",
            service_name: booking.service_type || booking.service_name || "-",
            total_price: booking.final_price || booking.total_price || 0,
            payment_method: booking.payment_method || "-",
            wallet_number:
                booking.customer?.phone || booking.wallet_number || "-",
            refund_method:
                booking.refund_method ||
                booking.customer?.refund_method ||
                null,
            refund_account_number:
                booking.refund_account_number ||
                booking.customer?.refund_account_number ||
                null,
        }));

        // Update mockData with fresh data from API
        const uniqueBookings = Array.from(
            new Map(mappedBookings.map((b) => [b.id, b])).values()
        );
        mockData.bookings = uniqueBookings;

        console.log("✅ Bookings loaded from API:", uniqueBookings.length);

        return uniqueBookings;
    } catch (error) {
        console.error("❌ Error fetching bookings:", error);
        return mockData.bookings; // Return existing data on error
    }
}

// Filter booking berdasarkan status
function filterBookingsByStatus(bookings) {
    if (currentFilter === "all") {
        return bookings;
    }
    return bookings.filter((b) => b.status === currentFilter);
}

// Sort booking berdasarkan aturan filter
function getSortedBookings(bookings) {
    // All statuses sorted by created_at descending (newest first)
    return [...bookings].sort((a, b) => {
        const dateA = new Date(a.created_at);
        const dateB = new Date(b.created_at);
        return dateB - dateA;
    });
}

// Render data ke tabel
async function renderTableData() {
    const tbody = document.getElementById("bookingTableBody");
    if (!tbody) {
        console.error("❌ Tabel body tidak ditemukan!");
        return;
    }

    // Show loading state
    tbody.innerHTML = `
        <tr>
            <td colspan="8" style="text-align: center; padding: 40px;">
                <div style="font-size: 24px; margin-bottom: 10px;">⏳</div>
                <div>Memuat data booking...</div>
            </td>
        </tr>
    `;

    // Fetch fresh data from API
    await fetchBookingsFromAPI();

    tbody.innerHTML = "";

    // Filter out completed bookings (status 'selesai')
    const activeBookings = mockData.bookings.filter(
        (b) => b.status !== "selesai"
    );

    // Terapkan filter berdasarkan status
    const filteredBookings = filterBookingsByStatus(activeBookings);

    // Terapkan sorting berdasarkan filter aktif
    const sortedBookings = getSortedBookings(filteredBookings);

    // Pagination: hitung total halaman
    const totalPages = Math.ceil(sortedBookings.length / itemsPerPage);

    // Pastikan currentPage tidak melebihi total halaman
    if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages;
    }
    if (currentPage < 1) {
        currentPage = 1;
    }

    // Ambil data untuk halaman saat ini
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageBookings = sortedBookings.slice(startIndex, endIndex);

    if (pageBookings.length === 0 && sortedBookings.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="empty-state">
                    <div class="icon"></div>
                    <p>Tidak ada data booking</p>
                    <span class="empty-subtitle">Belum ada booking yang tersedia saat ini</span>
                </td>
            </tr>
        `;
        renderPagination(0, 0);
        return;
    }

    pageBookings.forEach((booking, index) => {
        const globalIndex = startIndex + index;
        const row = createBookingRow(booking, globalIndex);
        tbody.appendChild(row);
    });

    // Render pagination controls
    renderPagination(sortedBookings.length, totalPages);

    console.log(
        `✅ Rendered ${pageBookings.length} of ${sortedBookings.length} booking rows (page ${currentPage}/${totalPages}, filter: ${currentFilter})`
    );
}

// Render pagination controls
function renderPagination(totalItems, totalPages) {
    const paginationContainer = document.getElementById("paginationControls");
    if (!paginationContainer) return;

    if (totalPages <= 1) {
        paginationContainer.innerHTML = "";
        return;
    }

    let paginationHTML = '<div class="pagination">';

    // Previous button
    if (currentPage > 1) {
        paginationHTML += `<button class="pagination-btn" onclick="BookingManager.changePage(${
            currentPage - 1
        })">&laquo; Prev</button>`;
    } else {
        paginationHTML += `<button class="pagination-btn" disabled>&laquo; Prev</button>`;
    }

    // Page info
    paginationHTML += `<span class="pagination-info">Halaman ${currentPage} dari ${totalPages}</span>`;

    // Next button
    if (currentPage < totalPages) {
        paginationHTML += `<button class="pagination-btn" onclick="BookingManager.changePage(${
            currentPage + 1
        })">Next &raquo;</button>`;
    } else {
        paginationHTML += `<button class="pagination-btn" disabled>Next &raquo;</button>`;
    }

    paginationHTML += "</div>";
    paginationContainer.innerHTML = paginationHTML;
}

// Change page
function changePage(newPage) {
    currentPage = newPage;
    renderTableData();
    // Scroll to top of table
    document
        .querySelector(".table-wrapper")
        ?.scrollIntoView({ behavior: "smooth", block: "start" });
}

// Buat row tabel untuk setiap booking
function createBookingRow(booking, index) {
    const row = document.createElement("tr");
    row.setAttribute("data-id", booking.id);

    const statusBadgeClass = `status-badge ${booking.status}`;
    const statusBadgeText = getStatusText(booking.status);

    let actionButtons = "";
    if (booking.status === "cek_transaksi" || booking.status === "menunggu") {
        actionButtons = `
            <div class="action-buttons">
                <button class="btn btn-danger btn-sm cancel-btn" data-id="${booking.id}">
                    Batalkan
                </button>
                <button class="btn btn-success btn-sm confirm-btn" data-id="${booking.id}">
                    Konfirmasi Booking
                </button>
            </div>
        `;
    } else if (booking.status === "dibatalkan") {
        // Check if refund already completed
        if (booking.refund_completed_at) {
            actionButtons = `<span class="status-badge completed">Dana Dikembalikan</span>`;
        } else {
            actionButtons = `
                <button class="btn btn-primary btn-sm refund-btn" data-id="${booking.id}">
                    Kembalikan Dana
                </button>
            `;
        }
    } else {
        actionButtons = `<span class="status-badge ${booking.status}">${statusBadgeText}</span>`;
    }

    // Format booking date and time
    const bookingDate = booking.booking_date
        ? booking.booking_date.split("T")[0]
        : "-";
    const bookingTime = booking.booking_time || "-";
    const bookingDateTime = `${bookingDate} | ${bookingTime}`;

    row.innerHTML = `
        <td>${index + 1}</td>
        <td>
            <div class="cell-main">
                <div>${escapeHtml(booking.customer_name || "-")}</div>
                <small>${escapeHtml(booking.customer_email || "-")}</small>
            </div>
        </td>
        <td>${escapeHtml(booking.mitra_name || "-")}</td>
        <td>${escapeHtml(booking.service_name || "-")}</td>
        <td>Rp ${formatCurrency(booking.total_price || 0)}</td>
        <td>${bookingDateTime}</td>
        <td>
            <span class="${statusBadgeClass}">${statusBadgeText}</span>
        </td>
        <td>
            ${actionButtons}
        </td>
    `;

    return row;
}

// Update row booking setelah perubahan status
function updateBookingRow(booking) {
    const row = document.querySelector(`tr[data-id="${booking.id}"]`);
    if (!row) return;

    const statusBadgeText = getStatusText(booking.status);

    const statusCell = row.cells[6];
    statusCell.innerHTML = `<span class="status-badge ${booking.status}">${statusBadgeText}</span>`;

    const actionCell = row.cells[7];
    if (booking.status === "pending") {
        actionCell.innerHTML = `
            <div class="action-buttons">
                <button class="btn btn-danger btn-sm cancel-btn" data-id="${booking.id}">
                    Batalkan
                </button>
                <button class="btn btn-success btn-sm confirm-btn" data-id="${booking.id}">
                    Konfirmasi Booking
                </button>
            </div>
        `;
    } else if (booking.status === "cancelled") {
        // Check if refund already completed
        if (booking.refund_completed_at) {
            actionCell.innerHTML = `<span class="status-badge completed">Dana Dikembalikan</span>`;
        } else {
            actionCell.innerHTML = `
                <button class="btn btn-primary btn-sm refund-btn" data-id="${booking.id}">
                    Kembalikan Dana
                </button>
            `;
        }
    } else {
        actionCell.innerHTML = `<span class="status-badge ${booking.status}">${statusBadgeText}</span>`;
    }
}

// ===== MODAL FUNCTIONS =====
function showModal(modalId, booking = null) {
    console.log("📂 Showing modal:", modalId, "with booking:", booking);
    const modal = document.getElementById(modalId);
    if (modal) {
        if (booking) {
            currentBooking = booking;
            console.log("💾 Saved currentBooking:", currentBooking);
        }

        if (modalId === "refundModal" && booking) {
            populateRefundModal(booking);
        } else if (modalId === "paymentDetailModal" && booking) {
            populatePaymentDetailModal(booking);
        } else if (modalId === "cancelModal" && booking) {
            document.getElementById("cancelCustomerName").textContent =
                booking.customer_name || booking.customer;
        } else if (modalId === "confirmRefundModal" && booking) {
            document.getElementById("confirmCustomerName").textContent =
                booking.customer_name || booking.customer;
        }

        modal.classList.add("show");
        document.body.style.overflow = "hidden";
    }
}

function hideModal(modalId) {
    console.log("📂 Hiding modal:", modalId);
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove("show");

        const modals = [
            "logoutModal",
            "cancelModal",
            "confirmRefundModal",
            "refundModal",
            "paymentDetailModal",
        ];
        const anyModalOpen = modals.some((id) =>
            document.getElementById(id).classList.contains("show")
        );

        if (!anyModalOpen) {
            document.body.style.overflow = "";
        }
    }
}

function hideAllModals() {
    const modals = [
        "logoutModal",
        "cancelModal",
        "confirmRefundModal",
        "refundModal",
        "paymentDetailModal",
    ];
    modals.forEach((modalId) => hideModal(modalId));
}

// Populate modal refund dengan data booking
function populateRefundModal(booking) {
    console.log("🔍 Refund Modal - Full booking data:", booking);
    console.log("🔍 Refund Method:", booking.refund_method);
    console.log("🔍 Refund Method TYPE:", typeof booking.refund_method);
    console.log("🔍 Refund Method === null?", booking.refund_method === null);
    console.log(
        '🔍 Refund Method === "null"?',
        booking.refund_method === "null"
    );
    console.log(
        "🔍 Refund Method === undefined?",
        booking.refund_method === undefined
    );
    console.log(
        "🔍 Refund Method value:",
        JSON.stringify(booking.refund_method)
    );
    console.log("🔍 Refund Account:", booking.refund_account_number);
    console.log(
        "🔍 Refund Account TYPE:",
        typeof booking.refund_account_number
    );
    console.log(
        "🔍 Refund Account value:",
        JSON.stringify(booking.refund_account_number)
    );

    // Populate transaction details
    document.getElementById("refundBookingId").textContent = booking.id;

    // Format tanggal
    const dateObj = new Date(booking.booking_date);
    const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    document.getElementById("refundBookingDate").textContent =
        dateObj.toLocaleDateString("id-ID", options);

    // Format waktu
    document.getElementById("refundBookingTime").textContent =
        booking.booking_time;

    document.getElementById("refundCustomer").textContent =
        booking.customer_name;
    document.getElementById("refundMitra").textContent = booking.mitra_name;
    document.getElementById("refundLayanan").textContent = booking.service_name;
    document.getElementById("refundTotal").textContent = `Rp ${formatCurrency(
        booking.total_price
    )}`;

    // Populate refund method info
    const refundMethodBadge = document.getElementById("refundMethodBadge");
    const refundAccountNumber = document.getElementById("refundAccountNumber");

    console.log("🔍 Badge element:", refundMethodBadge);
    console.log("🔍 Account element:", refundAccountNumber);
    console.log(
        "🔍 Badge current text:",
        refundMethodBadge ? refundMethodBadge.textContent : "NOT FOUND"
    );
    console.log(
        "🔍 Account current text:",
        refundAccountNumber ? refundAccountNumber.textContent : "NOT FOUND"
    );

    // Force update with slight delay to ensure DOM is ready
    setTimeout(() => {
        const badge = document.getElementById("refundMethodBadge");
        const account = document.getElementById("refundAccountNumber");

        if (badge) {
            if (
                booking.refund_method &&
                booking.refund_method !== null &&
                booking.refund_method !== "null"
            ) {
                badge.textContent = booking.refund_method;
                badge.className =
                    "ewallet-badge " + booking.refund_method.toLowerCase();
                badge.style.display = "inline-block";
                badge.style.visibility = "visible";
                console.log(
                    "✅✅ FINAL Badge set to:",
                    badge.textContent,
                    "Display:",
                    badge.style.display
                );
            } else {
                badge.textContent = "-";
                badge.className = "ewallet-badge";
                badge.style.display = "inline-block";
                console.log("⚠️ No refund method, set to -");
            }
        } else {
            console.error(
                "❌ refundMethodBadge element not found in setTimeout!"
            );
        }

        if (account) {
            const accountNum = booking.refund_account_number;
            if (accountNum && accountNum !== null && accountNum !== "null") {
                account.textContent = accountNum;
                account.style.display = "inline";
                account.style.visibility = "visible";
                console.log(
                    "✅✅ FINAL Account number set to:",
                    account.textContent
                );
            } else {
                account.textContent = "-";
                console.log("⚠️ No account number, set to -");
            }
        } else {
            console.error(
                "❌ refundAccountNumber element not found in setTimeout!"
            );
        }
    }, 100);

    loadPaymentProof(booking);
}

// Populate modal detail pembayaran untuk konfirmasi booking
function populatePaymentDetailModal(booking) {
    // Populate booking details in table format
    document.getElementById("bookingId").textContent = booking.id || "-";
    document.getElementById("bookingDate").textContent =
        formatDate(booking.booking_date) || "-";
    document.getElementById("bookingTime").textContent =
        booking.booking_time || "-";
    document.getElementById("bookingCustomerName").textContent =
        booking.customer_name || "-";
    document.getElementById("bookingMitraName").textContent =
        booking.mitra_name || "-";
    document.getElementById("bookingServiceName").textContent =
        booking.service_name || "-";
    document.getElementById("bookingTotalPrice").textContent = formatCurrency(
        booking.total_price || 0
    );

    // Handle notes (optional field - show/hide row)
    const notesRow = document.getElementById("bookingNotesRow");
    const notesElement = document.getElementById("bookingNotes");
    if (booking.notes && booking.notes.trim() !== "") {
        notesElement.textContent = booking.notes;
        notesRow.style.display = "";
    } else {
        notesRow.style.display = "none";
    }

    // Load payment proof
    loadPaymentDetailProof(booking);
}

// Format currency helper
function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

// Format date helper
function formatDate(dateString) {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
}

// Load bukti pembayaran ke modal
function loadPaymentProof(booking) {
    const proofImage = document.getElementById("proofImage");
    const proofPlaceholder = document.getElementById("proofPlaceholder");
    const imageControls = document.querySelector(
        "#refundModal .image-controls"
    );

    const hasPaymentProof =
        booking.payment_proof !== null && booking.payment_proof !== undefined;

    console.log(
        "🖼️ Loading refund payment proof:",
        hasPaymentProof,
        booking.payment_proof
    );

    if (hasPaymentProof) {
        // Hide placeholder
        if (proofPlaceholder) {
            proofPlaceholder.style.display = "none";
        }

        // Show image - use storage path
        const imagePath = booking.payment_proof.startsWith("storage/")
            ? `/${booking.payment_proof}`
            : `/storage/${booking.payment_proof}`;
        proofImage.src = imagePath;
        proofImage.alt = `Bukti Pembayaran - ${booking.customer_name}`;
        proofImage.style.display = "block";

        // Show controls
        if (imageControls) {
            imageControls.style.display = "flex";
        }

        // Handle image load error
        proofImage.onerror = function () {
            console.error("❌ Failed to load refund payment proof image");
            proofImage.style.display = "none";
            if (proofPlaceholder) {
                proofPlaceholder.style.display = "flex";
            }
            if (imageControls) {
                imageControls.style.display = "none";
            }
        };

        // Handle successful load
        proofImage.onload = function () {
            console.log("✅ Refund payment proof image loaded successfully");
        };
    } else {
        console.log("⚠️ No refund payment proof available");
        proofImage.style.display = "none";
        if (proofPlaceholder) {
            proofPlaceholder.style.display = "flex";
        }
        if (imageControls) {
            imageControls.style.display = "none";
        }
    }
}

// Load bukti pembayaran ke modal detail pembayaran
function loadPaymentDetailProof(booking) {
    const proofImage = document.getElementById("paymentProofImage");
    const proofPlaceholder = document.getElementById("paymentProofPlaceholder");
    const proofContainer = document.querySelector(
        "#paymentDetailModal .proof-image-container"
    );
    const imageControls = document
        .getElementById("paymentDetailModal")
        ?.querySelector(".image-controls");

    const hasPaymentProof =
        booking.payment_proof !== null && booking.payment_proof !== undefined;

    console.log(
        "🖼️ Loading payment proof:",
        hasPaymentProof,
        booking.payment_proof
    );

    if (hasPaymentProof) {
        // Hide placeholder
        if (proofPlaceholder) {
            proofPlaceholder.style.display = "none";
        }

        // Show image - use storage path
        const imagePath = booking.payment_proof.startsWith("storage/")
            ? `/${booking.payment_proof}`
            : `/storage/${booking.payment_proof}`;
        proofImage.src = imagePath;
        proofImage.alt = `Bukti Pembayaran - ${booking.customer_name}`;
        proofImage.style.display = "block";

        // Show controls
        if (imageControls) {
            imageControls.style.display = "flex";
        }

        // Handle image load error
        proofImage.onerror = function () {
            console.error("❌ Failed to load payment proof image:", imagePath);
            proofImage.style.display = "none";
            if (proofPlaceholder) {
                proofPlaceholder.style.display = "flex";
            }
            if (imageControls) {
                imageControls.style.display = "none";
            }
        };

        // Handle successful load
        proofImage.onload = function () {
            console.log("✅ Payment proof image loaded successfully");
        };
    } else {
        console.log("⚠️ No payment proof available");
        proofImage.style.display = "none";
        if (proofPlaceholder) {
            proofPlaceholder.style.display = "flex";
        }
        if (imageControls) {
            imageControls.style.display = "none";
        }
    }
}

// ===== EVENT HANDLERS =====
async function handleCancelBooking() {
    if (!currentBooking) {
        console.log("🚨 No current booking for cancel!");
        return;
    }

    const booking = currentBooking;
    const confirmBtn = document.getElementById("confirmCancel");
    const originalText = confirmBtn.textContent;
    const reasonInput = document.getElementById("cancelReason");
    const reason = reasonInput ? reasonInput.value.trim() : "";

    if (!reason) {
        showNotification("Harap isi alasan pembatalan", "error");
        return;
    }

    confirmBtn.textContent = "Memproses...";
    confirmBtn.disabled = true;

    try {
        const response = await fetch(
            `/admin/kelolabooking/${booking.id}/cancel`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content,
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin",
                body: JSON.stringify({
                    cancellation_reason: reason,
                }),
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.message || "Gagal membatalkan booking");
        }

        showNotification(
            `Booking dari ${booking.customer_name} berhasil dibatalkan`,
            "success"
        );

        // Reload page to refresh data
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    } catch (error) {
        console.error("❌ Cancel error:", error);
        showNotification(error.message || "Gagal membatalkan booking", "error");
        confirmBtn.textContent = originalText;
        confirmBtn.disabled = false;
    }
}

async function handleConfirmRefund() {
    if (!currentBooking) {
        showNotification("Error: Data booking tidak ditemukan", "error");
        return;
    }

    const booking = currentBooking;
    const confirmBtn = document.getElementById("confirmRefund");
    const originalText = confirmBtn.textContent;

    confirmBtn.textContent = "Memproses...";
    confirmBtn.disabled = true;

    try {
        const response = await fetch(
            `/admin/kelolabooking/${booking.id}/refund`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content,
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin",
            }
        );

        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(
                data.message || "Gagal menyelesaikan pengembalian dana"
            );
        }

        // Close modal
        hideModal("confirmRefundModal");

        // Show success notification
        showNotification(
            `Pengembalian dana untuk ${booking.customer_name} berhasil diselesaikan`,
            "success"
        );

        // Reload booking data tanpa reload halaman
        setTimeout(() => {
            renderTableData();
            currentBooking = null;
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        }, 500);
    } catch (error) {
        console.error("❌ Refund error:", error);
        showNotification(
            error.message || "Gagal menyelesaikan pengembalian dana",
            "error"
        );
        confirmBtn.textContent = originalText;
        confirmBtn.disabled = false;
    }
}

function handleRefundConfirm() {
    const tempBooking = currentBooking;
    hideModal("refundModal");

    setTimeout(() => {
        showModal("confirmRefundModal", tempBooking);
    }, 100);
}

// ===== DOWNLOAD FUNCTIONALITY =====
async function downloadPaymentProof(booking) {
    if (!booking || !booking.payment_proof) {
        showNotification("Tidak ada bukti pembayaran untuk diunduh", "warning");
        return;
    }

    console.log("📥 Starting download for:", booking.customer_name);

    // Show loading state
    const downloadBtn =
        document.getElementById("downloadProofBtn") ||
        document.getElementById("downloadPaymentProofBtn");
    if (!downloadBtn) {
        console.error("Download button not found");
        return;
    }

    const originalHTML = downloadBtn.innerHTML;
    downloadBtn.innerHTML = "⏳";
    downloadBtn.disabled = true;
    downloadBtn.classList.add("loading");

    try {
        // Convert base64 to blob or fetch from URL
        const base64Response = await fetch(booking.payment_proof);
        const blob = await base64Response.blob();

        // Create download link
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;

        // Set filename
        const filename = `bukti_pembayaran_${booking.customer_name
            .replace(/\s+/g, "_")
            .toLowerCase()}.jpg`;
        link.download = filename;

        // Trigger download
        link.style.display = "none";
        document.body.appendChild(link);
        link.click();

        // Clean up
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);

        // Show success notification
        setTimeout(() => {
            showNotification(
                `Bukti pembayaran berhasil diunduh: ${filename}`,
                "success"
            );

            // Restore button state
            downloadBtn.innerHTML = originalHTML;
            downloadBtn.disabled = false;
            downloadBtn.classList.remove("loading");
        }, 500);
    } catch (error) {
        console.error("Download error:", error);
        showNotification("Gagal mengunduh bukti pembayaran", "error");

        // Restore button state on error
        downloadBtn.innerHTML = originalHTML;
        downloadBtn.disabled = false;
        downloadBtn.classList.remove("loading");
    }
}

// Simpan history download ke localStorage
function saveDownloadHistory(booking, filename) {
    try {
        const downloadHistory = JSON.parse(
            localStorage.getItem("paymentProofDownloads") || "[]"
        );

        const downloadRecord = {
            id: booking.id,
            customer: booking.customer,
            filename: filename,
            downloadedAt: new Date().toISOString(),
            amount: booking.jumlah,
            service: booking.layanan,
        };

        downloadHistory.unshift(downloadRecord);

        // Keep only last 50 records
        if (downloadHistory.length > 50) {
            downloadHistory.pop();
        }

        localStorage.setItem(
            "paymentProofDownloads",
            JSON.stringify(downloadHistory)
        );
        console.log("💾 Download history saved:", downloadRecord);
    } catch (error) {
        console.error("Error saving download history:", error);
    }
}

// ===== PAYMENT PROOF CONTROLS =====
function initPaymentProofControls() {
    const downloadBtn = document.getElementById("downloadProofBtn");
    const proofImage = document.getElementById("proofImage");

    if (downloadBtn) {
        downloadBtn.addEventListener("click", function () {
            if (currentBooking && currentBooking.payment_proof) {
                downloadPaymentProof(currentBooking);
            } else {
                showNotification(
                    "Tidak ada bukti pembayaran untuk diunduh",
                    "warning"
                );
            }
        });
    }

    // Download button untuk payment detail modal
    const downloadPaymentBtn = document.getElementById(
        "downloadPaymentProofBtn"
    );
    if (downloadPaymentBtn) {
        downloadPaymentBtn.addEventListener("click", function () {
            if (currentBooking && currentBooking.payment_proof) {
                downloadPaymentProof(currentBooking);
            } else {
                showNotification(
                    "Tidak ada bukti pembayaran untuk diunduh",
                    "warning"
                );
            }
        });
    }

    // Gambar tidak bisa di-zoom
    if (proofImage) {
        proofImage.style.cursor = "default";
    }
}

// ===== SEARCH FUNCTIONALITY =====
function initSearch() {
    const searchInput = document.getElementById("searchBooking");
    if (!searchInput) return;

    searchInput.addEventListener("input", function () {
        const keyword = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll("#bookingTableBody tr");

        let hasVisibleRows = false;
        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(keyword);
            row.style.display = isVisible ? "" : "none";
            if (isVisible) hasVisibleRows = true;
        });

        let message = document.getElementById("noResultsMessage");
        if (!hasVisibleRows && keyword !== "") {
            if (!message) {
                message = document.createElement("tr");
                message.id = "noResultsMessage";
                message.innerHTML = `
                    <td colspan="8" class="empty-state">
                        <div class="icon"></div>
                        <p>Tidak ada booking yang sesuai</p>
                    </td>
                `;
                document
                    .getElementById("bookingTableBody")
                    .appendChild(message);
            }
        } else if (message) {
            message.remove();
        }
    });
}

// ===== TABLE ACTIONS =====
function initTableActions() {
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("cancel-btn")) {
            e.preventDefault();
            const bookingId = parseInt(e.target.getAttribute("data-id"));
            const booking = mockData.bookings.find((b) => b.id === bookingId);
            if (booking) {
                currentBooking = booking;
                showModal("cancelModal", booking);
            }
        }

        if (e.target.classList.contains("refund-btn")) {
            e.preventDefault();
            const bookingId = parseInt(e.target.getAttribute("data-id"));
            const booking = mockData.bookings.find((b) => b.id === bookingId);
            console.log(
                "🔍 REFUND BTN - Found booking from mockData:",
                booking
            );
            console.log(
                "🔍 REFUND BTN - Has refund_method?",
                booking ? booking.refund_method : "NO BOOKING"
            );
            console.log(
                "🔍 REFUND BTN - Has refund_account?",
                booking ? booking.refund_account_number : "NO BOOKING"
            );
            if (booking) {
                currentBooking = booking;
                showModal("refundModal", booking);
            }
        }

        if (e.target.classList.contains("confirm-btn")) {
            e.preventDefault();
            const bookingId = parseInt(e.target.getAttribute("data-id"));
            const booking = mockData.bookings.find((b) => b.id === bookingId);
            if (booking) {
                currentBooking = booking;
                showModal("paymentDetailModal", booking);
            }
        }
    });
}

// ===== COPY WALLET FUNCTIONALITY =====
function initCopyWallet() {
    // Copy wallet untuk payment detail modal
    const copyPaymentBtn = document.getElementById("copyPaymentWalletBtn");
    if (copyPaymentBtn) {
        copyPaymentBtn.addEventListener("click", function () {
            const walletNumber =
                document.getElementById("paymentWallet").textContent;
            if (walletNumber && walletNumber !== "-") {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard
                        .writeText(walletNumber)
                        .then(() => {
                            showNotification(
                                "Nomor e-wallet berhasil disalin",
                                "success"
                            );
                        })
                        .catch(() => {
                            const textArea = document.createElement("textarea");
                            textArea.value = walletNumber;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand("copy");
                            document.body.removeChild(textArea);
                            showNotification(
                                "Nomor e-wallet berhasil disalin",
                                "success"
                            );
                        });
                }
            }
        });
    }
}

function initCopyRefundAccount() {
    const copyBtn = document.getElementById("copyRefundAccountBtn");
    if (copyBtn) {
        copyBtn.addEventListener("click", function () {
            const accountNumber = document.getElementById(
                "refundAccountNumber"
            ).textContent;
            if (accountNumber && accountNumber !== "-") {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard
                        .writeText(accountNumber)
                        .then(() => {
                            showNotification(
                                "Nomor rekening refund berhasil disalin",
                                "success"
                            );
                        })
                        .catch(() => {
                            const textArea = document.createElement("textarea");
                            textArea.value = accountNumber;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand("copy");
                            document.body.removeChild(textArea);
                            showNotification(
                                "Nomor rekening refund berhasil disalin",
                                "success"
                            );
                        });
                }
            }
        });
    }
}

// ===== EVENT LISTENERS SETUP =====
function setupEventListeners() {
    // Logout Modal
    safeAddEventListener("logoutBtn", "click", (e) => {
        e.preventDefault();
        showModal("logoutModal");
    });

    safeAddEventListener("cancelLogout", "click", () => {
        hideModal("logoutModal");
    });

    safeAddEventListener("confirmLogout", "click", async () => {
        const confirmBtn = document.getElementById("confirmLogout");
        const originalText = confirmBtn.textContent;
        confirmBtn.textContent = "Logging out...";
        confirmBtn.disabled = true;

        try {
            // Clear localStorage first
            localStorage.removeItem("auth_token");
            localStorage.removeItem("user_data");
            console.log("✅ Cleared auth token from localStorage");

            // Get CSRF token
            await fetch("/sanctum/csrf-cookie", {
                credentials: "same-origin",
            });

            // Perform logout
            const response = await fetch("/logout", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.content,
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin",
            });

            if (response.ok) {
                // Redirect to login page
                window.location.href = "/login";
            } else {
                throw new Error("Logout failed");
            }
        } catch (error) {
            console.error("❌ Logout error:", error);
            showNotification("Gagal logout. Silakan coba lagi.", "error");
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        }
    });

    // Cancel Booking Modal
    safeAddEventListener("cancelCancel", "click", () => {
        hideModal("cancelModal");
    });

    safeAddEventListener("confirmCancel", "click", handleCancelBooking);

    // Confirm Refund Modal
    safeAddEventListener("cancelConfirmRefund", "click", () => {
        hideModal("confirmRefundModal");
    });

    safeAddEventListener("confirmRefund", "click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        handleConfirmRefund();
    });

    // Refund Modal
    safeAddEventListener("refundCancelBtn", "click", () => {
        hideModal("refundModal");
    });

    safeAddEventListener("refundConfirmBtn", "click", function (e) {
        handleRefundConfirm();
    });

    // Payment Detail Modal (Konfirmasi Booking)
    safeAddEventListener("paymentCancelBtn", "click", () => {
        hideModal("paymentDetailModal");
    });

    safeAddEventListener("paymentConfirmBtn", "click", async function (e) {
        if (!currentBooking) {
            showNotification("Error: Data booking tidak ditemukan", "error");
            return;
        }

        const booking = currentBooking;
        const confirmBtn = document.getElementById("paymentConfirmBtn");
        const originalText = confirmBtn.textContent;

        confirmBtn.textContent = "Memproses...";
        confirmBtn.disabled = true;

        try {
            const csrfToken =
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "";

            // No need to validate token - Laravel handles CSRF automatically for authenticated admin routes

            const response = await fetch(
                `/admin/kelolabooking/${booking.id}/confirm`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    credentials: "same-origin",
                }
            );

            const data = await response.json();

            console.log("📋 Confirm response:", {
                status: response.status,
                data,
            });

            if (!response.ok || !data.success) {
                throw new Error(data.message || "Gagal mengkonfirmasi booking");
            }

            // Close modal
            hideModal("paymentDetailModal");

            // Show success notification
            showNotification(
                `Booking dari ${booking.customer_name} berhasil dikonfirmasi`,
                "success"
            );

            // Reload booking data tanpa reload halaman
            setTimeout(() => {
                renderTableData();
                currentBooking = null;
                confirmBtn.textContent = originalText;
                confirmBtn.disabled = false;
            }, 500);
        } catch (error) {
            console.error("❌ Confirm error:", error);
            showNotification(
                error.message || "Gagal mengkonfirmasi booking",
                "error"
            );
            confirmBtn.textContent = originalText;
            confirmBtn.disabled = false;
        }
    });

    // Close modal on outside click
    const modals = [
        "logoutModal",
        "cancelModal",
        "confirmRefundModal",
        "refundModal",
        "paymentDetailModal",
    ];
    modals.forEach((modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    hideModal(modalId);
                }
            });
        }
    });

    // Escape key
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            hideAllModals();
        }
    });

    // Notification close
    const notificationClose = document.querySelector(".notification-close");
    if (notificationClose) {
        notificationClose.addEventListener("click", function () {
            document.getElementById("notification").classList.remove("show");
        });
    }
}

function safeAddEventListener(elementId, event, handler) {
    const element = document.getElementById(elementId);
    if (element) {
        element.addEventListener(event, handler);
    }
}

// ===== NOTIFICATION SYSTEM =====
function showNotification(message, type = "info") {
    const notification = document.getElementById("notification");
    const messageElement = document.getElementById("notificationMessage");

    if (!notification || !messageElement) return;

    messageElement.textContent = message;
    notification.className = `notification notification-${type}`;
    notification.classList.add("show");

    setTimeout(() => {
        notification.classList.remove("show");
    }, 3000);
}

// ===== UTILITY FUNCTIONS =====
function escapeHtml(text) {
    if (text == null) return "";
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

function formatDate(dateString) {
    const options = { day: "numeric", month: "short", year: "numeric" };
    return new Date(dateString).toLocaleDateString("id-ID", options);
}

function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    const dateOptions = { day: "numeric", month: "short", year: "numeric" };
    const timeOptions = { hour: "2-digit", minute: "2-digit", hour12: false };
    const dateStr = date.toLocaleDateString("id-ID", dateOptions);
    const timeStr = date.toLocaleTimeString("id-ID", timeOptions);
    return `${dateStr}, ${timeStr}`;
}

function getStatusText(status) {
    const statusMap = {
        cek_transaksi: "Cek Transaksi",
        menunggu: "Pending",
        proses: "Proses",
        selesai: "Selesai",
        dibatalkan: "Dibatalkan",
        pending: "Pending",
        completed: "Selesai",
        cancelled: "Dibatalkan",
    };
    return statusMap[status] || status;
}

// ===== INITIALIZATION =====
document.addEventListener("DOMContentLoaded", function () {
    console.log("📄 DOM Content Loaded - Starting Kelola Booking App");
    initializeApp();
    initializeFilterButtons();
});

// Initialize filter buttons
function initializeFilterButtons() {
    const filterButtons = document.querySelectorAll(".btn-filter");

    filterButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const filter = this.getAttribute("data-filter");

            // Update active state
            filterButtons.forEach((btn) => btn.classList.remove("active"));
            this.classList.add("active");

            // Update current filter
            currentFilter = filter;

            // Reset to page 1
            currentPage = 1;

            // Re-render table
            renderTableData();

            console.log(`🔍 Filter changed to: ${filter}`);
        });
    });

    console.log("✅ Filter buttons initialized");
}

// Fallback initialization
setTimeout(() => {
    const tableBody = document.getElementById("bookingTableBody");
    if (tableBody && tableBody.children.length === 0) {
        console.log("🔄 Fallback initialization triggered");
        initializeApp();
    }
}, 1000);

// ===== EXPORT UNTUK DEBUGGING =====
window.BookingManager = {
    mockData,
    currentBooking: () => currentBooking,
    showModal,
    hideModal,
    changePage, // Export untuk pagination
    refreshData: function () {
        renderTableData();
        showNotification("Data booking berhasil diperbarui", "success");
    },
    getDownloadHistory: function () {
        return JSON.parse(
            localStorage.getItem("paymentProofDownloads") || "[]"
        );
    },
    clearDownloadHistory: function () {
        localStorage.removeItem("paymentProofDownloads");
        showNotification("Riwayat download berhasil dihapus", "success");
    },
    testDownload: function (bookingId) {
        const booking = mockData.bookings.find((b) => b.id === bookingId);
        if (booking) {
            downloadPaymentProof(booking);
        } else {
            showNotification("Booking tidak ditemukan", "error");
        }
    },
    debugInfo: function () {
        console.log("🔍 Debug Info:", {
            bookingsCount: mockData.bookings.length,
            currentBooking: currentBooking,
            pendingBookings: mockData.bookings.filter(
                (b) => b.status === "pending"
            ).length,
            completedBookings: mockData.bookings.filter(
                (b) => b.status === "completed"
            ).length,
            cancelledBookings: mockData.bookings.filter(
                (b) => b.status === "cancelled"
            ).length,
        });
    },
};

console.log("🎯 Kelola Booking JS loaded successfully");
