<script setup>
import { computed } from 'vue';
import { useToasts } from '@/lib/toast';

const state = useToasts();
const toasts = computed(() => state.toasts);
</script>

<template>
    <div class="pointer-events-none fixed bottom-6 right-6 z-50 flex w-80 flex-col gap-3">
        <transition-group name="toast" tag="div" class="flex flex-col gap-3">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="pointer-events-auto rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-4 py-3 text-sm text-[var(--color-text)] shadow-lg"
                :class="toast.type === 'error' ? 'toast--error' : ''"
            >
                {{ toast.message }}
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 180ms ease;
}

.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
</style>
