import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';  // إضافة tailwindcss

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js', // ملف الجافا سكربت
                'resources/css/theme.css', // إضافة ملف CSS
            ],
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
        tailwindcss(),  // إضافة Tailwind CSS كـ plugin
    ],
});
