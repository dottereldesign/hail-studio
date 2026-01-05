<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <Head title="Search Schools" />
        <section class="layout__container mx-auto flex min-h-[calc(100vh-4rem)] flex-col gap-4 px-6 py-8">
            <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Features</p>
            <h1 class="text-3xl font-semibold text-[var(--color-text)]">Search Schools</h1>
            <div class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-card)] p-6 text-sm text-[var(--color-muted)]">
                Placeholder page. Content coming soon.
            </div>
        </section>
    </AppLayout>
</template>
