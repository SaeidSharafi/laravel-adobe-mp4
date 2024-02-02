import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import viteCompression from 'vite-plugin-compression';

export default defineConfig({
    resolve: {
        mainFields: [
            'browser',
            'module',
            'main',
            'jsnext:main',
            'jsnext'
        ]
    },
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        viteCompression({
            algorithm : 'brotliCompress',
        }),
    ] ,optimizeDeps: {
        include: ['lodash-es']
    },server: {
        hmr: {
            host: 'localhost',
        },
    },
});
