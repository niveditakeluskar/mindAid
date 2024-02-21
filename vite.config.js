import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  cacheDir: '.vite', // Enable cache directory for faster builds
  plugins: [
    laravel({
      input: [
        'resources/js/appInertia.js',
        'resources/laravel/js/app.js',
        // 'resources/laravel/js/bootstrap.js',
        // 'resources/laravel/js/form.js',
        // 'resources/laravel/js/utility.js',
        // 'resources/laravel/js/carePlanDevelopment.js',
        // 'resources/laravel/js/ccmcpdcommonJS.js',
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
>>>>>>> adc7c884f83cc5ea149eabc138f419b87b1daa4f
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
      input: '/resources/js/appInertia.js',
    },
  },
});
