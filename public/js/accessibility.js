// ========================================
// ACCESSIBILITY UTILITIES
// ========================================

class AccessibilityManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupKeyboardNavigation();
        this.setupAriaLive();
        this.setupFocusTrap();
        this.addSkipLinks();
        this.enhanceButtons();
        this.enhanceForms();
    }

    // Keyboard navigation enhancement
    setupKeyboardNavigation() {
        // ESC key to close modals
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                const openModal = document.querySelector(
                    '.modal.active, .modal[style*="display: block"]'
                );
                if (openModal) {
                    const closeBtn = openModal.querySelector(
                        '[data-dismiss="modal"], .close'
                    );
                    if (closeBtn) closeBtn.click();
                }
            }
        });

        // Tab navigation for custom dropdowns
        document.querySelectorAll('[role="menu"]').forEach((menu) => {
            menu.addEventListener("keydown", (e) => {
                const items = Array.from(
                    menu.querySelectorAll('[role="menuitem"]')
                );
                const currentIndex = items.indexOf(document.activeElement);

                if (e.key === "ArrowDown") {
                    e.preventDefault();
                    const nextIndex = (currentIndex + 1) % items.length;
                    items[nextIndex].focus();
                } else if (e.key === "ArrowUp") {
                    e.preventDefault();
                    const prevIndex =
                        (currentIndex - 1 + items.length) % items.length;
                    items[prevIndex].focus();
                }
            });
        });
    }

    // ARIA live regions for dynamic content
    setupAriaLive() {
        if (!document.getElementById("aria-live-region")) {
            const liveRegion = document.createElement("div");
            liveRegion.id = "aria-live-region";
            liveRegion.setAttribute("aria-live", "polite");
            liveRegion.setAttribute("aria-atomic", "true");
            liveRegion.className = "sr-only";
            document.body.appendChild(liveRegion);
        }
    }

    // Announce to screen readers
    announce(message, priority = "polite") {
        const liveRegion = document.getElementById("aria-live-region");
        if (liveRegion) {
            liveRegion.setAttribute("aria-live", priority);
            liveRegion.textContent = message;

            // Clear after announcement
            setTimeout(() => {
                liveRegion.textContent = "";
            }, 1000);
        }
    }

    // Focus trap for modals
    setupFocusTrap() {
        const modals = document.querySelectorAll('.modal, [role="dialog"]');

        modals.forEach((modal) => {
            modal.addEventListener("keydown", (e) => {
                if (e.key === "Tab") {
                    const focusableElements = modal.querySelectorAll(
                        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                    );

                    const firstElement = focusableElements[0];
                    const lastElement =
                        focusableElements[focusableElements.length - 1];

                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (
                        !e.shiftKey &&
                        document.activeElement === lastElement
                    ) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            });
        });
    }

    // Add skip navigation links
    addSkipLinks() {
        if (!document.querySelector(".skip-to-main")) {
            const skipLink = document.createElement("a");
            skipLink.href = "#main-content";
            skipLink.className = "skip-to-main";
            skipLink.textContent = "Skip to main content";
            document.body.insertBefore(skipLink, document.body.firstChild);

            // Ensure main content has ID
            const main = document.querySelector(
                "main, .main-content, .container"
            );
            if (main && !main.id) {
                main.id = "main-content";
            }
        }
    }

    // Enhance buttons with proper ARIA labels
    enhanceButtons() {
        document
            .querySelectorAll("button:not([aria-label])")
            .forEach((button) => {
                // If button has only icon, add label
                if (
                    button.querySelector("i, svg") &&
                    !button.textContent.trim()
                ) {
                    const icon = button.querySelector("i");
                    if (icon) {
                        const classList = Array.from(icon.classList);
                        const label = this.generateLabelFromIcon(classList);
                        if (label) {
                            button.setAttribute("aria-label", label);
                        }
                    }
                }
            });
    }

    // Generate label from icon class
    generateLabelFromIcon(classList) {
        const iconMap = {
            "fa-edit": "Edit",
            "fa-trash": "Delete",
            "fa-plus": "Add",
            "fa-search": "Search",
            "fa-filter": "Filter",
            "fa-download": "Download",
            "fa-upload": "Upload",
            "fa-print": "Print",
            "fa-eye": "View",
            "fa-eye-slash": "Hide",
            "fa-heart": "Like",
            "fa-star": "Favorite",
            "fa-share": "Share",
            "fa-bell": "Notifications",
            "fa-user": "User profile",
            "fa-cog": "Settings",
            "fa-sign-out": "Logout",
            "fa-close": "Close",
            "fa-check": "Confirm",
            "fa-times": "Cancel",
        };

        for (const [iconClass, label] of Object.entries(iconMap)) {
            if (classList.includes(iconClass)) {
                return label;
            }
        }
        return null;
    }

    // Enhance forms with proper labels and validation
    enhanceForms() {
        document.querySelectorAll("form").forEach((form) => {
            // Add novalidate and handle custom validation
            form.setAttribute("novalidate", "");

            // Ensure all inputs have labels
            form.querySelectorAll("input, select, textarea").forEach(
                (input) => {
                    if (!input.id) {
                        input.id =
                            "input-" + Math.random().toString(36).substr(2, 9);
                    }

                    const label = form.querySelector(
                        `label[for="${input.id}"]`
                    );
                    if (!label && !input.getAttribute("aria-label")) {
                        console.warn("Input without label:", input);
                    }

                    // Add aria-required for required fields
                    if (
                        input.required &&
                        !input.getAttribute("aria-required")
                    ) {
                        input.setAttribute("aria-required", "true");
                    }

                    // Add aria-invalid for validation
                    if (input.getAttribute("aria-invalid")) {
                        this.showError(input, input.validationMessage);
                    }
                }
            );

            // Handle form validation
            form.addEventListener("submit", (e) => {
                if (!form.checkValidity()) {
                    e.preventDefault();

                    const firstInvalid = form.querySelector(":invalid");
                    if (firstInvalid) {
                        firstInvalid.focus();
                        this.announce(
                            "Form has errors. Please check the highlighted fields.",
                            "assertive"
                        );
                    }
                }
            });
        });
    }

    // Show error message for input
    showError(input, message) {
        input.setAttribute("aria-invalid", "true");

        let errorDiv = input.parentElement.querySelector(".error-message");
        if (!errorDiv) {
            errorDiv = document.createElement("div");
            errorDiv.className = "error-message";
            errorDiv.setAttribute("role", "alert");
            input.parentElement.appendChild(errorDiv);
        }

        errorDiv.textContent = message;

        // Link error to input
        if (!errorDiv.id) {
            errorDiv.id = "error-" + input.id;
            input.setAttribute("aria-describedby", errorDiv.id);
        }
    }

    // Clear error message
    clearError(input) {
        input.removeAttribute("aria-invalid");
        const errorDiv = input.parentElement.querySelector(".error-message");
        if (errorDiv) {
            errorDiv.remove();
        }
    }
}

// ========================================
// AUTO INITIALIZE
// ========================================

let accessibilityManager;

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        accessibilityManager = new AccessibilityManager();
    });
} else {
    accessibilityManager = new AccessibilityManager();
}

// Export
if (typeof module !== "undefined" && module.exports) {
    module.exports = AccessibilityManager;
}
