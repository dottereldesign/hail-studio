<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
    theme: {
        type: String,
        default: "dark",
    },
});

defineEmits(["close", "toggle-theme"]);
</script>

<template>
    <transition name="fade">
        <div
            v-if="open"
            class="fixed inset-0 z-50 flex items-center justify-center bg-[var(--color-surface)] px-6"
            @click.self="$emit('close')"
        >
            <button
                type="button"
                class="absolute right-6 top-6 rounded-full border border-[var(--color-border)] px-4 py-2 text-xs uppercase tracking-[0.3em] text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                @click="$emit('close')"
            >
                Close
            </button>
            <div class="w-full max-w-2xl">
                <div class="flex items-center justify-between">
                    <p
                        class="text-xs uppercase tracking-[0.4em] text-[var(--color-muted)]"
                    >
                        Theme
                    </p>
                    <button
                        type="button"
                        class="inline-flex items-center gap-2 rounded-full border border-[var(--color-border)] px-3 py-1 text-xs uppercase tracking-[0.25em] text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('toggle-theme')"
                    >
                        <i
                            v-if="theme === 'dark'"
                            class="fa-regular fa-sun"
                        ></i>
                        <i v-else class="fa-regular fa-moon"></i>
                        <span>{{ theme === "dark" ? "Light" : "Dark" }}</span>
                    </button>
                </div>
                <p
                    class="mt-10 text-xs uppercase tracking-[0.4em] text-[var(--color-muted)]"
                >
                    Features
                </p>
                <div class="mt-6 space-y-4">
                    <Link
                        href="/components"
                        class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        Components
                        <i
                            class="fa-solid fa-arrow-right text-[var(--color-muted)]"
                        ></i>
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
