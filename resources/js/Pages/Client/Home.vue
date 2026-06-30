<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    bookings: {
        type: Array,
        default: () => [],
    },
    notifications: {
        type: Array,
        default: () => [],
    },
});

const bookings = computed(() => props.bookings);
const notifications = computed(() => props.notifications);

const unreadCount = computed(
    () => notifications.value.filter((n) => !n.read_at).length,
);

const activeTab = ref("bookings");

const statusConfig = {
    pending: { label: "Pending", badge: "bg-amber-50 text-amber-700 border-amber-200", icon: "fa-solid fa-hourglass-half" },
    confirmed: { label: "Confirmed", badge: "bg-emerald-50 text-emerald-700 border-emerald-200", icon: "fa-solid fa-circle-check" },
    cancelled: { label: "Cancelled", badge: "bg-red-50 text-red-700 border-red-200", icon: "fa-solid fa-circle-xmark" },
    completed: { label: "Completed", badge: "bg-blue-50 text-blue-700 border-blue-200", icon: "fa-solid fa-flag-checkered" },
};

function getStatus(status) {
    return (
        statusConfig[status] ?? {
            label: status,
            badge: "bg-gray-100 text-gray-700 border-gray-200",
            icon: "fa-solid fa-circle",
        }
    );
}

function formatDate(dateStr) {
    if (!dateStr) return "—";
    return new Date(dateStr).toLocaleDateString("en-PH", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
}

function formatTime(dateStr) {
    if (!dateStr) return "";
    return new Date(dateStr).toLocaleTimeString("en-PH", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

const pendingCount = computed(
    () => bookings.value.filter((b) => b.status === "pending").length,
);
const confirmedCount = computed(
    () => bookings.value.filter((b) => b.status === "completed").length,
);
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50">
            <!-- ── HERO BANNER ── -->
            <section
                class="relative overflow-hidden bg-navy px-6 pb-16 pt-10 rounded-lg"
            >
                <!-- Wake / wave decoration -->
                <div class="pointer-events-none absolute bottom-0 left-0 right-0">
                    <svg
                        viewBox="0 0 1200 60"
                        preserveAspectRatio="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="block h-12 w-full"
                    >
                        <path
                            d="M0,30 C200,60 400,0 600,30 C800,60 1000,0 1200,30 L1200,60 L0,60 Z"
                            fill="rgba(201,161,74,0.08)"
                        />
                        <path
                            d="M0,42 C150,15 350,55 600,38 C850,22 1050,50 1200,38 L1200,60 L0,60 Z"
                            fill="rgba(201,161,74,0.05)"
                        />
                    </svg>
                </div>

                <!-- Content -->
                <div class="relative z-10 mx-auto flex flex-wrap items-center gap-4">
                    <svg class="h-14 w-14 flex-shrink-0 fill-brass" viewBox="0 0 48 48">
                        <path
                            d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                        />
                    </svg>

                    <div class="min-w-[180px] flex-1">
                        <p class="font-mono text-[11px] font-semibold uppercase tracking-[.18em] text-brass">
                            Welcome aboard
                        </p>
                        <h1 class="font-display mt-1 text-2xl font-semibold text-white sm:text-3xl">
                            {{ user?.name ?? "Sailor" }}
                        </h1>
                        <p class="mt-1 text-sm text-slate-400">
                            Your voyage dashboard at The Butal Ship Hauz
                        </p>
                    </div>

                    <!-- Unread alert badge -->
                    <Link
                        :href="route('client.notifications.index')"
                        method="get"
                        as="button"
                        class="flex w-full shrink-0 flex-col items-center rounded-md bg-brass py-2.5 text-center shadow-md transition hover:bg-gold sm:w-auto sm:px-6 sm:py-3"
                    >
                        <span class="font-display text-2xl font-bold leading-none text-navy">
                            {{ unreadCount }}
                        </span>
                        <span class="mt-0.5 font-mono text-[10px] font-bold uppercase tracking-wider text-navy">
                            new alert{{ unreadCount !== 1 ? "s" : "" }}
                        </span>
                    </Link>
                </div>
            </section>

            <!-- ── STAT CARDS ── -->
            <div class="relative z-10 mx-auto -mt-7 grid grid-cols-2 gap-3 px-3 sm:grid-cols-4 sm:px-6">
                <div class="flex items-center gap-3 rounded-xl border-b-4 border-brass bg-white px-4 py-4 shadow-md">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-navy text-brass">
                        <font-awesome-icon icon="fa-solid fa-calendar" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-display text-xl font-bold leading-none text-navy">
                            {{ bookings.length }}
                        </p>
                        <p class="mt-1 font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Total Bookings
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-xl border-b-4 border-amber-400 bg-white px-4 py-4 shadow-md">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <font-awesome-icon icon="fa-solid fa-hourglass-half" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-display text-xl font-bold leading-none text-navy">
                            {{ pendingCount }}
                        </p>
                        <p class="mt-1 font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Pending
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-xl border-b-4 border-emerald-400 bg-white px-4 py-4 shadow-md">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <font-awesome-icon icon="fa-solid fa-check-double" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-display text-xl font-bold leading-none text-navy">
                            {{ confirmedCount }}
                        </p>
                        <p class="mt-1 font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Completed
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-xl border-b-4 border-rose-400 bg-white px-4 py-4 shadow-md">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                        <font-awesome-icon icon="fa-solid fa-bell" />
                    </div>
                    <div class="min-w-0">
                        <p class="font-display text-xl font-bold leading-none text-navy">
                            {{ unreadCount }}
                        </p>
                        <p class="mt-1 font-mono text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                            Unread Alerts
                        </p>
                    </div>
                </div>
            </div>

            <!-- ── TABBED CONTENT ── -->
            <div class="mx-auto mt-8 px-3 pb-16 sm:px-6">
                <!-- Tab bar -->
                <div class="mb-5 flex gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
                    <button
                        class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-all"
                        :class="
                            activeTab === 'bookings'
                                ? 'bg-navy text-white shadow'
                                : 'text-slate-500 hover:text-slate-700'
                        "
                        @click="activeTab = 'bookings'"
                    >
                        <font-awesome-icon icon="fa-solid fa-book-bookmark" />
                        Recent Bookings
                    </button>
                    <button
                        class="relative flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-all"
                        :class="
                            activeTab === 'notifications'
                                ? 'bg-navy text-white shadow'
                                : 'text-slate-500 hover:text-slate-700'
                        "
                        @click="activeTab = 'notifications'"
                    >
                        <font-awesome-icon icon="fa-solid fa-bell" />
                        Notifications
                        <span
                            v-if="unreadCount"
                            class="ml-1 rounded-full bg-brass px-2 py-0.5 font-mono text-[10px] font-bold text-navy"
                        >
                            {{ unreadCount }}
                        </span>
                    </button>
                </div>

                <!-- ── BOOKINGS PANEL ── -->
                <div v-if="activeTab === 'bookings'">
                    <!-- Empty state -->
                    <div v-if="bookings.length === 0" class="rounded-xl bg-white py-16 text-center shadow-sm">
                        <font-awesome-icon icon="fa-solid fa-anchor" class="text-4xl text-slate-300" />
                        <h3 class="font-display mt-4 text-base font-semibold text-navy">
                            No bookings yet
                        </h3>
                        <p class="mx-auto mt-1 max-w-xs text-sm text-slate-500">
                            Ready to chart your course? Make your first reservation at The Butal Ship Hauz.
                        </p>
                        <Link
                            :href="route('client.booking.index')"
                            class="mt-5 inline-block rounded-full bg-navy px-7 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                        >
                            Book a Visit
                        </Link>
                    </div>

                    <!-- Booking cards -->
                    <div v-else class="flex flex-col gap-4">
                        <div
                            v-for="booking in bookings"
                            :key="booking.id"
                            class="rounded-xl border-l-4 border-brass bg-white p-5 shadow-sm transition-shadow hover:shadow-md"
                        >
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="font-mono text-[10px] font-semibold uppercase tracking-widest text-slate-400">
                                        Ref #{{ booking.id }}
                                    </p>
                                    <p class="font-display mt-0.5 text-base font-semibold text-navy">
                                        {{ booking.package ?? booking.event_type ?? "Venue Booking" }}
                                    </p>
                                </div>
                                <span
                                    class="flex items-center gap-1.5 rounded-full border px-3 py-1 text-[11px] font-bold uppercase tracking-wide"
                                    :class="getStatus(booking.status).badge"
                                >
                                    <font-awesome-icon :icon="getStatus(booking.status).icon" class="text-[10px]" />
                                    {{ getStatus(booking.status).label }}
                                </span>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600">
                                <span class="flex items-center gap-1.5">
                                    <font-awesome-icon icon="fa-solid fa-calendar-day" class="text-slate-400" />
                                    {{ formatDate(booking.date) }}
                                </span>
                                <span v-if="booking.guests" class="flex items-center gap-1.5">
                                    <font-awesome-icon icon="fa-solid fa-users" class="text-slate-400" />
                                    {{ booking.guests }} guest{{ booking.guests !== 1 ? "s" : "" }}
                                </span>
                                <span v-if="booking.total_amount" class="flex items-center gap-1.5 font-medium text-navy">
                                    <font-awesome-icon icon="fa-solid fa-peso-sign" class="text-brass" />
                                    {{ Number(booking.total_amount).toLocaleString() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── NOTIFICATIONS PANEL ── -->
                <div v-if="activeTab === 'notifications'">
                    <!-- Empty state -->
                    <div v-if="notifications.length === 0" class="rounded-xl bg-white py-16 text-center shadow-sm">
                        <font-awesome-icon icon="fa-solid fa-bell-slash" class="text-4xl text-slate-300" />
                        <h3 class="font-display mt-4 text-base font-semibold text-navy">
                            All clear on deck
                        </h3>
                        <p class="mx-auto mt-1 max-w-xs text-sm text-slate-500">
                            No notifications right now. We'll signal you when something needs your attention.
                        </p>
                    </div>

                    <!-- Notification list -->
                    <div v-else class="flex flex-col gap-3">
                        <Link
                            v-for="notif in notifications"
                            :key="notif.id"
                            class="flex items-start gap-4 rounded-xl border-l-4 bg-white p-5 shadow-sm transition-shadow hover:shadow-md"
                            :class="notif.read_at ? 'border-transparent' : 'border-brass bg-amber-50/30'"
                            :href="route('client.notifications.index')"
                        >
                            <!-- Unread dot -->
                            <div class="mt-1.5 shrink-0">
                                <div
                                    class="h-2.5 w-2.5 rounded-full"
                                    :class="notif.read_at ? 'bg-slate-200' : 'bg-brass'"
                                ></div>
                            </div>

                            <div class="flex-1">
                                <p class="text-sm font-semibold text-navy">{{ notif.title }}</p>
                                <p class="mt-0.5 text-sm text-slate-600">{{ notif.message }}</p>
                                <p class="mt-2 font-mono text-[11px] text-slate-400">
                                    {{ formatDate(notif.created_at) }} · {{ formatTime(notif.created_at) }}
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>