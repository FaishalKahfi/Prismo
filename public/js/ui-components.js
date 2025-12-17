// ========================================
// TOAST NOTIFICATION SYSTEM
// ========================================

class Toast {
    constructor(options = {}) {
        this.container = this.getOrCreateContainer();
        this.duration = options.duration || 5000;
        this.position = options.position || "top-right";
    }

    getOrCreateContainer() {
        let container = document.querySelector(".toast-container");
        if (!container) {
            container = document.createElement("div");
            container.className = "toast-container";
            document.body.appendChild(container);
        }
        return container;
    }

    show(message, type = "info", title = "") {
        const toast = this.createToast(message, type, title);
        this.container.appendChild(toast);

        // Trigger animation
        setTimeout(() => toast.classList.add("show"), 10);

        // Auto dismiss
        setTimeout(() => this.dismiss(toast), this.duration);

        return toast;
    }

    createToast(message, type, title) {
        const toast = document.createElement("div");
        toast.className = `toast toast-${type}`;
        toast.setAttribute("role", "alert");
        toast.setAttribute("aria-live", "assertive");
        toast.setAttribute("aria-atomic", "true");

        const icon = this.getIcon(type);
        const defaultTitle = this.getDefaultTitle(type);

        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                ${
                    title || defaultTitle
                        ? `<div class="toast-title">${
                              title || defaultTitle
                          }</div>`
                        : ""
                }
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" aria-label="Close">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        `;

        toast.querySelector(".toast-close").addEventListener("click", () => {
            this.dismiss(toast);
        });

        return toast;
    }

    dismiss(toast) {
        toast.classList.add("removing");
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    getIcon(type) {
        const icons = {
            success:
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
            error: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
            warning:
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
            info: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
        };
        return icons[type] || icons.info;
    }

    getDefaultTitle(type) {
        const titles = {
            success: "Berhasil",
            error: "Error",
            warning: "Peringatan",
            info: "Informasi",
        };
        return titles[type] || "";
    }

    success(message, title = "") {
        return this.show(message, "success", title);
    }

    error(message, title = "") {
        return this.show(message, "error", title);
    }

    warning(message, title = "") {
        return this.show(message, "warning", title);
    }

    info(message, title = "") {
        return this.show(message, "info", title);
    }
}

// ========================================
// FORM VALIDATION
// ========================================

class FormValidator {
    constructor(form, options = {}) {
        this.form =
            typeof form === "string" ? document.querySelector(form) : form;
        this.options = {
            validateOnBlur: options.validateOnBlur !== false,
            validateOnInput: options.validateOnInput !== false,
            showSuccessState: options.showSuccessState !== false,
            ...options,
        };

        if (this.form) {
            this.init();
        }
    }

    init() {
        this.form.setAttribute("novalidate", "");

        if (this.options.validateOnBlur) {
            this.form
                .querySelectorAll("input, select, textarea")
                .forEach((field) => {
                    field.addEventListener("blur", () =>
                        this.validateField(field)
                    );
                });
        }

        if (this.options.validateOnInput) {
            this.form
                .querySelectorAll("input, select, textarea")
                .forEach((field) => {
                    field.addEventListener("input", () => {
                        if (field.classList.contains("is-invalid")) {
                            this.validateField(field);
                        }
                    });
                });
        }

        this.form.addEventListener("submit", (e) => this.handleSubmit(e));
    }

    validateField(field) {
        // Clear previous state
        this.clearFieldError(field);

        // Check validity
        if (!field.checkValidity()) {
            this.showFieldError(field, field.validationMessage);
            return false;
        }

        // Custom validators
        const customError = this.runCustomValidators(field);
        if (customError) {
            this.showFieldError(field, customError);
            return false;
        }

        // Show success state
        if (this.options.showSuccessState && field.value) {
            field.classList.add("is-valid");
        }

        return true;
    }

    runCustomValidators(field) {
        // Email confirmation
        if (field.name === "email_confirmation") {
            const email = this.form.querySelector('[name="email"]');
            if (email && field.value !== email.value) {
                return "Email tidak cocok";
            }
        }

        // Password confirmation
        if (field.name === "password_confirmation") {
            const password = this.form.querySelector('[name="password"]');
            if (password && field.value !== password.value) {
                return "Password tidak cocok";
            }
        }

        // Password strength
        if (field.name === "password" && field.value) {
            if (field.value.length < 8) {
                return "Password minimal 8 karakter";
            }
        }

        // Phone number (Indonesia)
        if (field.type === "tel" && field.value) {
            const phoneRegex = /^(\+62|62|0)[0-9]{9,12}$/;
            if (!phoneRegex.test(field.value.replace(/\s/g, ""))) {
                return "Nomor telepon tidak valid";
            }
        }

        return null;
    }

    showFieldError(field, message) {
        field.classList.remove("is-valid");
        field.classList.add("is-invalid");
        field.setAttribute("aria-invalid", "true");

        let errorDiv = field.parentElement.querySelector(".error-message");
        if (!errorDiv) {
            errorDiv = document.createElement("div");
            errorDiv.className = "error-message";
            errorDiv.setAttribute("role", "alert");
            errorDiv.id = "error-" + (field.id || field.name);
            field.setAttribute("aria-describedby", errorDiv.id);
            field.parentElement.appendChild(errorDiv);
        }

        errorDiv.textContent = message;
    }

    clearFieldError(field) {
        field.classList.remove("is-invalid", "is-valid");
        field.removeAttribute("aria-invalid");

        const errorDiv = field.parentElement.querySelector(".error-message");
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    handleSubmit(e) {
        let isValid = true;

        this.form
            .querySelectorAll("input, select, textarea")
            .forEach((field) => {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            });

        if (!isValid) {
            e.preventDefault();

            const firstInvalid = this.form.querySelector(".is-invalid");
            if (firstInvalid) {
                firstInvalid.focus();

                // Announce to screen readers
                if (window.accessibilityManager) {
                    window.accessibilityManager.announce(
                        "Form memiliki kesalahan. Silakan periksa field yang ditandai.",
                        "assertive"
                    );
                }
            }
        }

        return isValid;
    }

    reset() {
        this.form
            .querySelectorAll("input, select, textarea")
            .forEach((field) => {
                this.clearFieldError(field);
            });
    }
}

// ========================================
// GLOBAL INSTANCES
// ========================================

window.toast = new Toast();

// Auto-initialize forms with data-validate attribute
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("form[data-validate]").forEach((form) => {
        new FormValidator(form);
    });
});

// Export
if (typeof module !== "undefined" && module.exports) {
    module.exports = {
        Toast,
        FormValidator,
    };
}
