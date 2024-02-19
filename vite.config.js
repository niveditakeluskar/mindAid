import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  cacheDir: '.vite', // Enable cache directory for faster builds
  plugins: [
    laravel({
      input: [
        'resources/js/appInertia.js',
<<<<<<< HEAD
        'resources/laravel/js/app.js',
        // 'resources/laravel/js/bootstrap.js',
        // 'resources/laravel/js/form.js',
        // 'resources/laravel/js/utility.js',
        // 'resources/laravel/js/carePlanDevelopment.js',
        // 'resources/laravel/js/ccmcpdcommonJS.js',
=======
>>>>>>> 02fdba72ec8d2bd596a2fc970f046bca1bc0ac33
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
<<<<<<< HEAD
    include: ['vuex'], // Ensure Vuex is included in the optimized dependencies
=======
>>>>>>> 02fdba72ec8d2bd596a2fc970f046bca1bc0ac33
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
<<<<<<< HEAD
    rollupOptions: {
      input: '/resources/js/appInertia.js',
    },
=======
    rollupOptions: {},
>>>>>>> 02fdba72ec8d2bd596a2fc970f046bca1bc0ac33
  },
});