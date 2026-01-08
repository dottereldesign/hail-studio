<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ComponentsSidebar from '@/Components/ComponentsSidebar.vue';
import LayoutTile from '@/Components/LayoutTile.vue';
import AddLayoutModal from '@/Components/AddLayoutModal.vue';

const props = defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    category: {
        type: Object,
        default: null,
    },
    layouts: {
        type: Object,
        default: () => ({ data: [], total: 0, current_page: 1, last_page: 1, links: [] }),
    },
});

const page = usePage();
const allowedRoles = ['OWNER', 'ADMIN', 'EDITOR'];
const canCreate = computed(() => allowedRoles.includes(page.props.auth?.user?.role ?? ''));
const selectedCategoryId = computed(() => props.category?.id ?? props.categories[0]?.id ?? null);

const sidebarOpen = ref(false);
const modalOpen = ref(false);

const layoutItems = computed(() => props.layouts?.data ?? []);
const totalCount = computed(() => props.layouts?.total ?? layoutItems.value.length);
const paginationLinks = computed(() => props.layouts?.links ?? []);
const currentPage = computed(() => props.layouts?.current_page ?? 1);
const deleteMode = ref(false);

const closeSidebar = () => {
    sidebarOpen.value = false;
};
</script>

<template>
    <AppLayout>
        <Head :title="category?.name ?? 'Library'" />
        <div class="components-page__content layout__container mx-auto flex min-h-[calc(100vh-4rem)] gap-6 px-6 py-8">
            <aside class="hidden w-64 shrink-0 md:block">
                <div class="rounded-2xl border border-[var(--color-border)] bg-[var(--color-card)] p-4">
                    <h2 class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Categories</h2>
                    <div class="mt-4">
                        <ComponentsSidebar
                            :categories="categories"
                            :active-slug="category?.slug"
                            base-path="/library"
                            all-label="All Layouts"
                        />
                    </div>
                </div>
            </aside>

            <div class="flex-1 space-y-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Layouts Library</p>
                        <h1 class="mt-2 text-3xl font-semibold">
                            {{ category?.name ?? 'Layouts' }}
                        </h1>
                        <p class="mt-1 text-sm text-[var(--color-muted)]">
                            {{ totalCount }} items
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            v-if="canCreate"
                            type="button"
                            class="btn btn--danger px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.2em]"
                            @click="deleteMode = !deleteMode"
                        >
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-minus text-[10px]"></i>
                                Delete
                            </span>
                        </button>
                        <button
                            v-if="canCreate"
                            type="button"
                            class="btn btn--primary border-2 px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.2em]"
                            @click="modalOpen = true"
                        >
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                Add Layout
                            </span>
                        </button>
                        <button
                            type="button"
                            class="md:hidden rounded-full border border-[var(--color-border)] px-4 py-2 text-xs uppercase tracking-[0.25em] text-[var(--color-text)]"
                            @click="sidebarOpen = true"
                        >
                            Categories
                        </button>
                    </div>
                </div>

                <div v-if="layoutItems.length" class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    <LayoutTile
                        v-for="layout in layoutItems"
                        :key="layout.id"
                        :layout="layout"
                        :page="currentPage"
                        :show-delete="deleteMode"
                    />
                </div>
                <div v-else class="rounded-2xl border border-dashed border-[var(--color-border)] p-10 text-center">
                    <p class="text-sm text-[var(--color-muted)]">No layouts yet for this category.</p>
                </div>

                <div v-if="paginationLinks.length > 3" class="flex flex-wrap items-center gap-2">
                    <Link
                        v-for="link in paginationLinks"
                        :key="link.label"
                        :href="link.url || '#'"
                        class="btn px-3 py-1 text-[10px] uppercase tracking-[0.2em]"
                        :class="[
                            link.active
                                ? 'btn--primary'
                                : 'border-[var(--color-border)] text-[var(--color-text)] hover:border-[var(--color-border-strong)]',
                            !link.url ? 'pointer-events-none opacity-40' : '',
                        ]"
                    >
                        <span v-html="link.label" />
                    </Link>
                </div>
            </div>
        </div>

        <AddLayoutModal
            :open="modalOpen"
            :categories="categories"
            :selected-category-id="selectedCategoryId"
            @close="modalOpen = false"
        />

        <transition name="fade">
            <div v-if="sidebarOpen" class="fixed inset-0 z-40 bg-[var(--color-bg-soft)] md:hidden" @click="closeSidebar" />
        </transition>
        <transition name="slide">
            <aside
                v-if="sidebarOpen"
                class="fixed left-0 top-16 z-50 h-[calc(100vh-4rem)] w-72 overflow-y-auto border-r border-[var(--color-border)] bg-[var(--color-bg)] p-6 md:hidden"
            >
                <h2 class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Categories</h2>
                <div class="mt-4">
                    <ComponentsSidebar
                        :categories="categories"
                        :active-slug="category?.slug"
                        base-path="/library"
                        all-label="All Layouts"
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
