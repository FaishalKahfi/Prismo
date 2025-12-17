/**
 * Prevent Back Button Navigation
 *
 * This script prevents users from navigating back to login/register pages
 * after they have successfully logged in to their dashboard.
 *
 * Also handles session errors and redirects appropriately.
 *
 * Usage: Include this script in dashboard pages with meta tag:
 * <meta name="authenticated" content="true">
 */

(function () {
    "use strict";

    // Check if user is authenticated
    const isAuthenticated =
        document.querySelector('meta[name="authenticated"]')?.content ===
        "true";

    // If on login/register/landing page and authenticated, redirect immediately
    const guestPages = ["/", "/login", "/register"];
    const currentPath = window.location.pathname;

    if (!isAuthenticated && guestPages.includes(currentPath)) {
        // Check if there's a valid session
        fetch("/api/auth/check", {
            method: "GET",
            credentials: "same-origin",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => {
                // If not ok (including 401), user is not authenticated - this is expected
                if (!response.ok) {
                    return Promise.resolve({ authenticated: false });
                }
                return response.json();
            })
            .then((data) => {
                // User is authenticated, redirect to dashboard
                if (data.authenticated && data.role) {
                    if (data.role === "admin") {
                        window.location.replace("/admin/dashboard");
                    } else if (data.role === "mitra") {
                        window.location.replace("/dashboard-mitra");
                    } else if (data.role === "customer") {
                        window.location.replace("/customer/dashboard");
                    }
                }
            })
            .catch(() => {
                // Not authenticated, do nothing
            });
    }

    if (!isAuthenticated) {
        return;
    }

    // For authenticated users: Lock the page
    (function lockPage() {
        // Store the current page URL
        const currentUrl = window.location.href;

        // Create a dummy state
        const state = { page: "locked" };

        // Replace the entire history with current page
        window.history.replaceState(state, "", currentUrl);
        window.history.pushState(state, "", currentUrl);

        // Handle popstate event (back/forward button press)
        window.addEventListener(
            "popstate",
            function (event) {
                // Always stay on current page
                window.history.pushState(state, "", currentUrl);
            },
            false
        );

        // Backup: monitor hash changes
        window.addEventListener(
            "hashchange",
            function (event) {
                event.preventDefault();
                window.location.hash = "";
            },
            false
        );

        // Disable back button keyboard shortcut (Alt + Left Arrow, Backspace)
        document.addEventListener("keydown", function (event) {
            // Backspace key (except in input fields)
            if (event.keyCode === 8) {
                const target = event.target;
                const isInput =
                    target.tagName === "INPUT" ||
                    target.tagName === "TEXTAREA" ||
                    target.isContentEditable;

                if (!isInput) {
                    event.preventDefault();
                    return false;
                }
            }

            // Alt + Left Arrow (back button shortcut)
            if (event.altKey && event.keyCode === 37) {
                event.preventDefault();
                return false;
            }
        });
    })();

    // Handle session errors globally
    window.addEventListener("error", function (event) {
        // Check for session-related errors
        if (
            event.message &&
            (event.message.includes("session") ||
                event.message.includes("CSRF") ||
                event.message.includes("unauthorized") ||
                event.message.includes("419") ||
                event.message.includes("401"))
        ) {
            console.error("Session error detected:", event.message);

            // Redirect to login
            setTimeout(function () {
                window.location.href = "/login?session_expired=1";
            }, 1000);
        }
    });

    // Handle AJAX/Fetch errors for session expiry
    if (window.fetch) {
        const originalFetch = window.fetch;
        window.fetch = function () {
            return originalFetch
                .apply(this, arguments)
                .then(function (response) {
                    // Check for authentication errors (401, 419 CSRF token mismatch)
                    if (response.status === 401 || response.status === 419) {
                        console.error("Authentication error:", response.status);

                        // Clear local storage/session storage if needed
                        try {
                            sessionStorage.clear();
                        } catch (e) {
                            console.error("Error clearing session storage:", e);
                        }

                        // Redirect to login
                        window.location.href = "/login?session_expired=1";
                        return Promise.reject(
                            new Error("Authentication error")
                        );
                    }
                    return response;
                })
                .catch(function (error) {
                    console.error("Fetch error:", error);
                    throw error;
                });
        };
    }
})();
