import * as Vue from 'vue';
import $ from 'jquery';
window.jQuery = window.$ = $;
import { createApp, h} from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { InertiaProgress } from '@inertiajs/progress';
import LoadingSpinner from './Pages/LoadingSpinner.vue'; // Import the LoadingSpinner component



createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: async (name) => {
    //console.log('Resolving component:', name);
    try {
      //console.log('Starting Resolved component:', name);
      const resolved = await resolvePageComponent(
        `./Pages/${name}.vue`,
        import.meta.glob("./Pages/**/*.vue")
      );
      //console.log('Resolved component:', resolved);
      return resolved;
    } catch (error) {
      console.error('Error resolving component:', error);
      // Fallback to an error component or handle the error appropriately
      return import('./Pages/Error404.vue');
    }
  },
  setup({ el, app, props, plugin }) {
    return createApp({ render: () => h(app, props) })
      .use(plugin)
      .component('loading-spinner', LoadingSpinner) // Register the LoadingSpinner component locally
      .mount(el);
  },

});