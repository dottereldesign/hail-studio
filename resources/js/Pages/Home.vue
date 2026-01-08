<script setup>
import { Head } from "@inertiajs/vue3";
import { computed, onBeforeUnmount, onMounted, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import earthHero from "../../images/earthhero.png";
import birdVideo from "../../images/animated_bird.mp4";
import birdVideoDark from "../../images/animated_bird-darktheme.mp4";

const stageRef = ref(null);
const pathRef = ref(null);
const videoRef = ref(null);
const routeLength = ref(0);
const themeMode = ref("light");
const birdVideoSrc = computed(() =>
    themeMode.value === "dark" ? birdVideoDark : birdVideo,
);

const config = {
    speed: 180,
    lookahead: 20,
    headingOffset: 180,
    flapAmplitude: 4,
    flapWavelength: 220,
    bobAmplitude: 6,
    bobWavelength: 160,
    driftAmplitude: 2,
    driftWavelength: 520,
    debugRoute: false,
    loopMode: "wrap",
    allowOverflow: false,
    routeString: null,
};

let distanceTraveled = 0;
let direction = 1;
let rafId = 0;
let lastTime = 0;
let resizeObserver;
let intersectionObserver;
let reduceMotionQuery;
let reduceMotionHandler;
let themeObserver;
let reducedMotion = false;
let inView = true;

const buildRoute = (width, height) => {
    if (config.routeString) {
        return config.routeString;
    }

    return [
        `M ${width * 1.06} ${height * 0.28}`,
        `C ${width * 0.78} ${height * 0.05}, ${width * 0.55} ${
            height * 0.55
        }, ${width * 0.38} ${height * 0.5}`,
        `S ${width * 0.12} ${height * 0.2}, ${width * -0.08} ${height * 0.32}`,
    ].join(" ");
};

const updateRoute = () => {
    if (!stageRef.value || !pathRef.value) return;
    const rect = stageRef.value.getBoundingClientRect();
    const route = buildRoute(rect.width, rect.height);
    pathRef.value.setAttribute("d", route);
    routeLength.value = pathRef.value.getTotalLength();
};

const samplePoint = (distance) => {
    if (!pathRef.value) return null;
    return pathRef.value.getPointAtLength(distance);
};

const advanceDistance = (deltaSeconds) => {
    if (!routeLength.value) return;
    distanceTraveled += config.speed * deltaSeconds * direction;

    if (config.loopMode === "wrap") {
        distanceTraveled =
            ((distanceTraveled % routeLength.value) + routeLength.value) %
            routeLength.value;
        return;
    }

    if (config.loopMode === "ping-pong") {
        if (distanceTraveled > routeLength.value) {
            distanceTraveled = routeLength.value;
            direction = -1;
        } else if (distanceTraveled < 0) {
            distanceTraveled = 0;
            direction = 1;
        }
        return;
    }

    if (config.loopMode === "stop") {
        distanceTraveled = Math.min(distanceTraveled, routeLength.value);
    }
};

const updateBirdPosition = () => {
    if (!videoRef.value || !routeLength.value) return;
    const point = samplePoint(distanceTraveled);
    const lookaheadPoint = samplePoint(
        Math.min(distanceTraveled + config.lookahead, routeLength.value)
    );

    if (!point || !lookaheadPoint) return;

    const angle =
        (Math.atan2(lookaheadPoint.y - point.y, lookaheadPoint.x - point.x) *
            180) /
        Math.PI;

    const flap =
        Math.sin((distanceTraveled / config.flapWavelength) * Math.PI * 2) *
        config.flapAmplitude;
    const bob =
        Math.sin((distanceTraveled / config.bobWavelength) * Math.PI * 2) *
        config.bobAmplitude;
    const drift =
        Math.sin((distanceTraveled / config.driftWavelength) * Math.PI * 2) *
        config.driftAmplitude;

    const rotation = angle + config.headingOffset + flap + drift;
    videoRef.value.style.transform = `translate3d(${point.x}px, ${point.y}px, 0) translate3d(-50%, -50%, 0) rotate(${rotation}deg) translate3d(0, ${bob}px, 0)`;
};

const animate = (timestamp) => {
    if (!lastTime) lastTime = timestamp;
    const deltaSeconds = (timestamp - lastTime) / 1000;
    lastTime = timestamp;

    if (routeLength.value) {
        advanceDistance(deltaSeconds);
        updateBirdPosition();
    }

    if (config.loopMode === "stop" && distanceTraveled >= routeLength.value) {
        stop();
        return;
    }

    rafId = window.requestAnimationFrame(animate);
};

const start = () => {
    if (rafId || reducedMotion || !inView) return;
    lastTime = 0;
    rafId = window.requestAnimationFrame(animate);
};

const stop = () => {
    if (!rafId) return;
    window.cancelAnimationFrame(rafId);
    rafId = 0;
    lastTime = 0;
};

onMounted(() => {
    updateRoute();
    updateBirdPosition();
    start();

    resizeObserver = new ResizeObserver(() => {
        updateRoute();
        updateBirdPosition();
    });
    if (stageRef.value) resizeObserver.observe(stageRef.value);

    intersectionObserver = new IntersectionObserver(
        ([entry]) => {
            inView = entry.isIntersecting;
            if (inView) {
                start();
            } else {
                stop();
            }
        },
        { threshold: 0.2 }
    );
    if (stageRef.value) intersectionObserver.observe(stageRef.value);

    reduceMotionQuery = window.matchMedia("(prefers-reduced-motion: reduce)");
    reducedMotion = reduceMotionQuery.matches;
    reduceMotionHandler = (event) => {
        reducedMotion = event.matches;
        if (reducedMotion) {
            stop();
        } else {
            start();
        }
    };
    reduceMotionQuery.addEventListener("change", reduceMotionHandler);

    if (typeof document !== "undefined") {
        themeMode.value =
            document.documentElement.dataset.theme || "light";
        themeObserver = new MutationObserver(() => {
            themeMode.value =
                document.documentElement.dataset.theme || "light";
        });
        themeObserver.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ["data-theme"],
        });
    }
});

