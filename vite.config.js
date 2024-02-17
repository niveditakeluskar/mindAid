import {defineConfig} from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
     plugins: [vue()],
   resolve: {
        alias: {
            '@': '/resources/js',
            '@@': '/public/',
            '@@@': '/resources/laravel/js',
        },
    },
  build: {
    rollupOptions: {
      input: '/resources/js/appInertia.js',
    },
  },
});
