// kelola-admin.js (versi terhubung ke database)

// Use real data from server if available, otherwise use mock data
let admins = window.adminsData || [
    { id: 1, name: "Bagas Agustian", email: "budi@steamwash.com" },
    { id: 2, name: "Naufal apabae", email: "budi@steamwash.com" },
];

// Get CSRF token
const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.content || "";

// elemen DOM (cek dulu apakah ada)
const adminList = document.getElementById("adminList");
const btnAddAdmin = document.getElementById("btnAddAdmin");
const modalAdd = document.getElementById("modalAdd");
const modalDelete = document.getElementById("modalDelete");

const inputName = document.getElementById("inputName");
const inputEmail = document.getElementById("inputEmail");
const inputPassword = document.getElementById("inputPassword");
const inputRole = document.getElementById("inputRole");
// togglePassword mungkin sudah dihapus dari HTML — hanya tambahkan listener kalau ada
const togglePassword = document.getElementById("togglePassword");

const btnCancelAdd = document.getElementById("btnCancelAdd");
const btnSaveAdmin = document.getElementById("btnSaveAdmin");

const confirmDeleteYes = document.getElementById("confirmDeleteYes");
const confirmDeleteNo = document.getElementById("confirmDeleteNo");

let deleteTargetId = null;

// safe text
function escapeHtml(text) {
    if (text == null) return "";
    return String(text).replace(/[&<>"']/g, function (m) {
        return {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#39;",
        }[m];
    });
}

// Show notification
function showNotification(message, type = "success") {
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    background: ${type === "success" ? "#10b981" : "#ef4444"};
    color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 10000;
    animation: slideIn 0.3s ease-out;
  `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.style.animation = "slideOut 0.3s ease-out";
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// render tabel
function renderAdmins() {
    if (!adminList) return;
    adminList.innerHTML = "";
    admins.forEach((a, idx) => {
        const tr = document.createElement("tr");
        tr.dataset.id = a.id;
        tr.innerHTML = `
      <td class="col-no">${idx + 1}</td>
      <td>${escapeHtml(a.name)}</td>
      <td><span class="owner-email">${escapeHtml(a.email)}</span></td>
      <td style="text-align:center">
        <button class="action-btn small" data-action="delete" data-id="${
            a.id
        }">Hapus</button>
      </td>
    `;
        adminList.appendChild(tr);
    });
}

// open/close helpers (cek keberadaan dulu)
function openModal(el) {
    if (el) el.classList.add("show");
}
function closeModal(el) {
    if (el) el.classList.remove("show");
}

// Inisialisasi: tombol Tambah Admin
if (btnAddAdmin && modalAdd) {
    btnAddAdmin.addEventListener("click", () => {
        // reset form (jika elemen ada)
        if (inputName) inputName.value = "";
        if (inputEmail) inputEmail.value = "";
        if (inputPassword) inputPassword.value = "";
        if (inputRole) {
            inputRole.value = "Admin"; // selalu Admin dan readonly
            inputRole.setAttribute("readonly", "true");
        }
        openModal(modalAdd);
    });
}

// toggle password (jika tombol ada)
if (togglePassword && inputPassword) {
    togglePassword.addEventListener("click", () => {
        inputPassword.type =
            inputPassword.type === "password" ? "text" : "password";
    });
}

// Cancel tambah
if (btnCancelAdd && modalAdd) {
    btnCancelAdd.addEventListener("click", () => {
        closeModal(modalAdd);
    });
}

// Simpan admin baru - CONNECTED TO DATABASE
if (btnSaveAdmin && modalAdd) {
    btnSaveAdmin.addEventListener("click", async () => {
        const name = inputName ? inputName.value.trim() : "";
        const email = inputEmail ? inputEmail.value.trim() : "";
        const password = inputPassword ? inputPassword.value : "";

        if (!name || !email || !password) {
            showNotification("Nama, email, dan password harus diisi", "error");
            return;
        }

        // Disable button during request
        btnSaveAdmin.disabled = true;
        btnSaveAdmin.textContent = "Menyimpan...";

        try {
            const response = await fetch("/admin/kelolaadmin/store", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({ name, email, password }),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Add new admin to local array
                admins.push(result.data);
                renderAdmins();
                closeModal(modalAdd);
                showNotification(
                    result.message || "Admin berhasil ditambahkan",
                    "success"
                );
            } else {
                const errorMsg = result.errors
                    ? Object.values(result.errors).flat().join(", ")
                    : result.message || "Gagal menambahkan admin";
                showNotification(errorMsg, "error");
            }
        } catch (error) {
            console.error("Error adding admin:", error);
            showNotification(
                "Terjadi kesalahan saat menambahkan admin",
                "error"
            );
        } finally {
            btnSaveAdmin.disabled = false;
            btnSaveAdmin.textContent = "Selesai";
        }
    });
}

// Event delegation: tombol Hapus di tabel
if (adminList && modalDelete) {
    adminList.addEventListener("click", (e) => {
        const btn = e.target.closest('button[data-action="delete"]');
        if (!btn) return;
        const id = Number(btn.dataset.id);
        deleteTargetId = id;
        openModal(modalDelete);
    });
}

// Konfirmasi hapus - CONNECTED TO DATABASE
if (confirmDeleteYes && modalDelete) {
    confirmDeleteYes.addEventListener("click", async () => {
        if (deleteTargetId == null) return;

        // Disable button during request
        confirmDeleteYes.disabled = true;
        confirmDeleteYes.textContent = "Menghapus...";

        try {
            const response = await fetch(
                `/admin/kelolaadmin/${deleteTargetId}`,
                {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                }
            );

            const result = await response.json();

            if (response.ok && result.success) {
                // Remove from local array
                admins = admins.filter((a) => a.id !== deleteTargetId);
                renderAdmins();
                closeModal(modalDelete);
                showNotification(
                    result.message || "Admin berhasil dihapus",
                    "success"
                );
            } else {
                showNotification(
                    result.message || "Gagal menghapus admin",
                    "error"
                );
            }
        } catch (error) {
            console.error("Error deleting admin:", error);
            showNotification("Terjadi kesalahan saat menghapus admin", "error");
        } finally {
            deleteTargetId = null;
            confirmDeleteYes.disabled = false;
            confirmDeleteYes.textContent = "Ya, hapus!";
        }
    });
}

// Batal hapus
if (confirmDeleteNo && modalDelete) {
    confirmDeleteNo.addEventListener("click", () => {
        deleteTargetId = null;
        closeModal(modalDelete);
    });
}

// Close modal ketika klik luar
document.querySelectorAll(".modal-overlay").forEach((m) => {
    m.addEventListener("click", (e) => {
        if (e.target === m) closeModal(m);
    });
});

// ESC tutup semua modal
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        document
            .querySelectorAll(".modal-overlay.show")
            .forEach((m) => closeModal(m));
    }
});

// Add CSS animation styles
const style = document.createElement("style");
style.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  @keyframes slideOut {
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

// initial render
renderAdmins();
