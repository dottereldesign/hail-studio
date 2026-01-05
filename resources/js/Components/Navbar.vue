<script setup>
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["toggle"]);
</script>

<template>
    <header
        class="fixed inset-x-0 top-0 z-[60] border-b border-[var(--color-border)] bg-[var(--color-bg-soft)] backdrop-blur"
    >
        <div
            class="site-header__inner layout__container mx-auto flex h-16 items-center justify-between px-6"
        >
            <Link href="/" class="text-lg font-semibold tracking-wide">
                <span class="text-[#72AF2E]">hail</span> Studio
            </Link>
            <div class="flex items-center gap-3">
                <Link
                    v-if="page.props.auth?.user"
                    href="/logout"
                    method="post"
                    as="button"
                    class="hidden cursor-pointer rounded-full border border-[var(--color-border)] px-3 py-2 text-xs uppercase tracking-[0.25em] text-[var(--color-muted)] transition hover:border-[var(--color-border-strong)] hover:text-[var(--color-text)] md:inline-flex"
                >
                    Logout
                </Link>
                <Link
                    v-else
                    href="/login"
                    class="hidden cursor-pointer rounded-full border border-[var(--color-border)] px-3 py-2 text-xs uppercase tracking-[0.25em] text-[var(--color-muted)] transition hover:border-[var(--color-border-strong)] hover:text-[var(--color-text)] md:inline-flex"
                >
                    Login
                </Link>
                <button
                    type="button"
                    class="btn px-4 py-2 text-sm uppercase tracking-[0.25em]"
                    :class="
                        isOpen ? 'btn--secondary' : 'btn--ghost'
                    "
                    @click="$emit('toggle')"
                >
                    <span class="sr-only">Toggle menu</span>
                    <span>{{ isOpen ? "Close" : "Menu" }}</span>
                    <i :class="isOpen ? 'fa-solid fa-xmark text-base' : 'fa-solid fa-bars text-base'"></i>
                </button>
            </div>
        </div>
    </header>
</template>
