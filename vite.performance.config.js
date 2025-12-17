export default {
    preload: true,
    prefetchLinks: true,

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
};
