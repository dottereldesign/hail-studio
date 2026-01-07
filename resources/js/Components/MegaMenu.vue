<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import cloudflareLogo from "../../images/cloudflare.png";
import cloudwaysLogo from "../../images/cloudways.png";
import feedbackLogo from "../../images/feedback.png";
import hailLinkLogo from "../../images/haillink.png";
import helpScoutLogo from "../../images/helpscout.png";
import manageWpLogo from "../../images/managewp.png";
import novaLogo from "../../images/nova.png";
import porkbunLogo from "../../images/porkbun.png";
import wpstaqLogo from "../../images/wpstaq.png";
import schoolSearchBg from "../../images/school_search-bg_img.png";

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
    {
        name: "ManageWP",
        href: "https://orion.managewp.com/login",
        image: manageWpLogo,
    },
    {
        name: "Feedback",
        href: "https://docs.google.com/document/d/1VPjDJNh6u5pirtw1VBP0uAg6jsm6XPvPJ5Ubq5UKwGM/edit?tab=t.tpqof2weewls#heading=h.kb5eimnhstpv",
        image: feedbackLogo,
    },
    { name: "Hail", href: "https://hail.to/app/", image: hailLinkLogo },
    {
        name: "HelpScout",
        href: "https://secure.helpscout.net/inboxes/bcf07e1aec902f73/views",
        image: helpScoutLogo,
    },
    {
        name: "Nova",
        href: "https://hail.to/NimD4R3pu5/hail-nova/login",
        image: novaLogo,
    },
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
                <div class="mt-6 grid grid-cols-2 items-center gap-4">
                    <div class="flex items-center justify-between gap-4">
                        <p
                            class="text-xs uppercase tracking-[0.4em] text-[var(--color-muted)]"
                        >
                            Theme
                        </p>
                        <button
                            type="button"
                            class="btn btn--ghost w-24 px-3 py-1 text-xs uppercase tracking-[0.25em]"
                            @click="$emit('toggle-theme')"
                        >
                            <i
                                v-if="theme === 'dark'"
                                class="fa-regular fa-sun"
                            ></i>
                            <i v-else class="fa-regular fa-moon"></i>
                            <span>{{
                                theme === "dark" ? "Light" : "Dark"
                            }}</span>
                        </button>
                    </div>
                    <div></div>
                </div>
                <Link
                    v-if="isAuthenticated"
                    href="/dashboard"
                    class="mt-6 flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                    @click="$emit('close')"
                >
                    Dashboard
                    <i
                        class="fa-solid fa-arrow-right text-[var(--color-muted)]"
                    ></i>
                </Link>
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
                        href="#"
                        class="relative flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click.prevent="$emit('close')"
                    >
                        <span
                            class="absolute left-[calc(var(--spacing)*-2-6px)] top-[calc(var(--spacing)*0.25+4px)] rotate-[-45deg] rounded-full bg-[#fff4bf] px-4 py-1 text-[8px] font-semibold uppercase tracking-[0.25em] text-[#8a6d00]"
                        >
                            Divi 4
                        </span>
                        Library
                    </Link>
                    <Link
                        href="/search-schools"
                        class="relative flex items-center justify-between overflow-hidden rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        Search Schools
                        <img
                            :src="schoolSearchBg"
                            alt=""
                            class="pointer-events-none absolute right-8 top-1/2 h-full w-auto -translate-y-1/2 translate-x-1/2 scale-140 object-cover mix-blend-multiply opacity-70"
                            aria-hidden="true"
                        />
                    </Link>
                    <Link
                        href="/api"
                        class="flex items-center justify-between rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] transition hover:border-[var(--color-border-strong)]"
                        @click="$emit('close')"
                    >
                        <span>
                            <span class="text-[#72AF2E]">hail</span> API
                        </span>
                    </Link>
                    <Link
                        href="#"
                        class="pointer-events-none flex flex-col items-start justify-center gap-1 rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-muted)] px-6 py-5 text-2xl font-semibold text-[var(--color-text)] opacity-60"
                    >
                        <span>Design System</span>
                        <span
                            class="text-xs font-medium uppercase tracking-[0.25em] text-[var(--color-muted)]"
                        >
                            Coming soon
                        </span>
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
