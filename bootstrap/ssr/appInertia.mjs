import { globRequire_Pages } from "./Pages/**/*";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
createInertiaApp({
  resolve: (name) => globRequire_Pages(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) }).use(plugin).mount(el);
  }
});
