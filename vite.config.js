import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/public.css',
                'resources/js/public/app.js',
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'app/Http/Controllers/**/*.php',
                'app/Services/**/*.php',
            ],
        }),
    ],
    build: {
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
