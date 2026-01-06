<script setup>
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const logoUrl = "/brand/logo.png";

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
        class="fixed inset-x-2 top-2 z-[60] mx-auto max-w-[1440px] rounded-[360px] border border-[var(--color-border)] bg-[var(--color-bg-soft)] backdrop-blur"
    >
        <div
            class="site-header__inner layout__container mx-auto flex h-16 items-center justify-between px-6"
        >
            <Link
                href="/"
                class="flex items-center gap-3 text-2xl font-semibold tracking-wide"
            >
                <img
                    :src="logoUrl"
                    alt="hail Studio logo"
                    class="h-9 w-9 mb-1"
                />
                <span><span class="text-[#72AF2E]">hail</span> Studio</span>
            </Link>
            <div class="flex items-center gap-3">
                <div
                    v-if="page.props.auth?.user"
                    class="relative hidden md:block"
                >
                    <div class="group flex items-center">
                        <Link href="/dashboard" class="flex items-center gap-3">
                            <div
                                class="h-9 w-9 overflow-hidden rounded-full border border-[var(--color-border)] bg-[var(--color-surface-muted)]"
                            >
                                <img
                                    v-if="page.props.auth?.user?.avatarUrl"
                                    :src="page.props.auth.user.avatarUrl"
                                    :alt="page.props.auth.user.name"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <span
                                class="text-sm font-semibold text-[var(--color-text)]"
                            >
                                {{ page.props.auth.user.name }}
                            </span>
                        </Link>
                        <div
                            class="absolute right-[-32px] top-full z-50 mt-[calc(var(--spacing)*3+2px)] w-48 origin-top-right rounded-b-2xl rounded-t-none border border-[var(--color-border)] bg-[var(--color-surface)] p-2 text-sm opacity-0 shadow-lg transition before:absolute before:-top-6 before:left-0 before:h-6 before:w-full before:content-[''] invisible group-hover:visible group-hover:opacity-100"
                        >
                            <Link
                                href="/dashboard"
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-[var(--color-muted)] transition hover:bg-[var(--color-surface-muted)] hover:text-[var(--color-text)]"
                            >
                                <i class="fa-solid fa-gauge text-sm"></i>
                                Dashboard
                            </Link>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-[var(--color-muted)] transition hover:bg-[var(--color-surface-muted)] hover:text-[var(--color-text)]"
                            >
                                <i
                                    class="fa-solid fa-right-from-bracket text-sm"
                                ></i>
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
                <Link
                    v-else
                    href="/login"
                    class="hidden cursor-pointer items-center gap-2 px-2 py-2 text-xs uppercase tracking-[0.25em] text-[var(--color-muted)] transition hover:text-[var(--color-text)] md:inline-flex"
                >
                    <i class="fa-solid fa-right-to-bracket text-sm"></i>
                    Login
                </Link>
                <button
                    v-if="page.props.auth?.user"
                    type="button"
                    class="btn w-[118px] px-4 py-2 text-sm uppercase tracking-[0.25em]"
                    :class="isOpen ? 'btn--secondary' : 'btn--ghost'"
                    @click="$emit('toggle')"
                >
                    <span class="sr-only">Toggle menu</span>
                    <span>{{ isOpen ? "Close" : "Menu" }}</span>
                    <i
                        :class="
                            isOpen
                                ? 'fa-solid fa-xmark text-base'
                                : 'fa-solid fa-bars text-base'
                        "
                    ></i>
                </button>
            </div>
        </div>
    </header>
</template>
