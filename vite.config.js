import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/bulma.scss',
                'resources/sass/bulma.scss',
                'resources/js/app.js',
                'resources/js/login.js',
                'resources/js/share.js',
            ],
            refresh: true,
        }),
    ],
});
