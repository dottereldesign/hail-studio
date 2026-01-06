<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    activeSlug: {
        type: String,
        default: null,
    },
});

defineEmits(['select']);
</script>

<template>
    <nav class="space-y-1">
        <Link
            href="/components"
            class="flex items-center justify-between rounded-xl border border-[var(--color-border)] px-3 py-2 text-sm transition"
            :class="
                !activeSlug
                    ? 'border-[var(--color-border-strong)] bg-[var(--color-surface)] text-[var(--color-text)]'
                    : 'text-[var(--color-muted)] hover:border-[var(--color-border-strong)] hover:bg-[var(--color-surface)] hover:text-[var(--color-text)]'
            "
            @click="$emit('select')"
        >
            <span>All Components</span>
            <span v-if="!activeSlug" class="text-[10px] uppercase tracking-[0.2em] text-[var(--color-muted)]">All</span>
        </Link>
        <Link
            v-for="category in categories"
            :key="category.id"
            :href="`/components/${category.slug}`"
            class="flex items-center justify-between rounded-xl px-3 py-2 text-sm transition"
            :class="
                category.slug === activeSlug
                    ? 'bg-[var(--color-surface-strong)] text-[var(--color-text)]'
                    : 'text-[var(--color-muted)] hover:bg-[var(--color-surface)] hover:text-[var(--color-text)]'
            "
            @click="$emit('select')"
        >
            <span>{{ category.name }}</span>
            <span v-if="category.slug === activeSlug" class="text-xs text-[var(--color-muted)]">Active</span>
        </Link>
    </nav>
</template>
