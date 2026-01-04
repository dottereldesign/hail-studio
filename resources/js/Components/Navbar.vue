<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['toggle']);
</script>

<template>
    <header class="fixed inset-x-0 top-0 z-40 border-b border-slate-800 bg-slate-950/80 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
            <Link href="/" class="text-lg font-semibold tracking-wide">
                Hail Studio
            </Link>
            <div class="flex items-center gap-3">
                <Link
                    v-if="page.props.auth?.user"
                    href="/logout"
                    method="post"
                    as="button"
                    class="hidden rounded-full border border-slate-800 px-3 py-2 text-xs uppercase tracking-[0.25em] text-slate-300 transition hover:border-slate-600 hover:text-white md:inline-flex"
                >
                    Logout
                </Link>
                <Link
                    v-else
                    href="/login"
                    class="hidden rounded-full border border-slate-800 px-3 py-2 text-xs uppercase tracking-[0.25em] text-slate-300 transition hover:border-slate-600 hover:text-white md:inline-flex"
                >
                    Login
                </Link>
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-full border border-slate-700 px-4 py-2 text-sm uppercase tracking-[0.25em] text-slate-200 transition hover:border-slate-500 hover:text-white"
                    @click="$emit('toggle')"
                >
                    <span class="sr-only">Toggle menu</span>
                    <span>{{ isOpen ? 'Close' : 'Menu' }}</span>
                    <span class="text-base leading-none">|||</span>
                </button>
            </div>
        </div>
    </header>
</template>