onBeforeUnmount(() => {
    stop();
    if (resizeObserver) resizeObserver.disconnect();
    if (intersectionObserver) intersectionObserver.disconnect();
    if (reduceMotionQuery) {
        reduceMotionQuery.removeEventListener("change", reduceMotionHandler);
    }
    if (themeObserver) {
        themeObserver.disconnect();
    }
});
</script>

<template>
    <AppLayout page-class="home-page relative">
        <Head title="hail Studio" />
        <section
            ref="stageRef"
            class="relative flex min-h-[calc(100vh-4rem)] flex-col"
            :class="
                config.allowOverflow ? 'overflow-visible' : 'overflow-hidden'
            "
        >
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
                            Version 0.5
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
            <svg
                class="pointer-events-none absolute inset-0 z-0 h-full w-full"
                :class="config.debugRoute ? 'opacity-100' : 'opacity-0'"
            >
                <path
                    ref="pathRef"
                    d=""
                    fill="none"
                    stroke="rgba(255, 255, 255, 0.4)"
                    stroke-width="2"
                    stroke-dasharray="6 8"
                />
            </svg>
            <video
                ref="videoRef"
                class="pointer-events-none absolute left-0 top-0 z-0 h-50 w-50 origin-center object-contain will-change-transform"
                autoplay
                muted
                loop
                playsinline
                :src="birdVideoSrc"
                :key="birdVideoSrc"
            ></video>
        </section>
        <div
            class="pointer-events-none absolute inset-0 z-0 bg-[length:100%_auto] bg-bottom bg-no-repeat"
            :style="{ backgroundImage: `url(${earthHero})` }"
        ></div>
    </AppLayout>
</template>
