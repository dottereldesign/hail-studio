import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';

const isDebug = import.meta.env.DEV && import.meta.env.VITE_DEBUG !== 'false';

const installGlobalErrorHandlers = (app) => {
    app.config.errorHandler = (error, instance, info) => {
        if (!isDebug) {
            return;
        }

        console.error('[vue:error]', {
            message: error?.message ?? 'Unknown error',
            info,
        });
    };

    if (!isDebug) {
        return;
    }

    window.addEventListener('error', (event) => {
        console.error('[window:error]', {
            message: event.message,
            filename: event.filename,
            lineno: event.lineno,
            colno: event.colno,
        });
    });

    window.addEventListener('unhandledrejection', (event) => {
        console.error('[window:unhandledrejection]', {
            reason: event.reason?.message ?? event.reason,
        });
    });

    document.addEventListener('inertia:error', (event) => {
        console.error('[inertia:error]', {
            status: event.detail?.response?.status ?? null,
        });
    });

    document.addEventListener('inertia:exception', (event) => {
        console.error('[inertia:exception]', {
            message: event.detail?.exception?.message ?? 'Inertia exception',
        });
    });
};

createInertiaApp({
    resolve: (name) => import(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        vueApp.use(plugin);
        installGlobalErrorHandlers(vueApp);
        vueApp.mount(el);
    },
});
