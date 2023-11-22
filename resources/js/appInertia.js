import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress';
import { createInertiaApp } from '@inertiajs/inertia-vue3'

InertiaProgress.init()
 
createInertiaApp({
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})