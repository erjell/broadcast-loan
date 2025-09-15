import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/Tables.js",
                "resources/js/tooltip-kondisi.js",
                "resources/images/logo.webp",
            ],
            refresh: true,
        }),
    ],
});
