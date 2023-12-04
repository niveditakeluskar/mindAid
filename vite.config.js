import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/appInertia.js',
                'resources/laravel/js/iapp.js',
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
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@@': '/public/',
        },
    },
      build: {
    rollupOptions: {
      external: ['assets/js/laravel/ccmcpdcommonJS'], // Add the module to externalize
      // Other rollup options...
    },
  },
});
