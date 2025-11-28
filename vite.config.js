import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import {fileURLToPath} from "node:url";

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            "@": fileURLToPath(new URL("./resources", import.meta.url)),
            "@pages": fileURLToPath(new URL("./resources/js/Pages", import.meta.url)),
            "@custom-helpers": fileURLToPath(new URL("./resources/js/helpers/custom", import.meta.url)),
            "@auth-layout": fileURLToPath(new URL("./resources/js/Layouts/Auth", import.meta.url)),
            "@dashboard-layout": fileURLToPath(new URL("./resources/js/Layouts/Dashboard", import.meta.url)),
            "@components": fileURLToPath(new URL("./resources/js/components", import.meta.url)),
        },
    },
});
