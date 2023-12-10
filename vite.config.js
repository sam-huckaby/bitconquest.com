import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

/*
    server: {
        host: 'localhost',
    },
*/

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
});
