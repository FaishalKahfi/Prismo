// ========================================
// WEB VITALS MONITORING
// ========================================

class WebVitalsMonitor {
    constructor() {
        this.metrics = {};
        this.init();
    }

    init() {
        // Measure LCP (Largest Contentful Paint)
        this.measureLCP();

        // Measure FID (First Input Delay)
        this.measureFID();

        // Measure CLS (Cumulative Layout Shift)
        this.measureCLS();

        // Measure TTFB (Time to First Byte)
        this.measureTTFB();

        // Send metrics after page load
        window.addEventListener("load", () => {
            setTimeout(() => this.sendMetrics(), 3000);
        });
    }

    measureLCP() {
        if ("PerformanceObserver" in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];

                this.metrics.lcp = lastEntry.renderTime || lastEntry.loadTime;
                this.logMetric("LCP", this.metrics.lcp);
            });

            observer.observe({ entryTypes: ["largest-contentful-paint"] });
        }
    }

    measureFID() {
        if ("PerformanceObserver" in window) {
            const observer = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach((entry) => {
                    if (entry.name === "first-input") {
                        this.metrics.fid =
                            entry.processingStart - entry.startTime;
                        this.logMetric("FID", this.metrics.fid);
                    }
                });
            });

            observer.observe({ entryTypes: ["first-input"] });
        }
    }

    measureCLS() {
        if ("PerformanceObserver" in window) {
            let clsValue = 0;

            const observer = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                }

                this.metrics.cls = clsValue;
                this.logMetric("CLS", this.metrics.cls);
            });

            observer.observe({ entryTypes: ["layout-shift"] });
        }
    }

    measureTTFB() {
        if ("performance" in window && "timing" in performance) {
            const perfData = performance.timing;
            this.metrics.ttfb = perfData.responseStart - perfData.requestStart;
            this.logMetric("TTFB", this.metrics.ttfb);
        }
    }

    logMetric(name, value) {
        const status = this.getMetricStatus(name, value);
        const color =
            status === "good"
                ? "green"
                : status === "needs-improvement"
                ? "orange"
                : "red";

        console.log(
            `%c${name}: ${Math.round(value)}ms (${status})`,
            `color: ${color}; font-weight: bold;`
        );
    }

    getMetricStatus(name, value) {
        const thresholds = {
            LCP: { good: 2500, poor: 4000 },
            FID: { good: 100, poor: 300 },
            CLS: { good: 0.1, poor: 0.25 },
            TTFB: { good: 600, poor: 1500 },
        };

        const threshold = thresholds[name];
        if (!threshold) return "unknown";

        if (value <= threshold.good) return "good";
        if (value <= threshold.poor) return "needs-improvement";
        return "poor";
    }

    sendMetrics() {
        // Log summary
        console.group("📊 Core Web Vitals Summary");
        console.table(this.metrics);
        console.groupEnd();

        // Optionally send to analytics
        if (typeof gtag !== "undefined") {
            Object.entries(this.metrics).forEach(([metric, value]) => {
                gtag("event", metric, {
                    value: Math.round(value),
                    metric_id: metric,
                    metric_value: value,
                    metric_delta: 0,
                });
            });
        }
    }
}

// ========================================
// RESOURCE TIMING MONITOR
// ========================================

class ResourceTimingMonitor {
    constructor() {
        this.init();
    }

    init() {
        window.addEventListener("load", () => {
            this.analyzeResources();
        });
    }

    analyzeResources() {
        if (!("performance" in window)) return;

        const resources = performance.getEntriesByType("resource");

        const summary = {
            scripts: [],
            styles: [],
            images: [],
            fonts: [],
            other: [],
        };

        resources.forEach((resource) => {
            const type = this.getResourceType(resource.name);
            const data = {
                name: this.getResourceName(resource.name),
                duration: Math.round(resource.duration),
                size: resource.transferSize || 0,
                cached: resource.transferSize === 0,
            };

            summary[type].push(data);
        });

        // Log slow resources
        this.logSlowResources(summary);

        // Log large resources
        this.logLargeResources(summary);
    }

    getResourceType(url) {
        if (url.match(/\.js$/)) return "scripts";
        if (url.match(/\.css$/)) return "styles";
        if (url.match(/\.(jpg|jpeg|png|gif|webp|svg)$/)) return "images";
        if (url.match(/\.(woff|woff2|ttf|otf)$/)) return "fonts";
        return "other";
    }

    getResourceName(url) {
        return url.split("/").pop() || url;
    }

    logSlowResources(summary) {
        const slowThreshold = 1000; // 1 second
        const slowResources = [];

        Object.entries(summary).forEach(([type, resources]) => {
            resources.forEach((resource) => {
                if (resource.duration > slowThreshold) {
                    slowResources.push({ ...resource, type });
                }
            });
        });

        if (slowResources.length > 0) {
            console.group("⚠️ Slow Resources (> 1s)");
            console.table(slowResources);
            console.groupEnd();
        }
    }

    logLargeResources(summary) {
        const sizeThreshold = 100000; // 100KB
        const largeResources = [];

        Object.entries(summary).forEach(([type, resources]) => {
            resources.forEach((resource) => {
                if (resource.size > sizeThreshold) {
                    largeResources.push({
                        ...resource,
                        type,
                        sizeKB: Math.round(resource.size / 1024),
                    });
                }
            });
        });

        if (largeResources.length > 0) {
            console.group("⚠️ Large Resources (> 100KB)");
            console.table(largeResources);
            console.groupEnd();
        }
    }
}

// ========================================
// AUTO INITIALIZE (ONLY IN DEVELOPMENT)
// ========================================

if (
    window.location.hostname === "localhost" ||
    window.location.hostname === "127.0.0.1"
) {
    window.webVitalsMonitor = new WebVitalsMonitor();
    window.resourceTimingMonitor = new ResourceTimingMonitor();

    console.log(
        "%c🚀 Performance monitoring enabled",
        "color: #2563eb; font-size: 14px; font-weight: bold;"
    );
}

// Export
if (typeof module !== "undefined" && module.exports) {
    module.exports = {
        WebVitalsMonitor,
        ResourceTimingMonitor,
    };
}
