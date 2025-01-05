import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import { resolve, dirname } from 'node:path'
import { fileURLToPath } from 'url'
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite'

const path = require('path')

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/js/login.js',
                'resources/js/app.js',
                'resources/js/share.js',
            ],
            refresh: true,
        }),




        // VueI18nPlugin({
        //     /* options */
        //     // locale messages resource pre-compile option
        //     include: resolve(dirname(fileURLToPath(import.meta.url)), './js/locales/**'),
        // }),
    ],

});
