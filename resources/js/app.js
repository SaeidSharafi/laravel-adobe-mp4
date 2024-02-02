import './bootstrap';
import './config.js';
import '../css/app.css';
import '@vueform/multiselect/themes/default.css'

import {createApp, h} from 'vue';
import {createPinia} from "pinia";
import {useStyleStore} from "@/admin/style.js";
import {darkModeKey, styleKey} from "@/config.js";
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import * as ConfirmDialog from 'vuejs-confirm-dialog'

/* Multi-language */
import {createI18n} from "vue-i18n";
import appLangEn from "@/Lang/en";
import appLangFa from "@/Lang/fa";
//
// import localeMessages from "@/Lang/vue-i18n-locales.generated";
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import {modal} from "momentum-modal";


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel Easy Dash';

const pinia = createPinia();

const styleStore = useStyleStore(pinia);


/* App style */
styleStore.setStyle(localStorage[styleKey] ?? "basic");

if (
    (!localStorage[darkModeKey] &&
        window.matchMedia("(prefers-color-scheme: dark)").matches) ||
    localStorage[darkModeKey] === "1"
) {
    styleStore.setDarkMode(true);
}
const numberFormat={
    'en': {
        currency: {
            style: 'currency', currency: 'USD', notation: 'standard'
        },
        grouping: {
            style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2
        },
        decimal: {
            style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2
        },
        percent: {
            style: 'percent', useGrouping: false
        }
    },
    'fa': {
        currency: {
            style: 'currency', currency: 'IRR', notation: 'standard'
        },
        grouping: {
            style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2
        },
        decimal: {
            style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2
        },
        percent: {
            style: 'percent', useGrouping: false
        }
    },
}
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    progress: {
        color: '#32982e',
    },
    resolve: (name) => {
        const page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob(['./Pages/**/*.vue','../images/**',]));
        page.then((module) => {
            module.default.layout = module.default.layout || AuthenticatedLayout;
        });

        return page;
    },
    setup({el, App, props, plugin}) {
        const i18n = createI18n({
            legacy: false,
            locale: props.initialPage.props.locale,
            fallbackLocale: "en",
            fallbackRoot: "en",
            numberFormats:numberFormat,
            messages: {
                en: appLangEn,
                fa: appLangFa,
            },
        });
        return createApp({render: () => h(App, props)})
            .use(modal, {
                resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
            })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue, Ziggy)
            .use(pinia)
            .use(ConfirmDialog)
            .mount(el);
    },
});
