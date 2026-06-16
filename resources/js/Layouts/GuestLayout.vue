<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-navy py-6 px-2 sm:px-0 sm:justify-center sm:pt-0"
    >
        <div>
            <Link class="flex items-center justify-between" href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
                <div>
                    <div
                        class="font-display text-white text-lg font-semibold leading-tight"
                    >
                        Butal Ship Hauz
                    </div>
                    <div
                        class="font-mono text-brass text-[11px] tracking-[.18em] uppercase leading-none"
                    >
                        Talibon, Bohol
                    </div>
                </div>
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-3 py-2 sm:px-4 sm:py-4 shadow-md sm:max-w-md rounded-sm sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
