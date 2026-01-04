<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ComponentsSidebar from '@/Components/ComponentsSidebar.vue';
import ComponentTile from '@/Components/ComponentTile.vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    category: {
        type: Object,
        default: null,
    },
    components: {
        type: Array,
        default: () => [],
    },
});

const sidebarOpen = ref(false);

const closeSidebar = () => {
    sidebarOpen.value = false;
};
</script>

<template>
    <AppLayout>
        <Head :title="category?.name ?? 'Components'" />
        <div class="mx-auto flex min-h-[calc(100vh-4rem)] max-w-6xl gap-6 px-6 py-8">
            <aside class="hidden w-64 shrink-0 md:block">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/40 p-4">
                    <h2 class="text-xs uppercase tracking-[0.3em] text-slate-400">Categories</h2>
                    <div class="mt-4">
                        <ComponentsSidebar
                            :categories="categories"
                            :active-slug="category?.slug"
                        />
                    </div>
                </div>
            </aside>

            <div class="flex-1 space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Components Library</p>
                        <h1 class="mt-2 text-3xl font-semibold">
                            {{ category?.name ?? 'Components' }}
                        </h1>
                        <p class="mt-1 text-sm text-slate-400">
                            {{ components.length }} items
                        </p>
                    </div>
                    <button
                        type="button"
                        class="md:hidden rounded-full border border-slate-700 px-4 py-2 text-xs uppercase tracking-[0.25em] text-slate-200"
                        @click="sidebarOpen = true"
                    >
                        Categories
                    </button>
                </div>

                <div v-if="components.length" class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    <ComponentTile
                        v-for="component in components"
                        :key="component.id"
                        :component="component"
                    />
                </div>
                <div v-else class="rounded-2xl border border-dashed border-slate-800 p-10 text-center">
                    <p class="text-sm text-slate-400">No components yet for this category.</p>
                </div>
            </div>
        </div>

        <transition name="fade">
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-slate-950/80 md:hidden" @click="closeSidebar" />
        </transition>
        <transition name="slide">
            <aside
                v-if="sidebarOpen"
                class="fixed left-0 top-16 z-50 h-[calc(100vh-4rem)] w-72 overflow-y-auto border-r border-slate-800 bg-slate-950 p-6 md:hidden"
            >
                <h2 class="text-xs uppercase tracking-[0.3em] text-slate-400">Categories</h2>
                <div class="mt-4">
                    <ComponentsSidebar
                        :categories="categories"
                        :active-slug="category?.slug"
                        @select="closeSidebar"
                    />
                </div>
            </aside>
        </transition>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 150ms ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-enter-active,
.slide-leave-active {
    transition: transform 200ms ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}
</style>
