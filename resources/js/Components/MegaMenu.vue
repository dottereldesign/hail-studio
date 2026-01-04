<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close']);
</script>

<template>
    <transition name="fade">
        <div
            v-if="open"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 px-6"
            @click.self="$emit('close')"
        >
            <button
                type="button"
                class="absolute right-6 top-6 rounded-full border border-slate-700 px-4 py-2 text-xs uppercase tracking-[0.3em] text-slate-200 transition hover:border-slate-400 hover:text-white"
                @click="$emit('close')"
            >
                Close
            </button>
            <div class="w-full max-w-2xl">
                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Features</p>
                <div class="mt-6 space-y-4">
                    <Link
                        href="/components"
                        class="flex items-center justify-between rounded-2xl border border-slate-800 bg-slate-900/60 px-6 py-5 text-2xl font-semibold text-white transition hover:border-slate-600 hover:bg-slate-900"
                        @click="$emit('close')"
                    >
                        Components
                        <i class="fa-solid fa-arrow-right text-slate-400"></i>
                    </Link>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 200ms ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
