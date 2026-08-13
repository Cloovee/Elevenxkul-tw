import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // <-- Harus ada ini

export default defineConfig({
    plugins: [
        tailwindcss(), // <-- Harus dipanggil di sini
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});