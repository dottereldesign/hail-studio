<script setup>
import { pushToast } from '@/lib/toast';

const props = defineProps({
    component: {
        type: Object,
        required: true,
    },
});

const copyPayload = async () => {
    try {
        const payload = JSON.stringify(props.component.payload, null, 2);
        await navigator.clipboard.writeText(payload);
        pushToast('Copied JSON to clipboard.');
    } catch (error) {
        pushToast('Could not copy. Please try again.', 'error');
    }
};
</script>

<template>
    <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4 shadow-sm">
        <div class="aspect-[4/3] overflow-hidden rounded-xl border border-slate-800 bg-slate-950">
            <img
                :src="component.image_url"
                :alt="component.name"
                class="h-full w-full object-cover"
            >
        </div>
        <div class="mt-4 flex items-center justify-between gap-3">
            <p class="text-sm font-semibold text-slate-100">
                {{ component.name }}
            </p>
            <button
                type="button"
                class="cursor-pointer rounded-full border border-sky-500/40 bg-sky-500/10 px-3 py-1 text-xs uppercase tracking-[0.2em] text-sky-100 transition hover:border-sky-400 hover:bg-sky-500/20 hover:text-white"
                @click="copyPayload"
            >
                <span class="flex items-center gap-2">
                    <i class="fa-regular fa-clipboard text-[11px]"></i>
                    Copy
                </span>
            </button>
        </div>
    </div>
</template>
