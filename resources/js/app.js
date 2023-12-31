/* import css */
import './bootstrap';
import '../css/app.css';

/* import bootstrap */
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import {
    faSignOutAlt,
    faUser,
    faTachometerAlt,
    faUsersCog,
    faUsers,
    faExclamationCircle,
    faTicketAlt,
} from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faSignOutAlt, faUser, faTachometerAlt, faUsersCog, faUsers, faExclamationCircle, faTicketAlt)

/* import laravel-permission-to-vuejs */
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'

/* Init Ziggy and Inertia */
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(LaravelPermissionToVueJS)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#2596be',
    },
});
