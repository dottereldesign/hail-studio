<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const role = computed(() => page.props.auth?.user?.role ?? '');
const canManageUsers = computed(() => ['OWNER', 'ADMIN'].includes(role.value));
const currentUrl = computed(() => page.url);

const isActive = (href) => {
    if (href === '/dashboard') {
        return currentUrl.value === '/dashboard';
    }

    return currentUrl.value.startsWith(href);
};

const linkClass = (href) => [
    'rounded-xl border border-[var(--color-border)] px-3 py-2 text-sm transition',
    isActive(href)
        ? 'border-[var(--color-border-strong)] bg-[var(--color-surface)] text-[var(--color-text)]'
        : 'text-[var(--color-muted)] hover:border-[var(--color-border-strong)] hover:text-[var(--color-text)]',
];
</script>

<template>
    <AppLayout>
        <div class="layout__container mx-auto flex min-h-[calc(100vh-4rem)] gap-6 px-6 py-8">
            <aside class="hidden w-64 shrink-0 md:block">
                <div class="sticky top-20 rounded-2xl border border-[var(--color-border)] bg-[var(--color-card)] p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">
                        Dashboard
                    </p>
                    <nav class="mt-4 space-y-2">
                        <Link href="/dashboard" :class="linkClass('/dashboard')">
                            Profile
                        </Link>
                        <Link
                            v-if="canManageUsers"
                            href="/dashboard/users"
                            :class="linkClass('/dashboard/users')"
                        >
                            Users
                        </Link>
                    </nav>
                </div>
            </aside>
            <div class="flex-1">
                <slot />
            </div>
        </div>
    </AppLayout>
</template>
