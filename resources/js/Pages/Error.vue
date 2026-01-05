<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const reloadPage = () => {
    window.location.reload();
};
</script>

<template>
    <AppLayout>
        <Head title="Something went wrong" />
        <div class="layout__container mx-auto flex min-h-[calc(100vh-4rem)] items-center justify-center px-6 py-10">
            <div class="max-w-xl rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8 text-center shadow-xl">
                <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Server Error</p>
                <h1 class="mt-4 text-3xl font-semibold text-[var(--color-text)]">
                    Something went wrong
                </h1>
                <p class="mt-3 text-sm text-[var(--color-muted)]">
                    We hit an unexpected error while processing your request. Please try again.
                </p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <button
                        type="button"
                        class="rounded-full border border-[var(--color-border-strong)] bg-white/10 px-5 py-2 text-xs uppercase tracking-[0.3em] text-[var(--color-text)] transition hover:border-[var(--color-border-strong)] hover:bg-white/20"
                        @click="reloadPage"
                    >
                        Reload
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
