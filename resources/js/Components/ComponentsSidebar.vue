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
            v-for="category in categories"
            :key="category.id"
            :href="`/components/${category.slug}`"
            class="flex items-center justify-between rounded-xl px-3 py-2 text-sm transition"
            :class="
                category.slug === activeSlug
                    ? 'bg-slate-800 text-white'
                    : 'text-slate-300 hover:bg-slate-900 hover:text-white'
            "
            @click="$emit('select')"
        >
            <span>{{ category.name }}</span>
            <span v-if="category.slug === activeSlug" class="text-xs text-slate-400">Active</span>
        </Link>
    </nav>
</template>
