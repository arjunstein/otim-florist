import { createInertiaApp } from '@inertiajs/vue3';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import '../css/app.css';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue', { eager: true });

        const page = pages[`./Pages/${name}.vue`];

        if (!page) {
            throw new Error(`Inertia page not found: ./Pages/${name}.vue`);
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: { color: '#059669' },
});
