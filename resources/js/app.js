import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { pushToast } from '@/lib/toast';

const isDebug = import.meta.env.DEV && import.meta.env.VITE_DEBUG !== 'false';
const reloadStateKey = '__hailSessionReloaded';

const installGlobalErrorHandlers = (app) => {
    document.addEventListener('inertia:error', (event) => {
        const status = event.detail?.response?.status ?? null;

        if (status === 419) {
            if (!window[reloadStateKey]) {
                window[reloadStateKey] = true;
                pushToast('Session expired. Reloading…', 'error');
                window.setTimeout(() => {
                    window.location.reload();
                }, 1200);
            }
            return;
        }

        if (status === 500) {
            window.location.href = '/error';
            return;
        }
    });

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

const pages = import.meta.glob('./Pages/**/*.vue');

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, pages),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });
        vueApp.use(plugin);
        installGlobalErrorHandlers(vueApp);
        vueApp.mount(el);
    },
});
