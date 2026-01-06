<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import cloudflareLogo from "../../images/cloudflare.png";
import cloudwaysLogo from "../../images/cloudways.png";
import porkbunLogo from "../../images/porkbun.png";
import wpstaqLogo from "../../images/wpstaq.png";

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const externalLinks = [
    { name: "WPStaq", href: "https://app.wpstaq.com/", image: wpstaqLogo },
    {
        name: "Cloudways",
        href: "https://unified.cloudways.com/login",
        image: cloudwaysLogo,
    },
    {
        name: "Cloudflare",
        href: "https://dash.cloudflare.com/",
        image: cloudflareLogo,
    },
    {
        name: "Porkbun",
        href: "https://porkbun.com/account/login",
        image: porkbunLogo,
    },
    { name: "ManageWP", href: "https://orion.managewp.com/login" },
    {
        name: "Feedback",
        href: "https://docs.google.com/document/d/1VPjDJNh6u5pirtw1VBP0uAg6jsm6XPvPJ5Ubq5UKwGM/edit?tab=t.tpqof2weewls#heading=h.kb5eimnhstpv",
    },
    { name: "Hail", href: "https://hail.to/app/" },
    {
        name: "HelpScout",
        href: "https://secure.helpscout.net/inboxes/bcf07e1aec902f73/views",
    },
    { name: "Nova", href: "https://hail.to/NimD4R3pu5/hail-nova/login" },
];

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
            v-if="open && isAuthenticated"
            class="fixed inset-0 z-50 flex items-center justify-center bg-[var(--color-surface)] px-6"
            @click.self="$emit('close')"
        >
            <div class="w-full max-w-2xl">
                <Link
                    v-if="isAuthenticated"
                    href="/dashboard"
                    class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                    @click="$emit('close')"
                >
                    Dashboard
                </Link>

                <div class="mt-10 flex items-center justify-between">
                    <p
                        class="text-xs uppercase tracking-[0.4em] text-[var(--color-muted)]"
                    >
                        Theme
                    </p>
                    <button
                        type="button"
                        class="btn btn--ghost px-3 py-1 text-xs uppercase tracking-[0.25em]"
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
                    class="mt-12 text-xs uppercase tracking-[0.4em] text-[var(--color-muted)]"
                >
                    Features
                </p>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <Link
                        href="/components"
                        class="relative flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py- text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        <span
                            class="absolute left-[calc(var(--spacing)*-5-2px)] top-[calc(var(--spacing)*1+2px)] rotate-[-45deg] rounded-full bg-[#ffe3f9] px-2 py-1 p text-[8px] font-semibold uppercase tracking-[0.25em] text-[#7a2f68]"
                        >
                            Elementor
                        </span>
                        Components
                    </Link>
                    <Link
                        href="/search-schools"
                        class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        Search Schools
                    </Link>
                    <Link
                        href="/api"
                        class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        API
                    </Link>
                </div>

                <p
                    class="mt-12 text-xs uppercase tracking-[0.4em] text-[var(--color-muted)]"
                >
                    Resources
                </p>
                <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-3">
                    <Link
                        href="/resources/licenses"
                        class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-5 py-4 text-base font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        Licenses
                    </Link>
                    <Link
                        href="/resources/roadmap"
                        class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-5 py-4 text-base font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        Roadmap
                    </Link>
                    <Link
                        href="/resources/launch-checklist"
                        class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-5 py-4 text-base font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        Launch Checklist
                    </Link>
                </div>

                <p
                    class="mt-12 text-xs uppercase tracking-[0.4em] text-[var(--color-muted)]"
                >
                    External Links
                </p>
                <div
                    class="mt-4 grid grid-cols-4 gap-4 md:grid-cols-6 xl:grid-cols-9"
                >
                    <a
                        v-for="(link, index) in externalLinks"
                        :key="`${link.name}-${index}`"
                        :href="link.href"
                        target="_blank"
                        rel="noreferrer"
                        class="group"
                        :aria-label="link.name"
                    >
                        <div
                            class="aspect-square w-full rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] transition group-hover:border-[var(--color-border-strong)]"
                        >
                            <img
                                v-if="link.image"
                                :src="link.image"
                                :alt="`${link.name} logo`"
                                class="h-full w-full rounded-2xl object-cover"
                                loading="lazy"
                            />
                        </div>
                    </a>
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
