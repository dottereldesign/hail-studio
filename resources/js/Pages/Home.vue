<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import earthHero from "../../images/earthhero.png";
</script>

<template>
    <AppLayout page-class="home-page relative">
        <Head title="hail Studio" />
        <section class="relative flex min-h-[calc(100vh-4rem)] flex-col">
            <div
                class="relative z-10 flex flex-1 items-start justify-center px-6 pt-[20vh]"
            >
                <div class="flex flex-col items-center gap-12">
                    <div class="flex flex-col items-center gap-2">
                        <h1 class="text-7xl font-semibold tracking-wide">
                            <span class="text-[#72AF2E]">hail</span> Studio
                        </h1>
                        <span
                            class="text-sm uppercase tracking-[0.3em] text-[var(--color-muted)]"
                        >
                            Version 0.4
                        </span>
                    </div>
                    <div
                        class="flex items-center justify-center gap-6 text-4xl"
                    >
                        <i
                            class="fa-brands fa-laravel text-[#FF2D1F]"
                            aria-hidden="true"
                        ></i>
                        <i
                            class="fa-brands fa-vuejs text-[#3FB984]"
                            aria-hidden="true"
                        ></i>
                    </div>
                </div>
            </div>
        </section>
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-[length:100%_auto] bg-bottom bg-no-repeat"
            :style="{ backgroundImage: `url(${earthHero})` }"
        ></div>
    </AppLayout>
</template>
