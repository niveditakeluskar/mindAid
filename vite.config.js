import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/appInertia.js',
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
            optimizeDeps: {
    include: ['vuex'], // Ensure Vuex is included in the optimized dependencies
  },
    resolve: {
        alias: {
            '@': '/resources/js',
            '@@': '/public/',
            '@@@': '/resources/laravel/js',
        },
    },
      build: {
        outDir: 'public/build', // Output directory for Vite-built assets
    assetsDir: 'assets', // Directory within outDir for assets
    rollupOptions: {
      //external: ['assets/js/laravel/ccmcpdcommonJS'], // Add the module to externalize
      // Other rollup options...
    },
  },
});
