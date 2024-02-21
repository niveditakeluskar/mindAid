import * as Vue from 'vue';
import $ from 'jquery';
window.jQuery = window.$ = $;
import { createApp, h} from "vue";
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import LoadingSpinner from './Pages/LoadingSpinner.vue'; // Import the LoadingSpinner component

createInertiaApp({
  resolve: async (name) => {
    //console.log('Resolving component:', name);
    try {
      //console.log('Starting Resolved component:', name);
      const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
    } catch (error) {
      console.error('Error resolving component:', error);
      // Fallback to an error component or handle the error appropriately
      return import('./Pages/Error404.vue');
    }
  },
  setup({ el, App, props, plugin }) {

    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .component('loading-spinner', LoadingSpinner)
      .mount(el);
  },

});