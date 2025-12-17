// simple interactions for the approval form

// modal elements
const modalSuccess = document.getElementById("modalSuccess");
const modalReject = document.getElementById("modalReject");
const modalViewer = document.getElementById("modalViewer");
const okSuccess = document.getElementById("okSuccess");
const btnApprove = document.getElementById("btnApprove");
const btnReject = document.getElementById("btnReject");
const confirmReject = document.getElementById("confirmReject");
const cancelReject = document.getElementById("cancelReject");
const successText = document.getElementById("successText");
const viewerClose = document.getElementById("viewerClose");
const viewerTitle = document.getElementById("viewerTitle");
const pdfViewer = document.getElementById("pdfViewer");
const imageViewer = document.getElementById("imageViewer");
const pdfFrame = document.getElementById("pdfFrame");
const imagePreview = document.getElementById("imagePreview");

// Current file being viewed
let currentFile = null;

// Initialize event listeners
function init() {
    // Approval flow
    if (btnApprove) {
        btnApprove.addEventListener("click", handleApprove);
    }

    if (btnReject) {
        btnReject.addEventListener("click", () => showModal(modalReject));
    }

    if (confirmReject) {
        confirmReject.addEventListener("click", handleReject);
    }

    if (cancelReject) {
        cancelReject.addEventListener("click", () => {
            closeModal(modalReject);
            // Reset textarea and error
            document.getElementById("rejectReason").value = "";
            document.getElementById("rejectReasonError").style.display = "none";
        });
    }

    // Reset error when typing
    const rejectReasonTextarea = document.getElementById("rejectReason");
    if (rejectReasonTextarea) {
        rejectReasonTextarea.addEventListener("input", () => {
            if (rejectReasonTextarea.value.trim().length >= 10) {
                document.getElementById("rejectReasonError").style.display =
                    "none";
            }
        });
    }

    if (okSuccess) {
        okSuccess.addEventListener("click", () => {
            closeModal(modalSuccess);
            // Redirect to kelolamitra list
            window.location.href = "/admin/kelolamitra/kelolamitra";
        });
    }

    if (viewerClose) {
        viewerClose.addEventListener("click", () => closeModal(modalViewer));
    }

    // File viewer handlers
    setupFileViewers();

    // Modal close handlers
    setupModalCloseHandlers();
}

// Setup file viewer buttons
function setupFileViewers() {
    // Document viewers
    document
        .querySelectorAll(".icon-eye[data-type][data-src]")
        .forEach((button) => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                const type = button.getAttribute("data-type");
                const src = button.getAttribute("data-src");

                console.log("View button clicked:", { type, src });

                // Get title from different possible containers
                let title = "Preview";
                if (button.closest(".doc-item")) {
                    title = button
                        .closest(".doc-item")
                        .querySelector(".doc-title").textContent;
                } else if (button.closest("li")) {
                    const fileLeft = button
                        .closest("li")
                        .querySelector(".file-left");
                    if (fileLeft) {
                        title = fileLeft.textContent;
                    }
                }

                console.log("Opening viewer with title:", title);
                openViewer(type, src, title);
            });
        });

    console.log(
        "File viewers initialized. Total buttons:",
        document.querySelectorAll(".icon-eye[data-type][data-src]").length
    );
}

// Open file viewer
function openViewer(type, fileUrl, title) {
    currentFile = { type, url: fileUrl, title };
    viewerTitle.textContent = title || "Preview Dokumen";

    // Hide all viewers first
    pdfViewer.style.display = "none";
    imageViewer.style.display = "none";

    // Show appropriate viewer
    if (type === "pdf") {
        console.log("Loading PDF:", fileUrl);

        // Clear previous content
        pdfFrame.src = "";

        // Add loading message
        const loadingDiv = document.createElement("div");
        loadingDiv.style.cssText =
            "text-align: center; padding: 40px; color: #666;";
        loadingDiv.innerHTML = "<p>⏳ Memuat dokumen PDF...</p>";

        // Show viewer
        pdfViewer.style.display = "flex";

        // Load PDF with slight delay to show loading state
        setTimeout(() => {
            // Try direct iframe first (works for most modern browsers with PDF plugin)
            pdfFrame.src =
                fileUrl + "#toolbar=1&navpanes=1&scrollbar=1&view=FitH";

            // Add load event handler
            pdfFrame.onload = function () {
                console.log("PDF loaded successfully");
            };

            // Add error handler
            pdfFrame.onerror = function () {
                console.error("Failed to load PDF directly");
                // Show error message with download option
                const errorMsg = document.createElement("div");
                errorMsg.style.cssText =
                    "text-align: center; padding: 40px; color: #999;";
                errorMsg.innerHTML = `
                    <p style="margin-bottom: 16px;">⚠️ Tidak dapat menampilkan preview PDF</p>
                    <p style="font-size: 14px; margin-bottom: 20px;">Browser Anda mungkin tidak mendukung preview PDF.</p>
                    <button onclick="downloadFile()" style="padding: 10px 20px; background: #2ea0ff; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        Download PDF
                    </button>
                `;
            };
        }, 100);
    } else if (type === "image") {
        // Add loading state
        imagePreview.style.opacity = "0.3";
        imagePreview.src = "";

        // Load the image
        const img = new Image();
        img.onload = function () {
            imagePreview.src = fileUrl;
            imagePreview.style.opacity = "1";
            console.log("Image loaded successfully:", fileUrl);
        };
        img.onerror = function () {
            console.error("Failed to load image:", fileUrl);
            imagePreview.alt = "❌ Gagal memuat gambar. URL: " + fileUrl;
            imagePreview.style.opacity = "1";
            imagePreview.style.minHeight = "200px";
            imagePreview.style.display = "flex";
            imagePreview.style.alignItems = "center";
            imagePreview.style.justifyContent = "center";
            imagePreview.style.background = "#f5f5f5";
            imagePreview.style.color = "#999";
            imagePreview.style.fontSize = "14px";
            imagePreview.style.padding = "40px";
            imagePreview.style.textAlign = "center";
        };
        img.src = fileUrl;

        imageViewer.style.display = "flex";
    }

    showModal(modalViewer);
}

