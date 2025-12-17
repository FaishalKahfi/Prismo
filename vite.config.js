import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],

    build: {
        minify: "terser",
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                pure_funcs: ["console.log", "console.info"],
            },
        },
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ["laravel-vite-plugin"],
                },
            },
        },
        cssMinify: true,
        chunkSizeWarningLimit: 1000,
        assetsInlineLimit: 4096,
    },

    optimizeDeps: {
        include: ["laravel-vite-plugin"],
    },

    server: {
        hmr: {
            host: "localhost",
        },
    },
});
