<script setup>
import { computed, ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DashboardUserModal from '@/Components/DashboardUserModal.vue';
import { pushToast } from '@/lib/toast';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const currentUserId = computed(() => page.props.auth?.user?.id);
const roles = ['OWNER', 'ADMIN', 'EDITOR', 'VIEWER'];

const modalOpen = ref(false);
const modalMode = ref('create');
const selectedUser = ref(null);

const openCreate = () => {
    modalMode.value = 'create';
    selectedUser.value = null;
    modalOpen.value = true;
};

const openEdit = (user) => {
    modalMode.value = 'edit';
    selectedUser.value = user;
    modalOpen.value = true;
};

const deleteUser = (user) => {
    if (user.id === currentUserId.value) {
        pushToast('You cannot delete your own account.', 'error');
        return;
    }

    if (!window.confirm('Delete this user? This cannot be undone.')) {
        return;
    }

    router.delete(`/dashboard/users/${user.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            pushToast('User deleted.');
        },
        onError: () => {
            pushToast('Could not delete user.', 'error');
        },
    });
};
</script>

<template>
    <DashboardLayout>
        <Head title="Users" />
        <div class="space-y-6">
            <div class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[var(--color-muted)]">Dashboard</p>
                        <h1 class="mt-2 text-3xl font-semibold text-[var(--color-text)]">Users</h1>
                        <p class="mt-2 text-sm text-[var(--color-muted)]">
                            Manage team access, roles, and profile details.
                        </p>
                    </div>
                    <button
                        type="button"
                        class="btn btn--primary px-5 py-2 text-xs uppercase tracking-[0.3em]"
                        @click="openCreate"
                    >
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-plus text-[11px]"></i>
                            Add User
                        </span>
                    </button>
                </div>
            </div>

            <div class="rounded-3xl border border-[var(--color-border)] bg-[var(--color-card)] p-6">
                <div class="space-y-4">
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface)] px-5 py-4"
                    >
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 overflow-hidden rounded-full border border-[var(--color-border)] bg-[var(--color-surface-muted)]">
                                <img
                                    v-if="user.avatarUrl"
                                    :src="user.avatarUrl"
                                    :alt="user.email"
                                    class="h-full w-full object-cover"
                                >
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[var(--color-text)]">{{ user.email }}</p>
                                <p class="text-xs text-[var(--color-muted)]">{{ user.name }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="badge badge--secondary">
                                {{ user.role }}
                            </span>
                            <button
                                type="button"
                                class="btn btn--ghost px-3 py-1 text-[10px] uppercase tracking-[0.2em]"
                                @click="openEdit(user)"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="btn btn--danger px-3 py-1 text-[10px] uppercase tracking-[0.2em] disabled:cursor-not-allowed disabled:opacity-60"
                                :disabled="user.id === currentUserId"
                                @click="deleteUser(user)"
                            >
                                Delete
                            </button>
                        </div>
                    </div>

                    <div v-if="!users.length" class="rounded-2xl border border-dashed border-[var(--color-border)] p-10 text-center">
                        <p class="text-sm text-[var(--color-muted)]">No users found for this organization.</p>
                    </div>
                </div>
            </div>
        </div>

        <DashboardUserModal
            :open="modalOpen"
            :mode="modalMode"
            :user="selectedUser"
            :roles="roles"
            @close="modalOpen = false"
        />
    </DashboardLayout>
</template>
