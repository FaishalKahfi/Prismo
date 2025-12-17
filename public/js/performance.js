// ========================================
// IMAGE LAZY LOADING UTILITY
// ========================================

class LazyLoader {
    constructor(options = {}) {
        this.options = {
            root: options.root || null,
            rootMargin: options.rootMargin || "50px",
            threshold: options.threshold || 0.01,
            loadingClass: options.loadingClass || "lazy-loading",
            loadedClass: options.loadedClass || "lazy-loaded",
            errorClass: options.errorClass || "lazy-error",
        };

        this.observer = null;
        this.init();
    }

    init() {
        if ("IntersectionObserver" in window) {
            this.observer = new IntersectionObserver(
                this.handleIntersection.bind(this),
                {
                    root: this.options.root,
                    rootMargin: this.options.rootMargin,
                    threshold: this.options.threshold,
                }
            );

            this.observe();
        } else {
            // Fallback for older browsers
            this.loadAllImages();
        }
    }

    handleIntersection(entries) {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                this.loadImage(entry.target);
                this.observer.unobserve(entry.target);
            }
        });
    }

    loadImage(img) {
        const src = img.dataset.src;
        const srcset = img.dataset.srcset;

        if (!src && !srcset) return;

        img.classList.add(this.options.loadingClass);

        const tempImg = new Image();

        tempImg.onload = () => {
            if (src) img.src = src;
            if (srcset) img.srcset = srcset;

            img.classList.remove(this.options.loadingClass);
            img.classList.add(this.options.loadedClass);
            img.removeAttribute("data-src");
            img.removeAttribute("data-srcset");
        };

        tempImg.onerror = () => {
            img.classList.remove(this.options.loadingClass);
            img.classList.add(this.options.errorClass);
            console.error("Failed to load image:", src || srcset);
        };

        if (src) tempImg.src = src;
        if (srcset) tempImg.srcset = srcset;
    }

    observe() {
        const images = document.querySelectorAll(
            "img[data-src], img[data-srcset]"
        );
        images.forEach((img) => {
            this.observer.observe(img);
        });
    }

    loadAllImages() {
        const images = document.querySelectorAll(
            "img[data-src], img[data-srcset]"
        );
        images.forEach((img) => this.loadImage(img));
    }

    refresh() {
        if (this.observer) {
            this.observe();
        }
    }
}

// ========================================
// DEFER SCRIPT LOADING
// ========================================

function deferScripts() {
    const scripts = document.querySelectorAll("script[data-defer-src]");

    scripts.forEach((script) => {
        const newScript = document.createElement("script");
        newScript.src = script.dataset.deferSrc;

        if (script.dataset.async) {
            newScript.async = true;
        }

        document.body.appendChild(newScript);
        script.remove();
    });
}

// ========================================
// RESOURCE PREFETCHING
// ========================================

function prefetchResources(urls) {
    if (!urls || urls.length === 0) return;

    urls.forEach((url) => {
        const link = document.createElement("link");
        link.rel = "prefetch";
        link.href = url;
        document.head.appendChild(link);
    });
}

// ========================================
// DEBOUNCE UTILITY
// ========================================

function debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ========================================
// THROTTLE UTILITY
// ========================================

function throttle(func, limit = 100) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => (inThrottle = false), limit);
        }
    };
}

// ========================================
// AUTO INITIALIZE
// ========================================

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.lazyLoader = new LazyLoader();
        deferScripts();
    });
} else {
    window.lazyLoader = new LazyLoader();
    deferScripts();
}

// Export utilities
if (typeof module !== "undefined" && module.exports) {
    module.exports = {
        LazyLoader,
        deferScripts,
        prefetchResources,
        debounce,
        throttle,
    };
}
