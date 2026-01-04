import { reactive } from 'vue';

const state = reactive({
    toasts: [],
});

let toastId = 0;

export function useToasts() {
    return state;
}

export function pushToast(message, type = 'success') {
    const id = `${Date.now()}-${toastId++}`;
    state.toasts.push({ id, message, type });

    window.setTimeout(() => {
        const index = state.toasts.findIndex((toast) => toast.id === id);
        if (index !== -1) {
            state.toasts.splice(index, 1);
        }
    }, 2600);
}