// Download current file
function downloadFile() {
    if (!currentFile || !currentFile.url) return;

    const link = document.createElement("a");
    link.href = currentFile.url;
    link.download = currentFile.title || "download";
    link.target = "_blank";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Handle approve action
function handleApprove(e) {
    e.preventDefault();
    const mitraId = btnApprove.getAttribute("data-id");

    // Disable button immediately
    btnApprove.disabled = true;
    btnApprove.textContent = "Memproses...";

    // Send AJAX request
    fetch(`/admin/kelolamitra/${mitraId}/approve`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN":
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || "",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                successText.textContent =
                    data.message || "Mitra telah disetujui.";
                showModal(modalSuccess);
                disableButtons();
            } else {
                alert("Terjadi kesalahan saat menyetujui mitra");
                btnApprove.disabled = false;
                btnApprove.textContent = "Setujui";
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat menyetujui mitra");
            btnApprove.disabled = false;
            btnApprove.textContent = "Setujui";
        });
}

// Handle reject action
function handleReject() {
    const mitraId = btnReject.getAttribute("data-id");
    const rejectReason = document.getElementById("rejectReason").value.trim();
    const rejectReasonError = document.getElementById("rejectReasonError");

    // Validate reason
    if (rejectReason.length < 10) {
        rejectReasonError.style.display = "block";
        return;
    }

    rejectReasonError.style.display = "none";

    // Disable button immediately
    confirmReject.disabled = true;
    confirmReject.textContent = "Memproses...";

    // Send AJAX request
    fetch(`/admin/kelolamitra/${mitraId}/reject`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN":
                document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content") || "",
        },
        body: JSON.stringify({
            reject_reason: rejectReason,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                successText.textContent =
                    data.message ||
                    "Mitra telah ditolak dan email notifikasi telah dikirim.";
                showModal(modalSuccess);
                disableButtons();
                closeModal(modalReject);
            } else {
                alert("Terjadi kesalahan saat menolak mitra");
                confirmReject.disabled = false;
                confirmReject.textContent = "Ya, Tolak";
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat menolak mitra");
            confirmReject.disabled = false;
            confirmReject.textContent = "Ya, Tolak";
        });
}

// Disable action buttons after decision
function disableButtons() {
    btnApprove.disabled = true;
    btnReject.disabled = true;
    btnApprove.style.opacity = "0.6";
    btnReject.style.opacity = "0.6";
    btnApprove.style.cursor = "not-allowed";
    btnReject.style.cursor = "not-allowed";
}

// Modal close handlers
function setupModalCloseHandlers() {
    // Close modal when clicking overlay
    document.querySelectorAll(".modal-overlay").forEach((overlay) => {
        overlay.addEventListener("click", (e) => {
            if (e.target === overlay) closeModal(overlay);
        });
    });

    // Close with ESC key
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeModal(modalSuccess);
            closeModal(modalReject);
            closeModal(modalViewer);
        }
    });
}

// Helper functions
function showModal(el) {
    if (!el) return;
    el.classList.add("show");
    document.body.style.overflow = "hidden";
}

function closeModal(el) {
    if (!el) return;
    el.classList.remove("show");
    document.body.style.overflow = "";

    // Clear PDF frame when closing viewer to stop loading
    if (el === modalViewer) {
        pdfFrame.src = "";
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", init);
