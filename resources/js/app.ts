import { createInertiaApp } from '@inertiajs/vue3';
import type { DefineComponent } from 'vue';
import { createApp, createSSRApp, h } from 'vue';
import '../css/app.css';

createInertiaApp({
    title: (title) => (title ? `${title}` : 'Otim Florist'),
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>('./Pages/**/*.vue');

        const resolvePage = pages[`./Pages/${name}.vue`];

        if (!resolvePage) {
            throw new Error(`Inertia page not found: ./Pages/${name}.vue`);
        }

        return resolvePage();
    },
    setup({ el, App, props, plugin }) {
        const createVueApp = el.hasChildNodes() ? createSSRApp : createApp;
        createVueApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: { color: '#059669' },
});
