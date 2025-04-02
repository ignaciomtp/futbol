
import '../css/app.css'
import 'bootstrap' // Esto carga el JS de Bootstrap
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { i18nVue } from 'laravel-vue-i18n'; 
import { ZiggyVue } from 'ziggy-js';
import Ziggy from './ziggy-routes';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(i18nVue, { 
                lang: props.initialPage.props.locale || 'en', // Usar el locale inicial desde Inertia
                resolve: async lang => {
                    const langs = import.meta.glob('../../lang/*.json');
                    return await langs[`../../lang/${lang}.json`]();
                }
            })
            .mount(el)
    },
})
