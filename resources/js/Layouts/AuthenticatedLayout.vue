<script setup>
import { ref } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ChatBot from "@/Components/ChatBot.vue";

const isShowSideBar = ref(true);
const page = usePage();

const sidebarMenus = [
    { menuName: "Home", route: route("client.home"), icon: "fa-solid fa-home" },
    {
        menuName: "Booking",
        route: route("client.booking.index"),
        icon: "fa-solid fa-book-bookmark",
    },
    {
        menuName: "Notifications",
        route: route("client.notifications"),
        icon: "fa-solid fa-bell",
        hasBadge: true,
    },
    {
        menuName: "Profile",
        route: route("client.profile.index"),
        icon: "fa-solid fa-image-portrait",
    },
];

const isActive = (href) => page.url.startsWith(new URL(href).pathname);
</script>

<template>
    <div class="flex h-screen bg-gray-100 overflow-hidden">
        <!-- Mobile Overlay (only on small screens) -->
        <div
            v-if="isShowSideBar"
            @click="isShowSideBar = false"
            class="fixed inset-0 bg-black/50 z-20 lg:hidden"
        ></div>

        <!-- Sidebar -->
        <aside
            :class="[
                isShowSideBar
                    ? 'translate-x-0 lg:w-64'
                    : '-translate-x-full lg:translate-x-0 lg:w-0',
                'fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-white transition-all duration-300 ease-in-out shadow-xl',
                'lg:relative lg:inset-y-auto lg:left-auto lg:z-auto lg:flex-shrink-0 lg:overflow-hidden',
            ]"
        >
            <div class="w-64 h-full flex flex-col">
                <!-- Logo -->
                <div
                    class="h-16 flex items-center justify-between px-5 border-b border-slate-800"
                >
                    <Link
                        class="flex items-center gap-3 no-underline flex-shrink-0"
                        :href="route('client.home')"
                    >
                        <svg
                            class="w-8 h-8 fill-brass flex-shrink-0"
                            viewBox="0 0 48 48"
                        >
                            <path
                                d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                            />
                        </svg>
                        <div>
                            <div
                                class="font-display text-white text-base font-semibold leading-tight"
                            >
                                Butal Ship Hauz
                            </div>
                            <div
                                class="font-mono text-brass text-[10px] tracking-[.18em] uppercase leading-none"
                            >
                                Talibon, Bohol
                            </div>
                        </div>
                    </Link>
                    <button
                        @click="isShowSideBar = false"
                        class="lg:hidden text-gray-400 hover:text-white"
                    >
                        <font-awesome-icon icon="fa-solid fa-xmark" />
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="p-4 space-y-2">
                    <Link
                        v-for="menu in sidebarMenus"
                        :key="menu.menuName"
                        :href="menu.route"
                        :class="[
                            'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
                            isActive(menu.route)
                                ? 'bg-slate-800 text-white font-semibold'
                                : 'text-gray-300 hover:bg-slate-800 hover:text-white',
                        ]"
                    >
                        <div class="relative">
                            <font-awesome-icon :icon="menu.icon" />
                            <span
                                v-if="menu.hasBadge"
                                class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"
                            />
                        </div>
                        <span>{{ menu.menuName }}</span>
                    </Link>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Navbar -->
            <header
                class="h-16 bg-navy shadow-sm border-b border-gray-200 flex items-center justify-between px-6"
            >
                <div class="flex items-center gap-4">
                    <button
                        @click="isShowSideBar = !isShowSideBar"
                        class="text-gray-400 hover:text-white transition"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-bars"
                            class="text-xl"
                        />
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        :href="route('landing-page')"
                        method="get"
                        as="button"
                        class="px-4 py-2 text-navy bg-brass hover:bg-gold rounded-lg transition"
                    >
                        <font-awesome-icon icon="fa-solid fa-newspaper" />
                        Feed
                    </Link>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="px-4 py-2 text-navy bg-brass hover:bg-gold rounded-lg transition"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-right-from-bracket"
                        />
                    </Link>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 relative">
                <!-- Background watermark logo -->
                <div
                    class="pointer-events-none fixed inset-0 flex items-center justify-center z-0 overflow-hidden"
                >
                    <svg
                        class="w-[100px] h-[100px] sm:w-[480px] sm:h-[480px] fill-slate-900 opacity-[0.12] flex-shrink-0"
                        viewBox="0 0 48 48"
                    >
                        <path
                            d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                        />
                    </svg>
                    <div>
                        <div
                            class="font-display text-slate-900 text-md font-semibold leading-tight"
                        >
                            Butal Ship Hauz
                        </div>
                        <div
                            class="font-mono text-brass text-md tracking-[.18em] uppercase leading-none"
                        >
                            Talibon, Bohol
                        </div>
                    </div>
                </div>

                <div class="relative z-10">
                    <slot />
                </div>
            </main>

            <ChatBot />
        </div>
    </div>
</template>
