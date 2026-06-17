\
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

// ── STATIC DATA ──
const bookings = ref([
    {
        id: 1001,
        package: "Captain's Experience",
        event_type: null,
        status: "confirmed",
        check_in: "2025-07-10",
        check_out: null,
        guests: 4,
        total_amount: 800,
    },
    {
        id: 1002,
        package: "Overnight Stay",
        event_type: null,
        status: "pending",
        check_in: "2025-07-18",
        check_out: "2025-07-19",
        guests: 2,
        total_amount: 3500,
    },
    {
        id: 1003,
        package: null,
        event_type: "Private Event",
        status: "completed",
        check_in: "2025-06-01",
        check_out: "2025-06-01",
        guests: 30,
        total_amount: 15000,
    },
    {
        id: 1004,
        package: "Standard Visit",
        event_type: null,
        status: "cancelled",
        check_in: "2025-05-20",
        check_out: null,
        guests: 2,
        total_amount: 100,
    },
]);

const notifications = ref([
    {
        id: 1,
        read_at: null,
        title: "Booking Confirmed!",
        message:
            "Your Captain's Experience on July 10 has been confirmed. See you on deck!",
        created_at: "2025-07-01T09:15:00",
    },
    {
        id: 2,
        read_at: null,
        title: "Upcoming Reservation Reminder",
        message:
            "Your Overnight Stay is in 3 days. Please settle the remaining balance upon arrival.",
        created_at: "2025-07-15T08:00:00",
    },
    {
        id: 3,
        read_at: "2025-06-30T10:00:00",
        title: "Booking Completed",
        message:
            "Thank you for celebrating your Private Event with us. We hope to see you again!",
        created_at: "2025-06-01T18:00:00",
    },
    {
        id: 4,
        read_at: "2025-05-19T14:30:00",
        title: "Booking Cancelled",
        message:
            "Your Standard Visit on May 20 has been cancelled as requested. Refund will be processed within 3–5 business days.",
        created_at: "2025-05-19T14:00:00",
    },
]);

const unreadCount = computed(
    () => notifications.value.filter((n) => !n.read_at).length,
);

const activeTab = ref("bookings");

const statusConfig = {
    pending: { label: "Pending", badge: "bg-yellow-100 text-yellow-800" },
    confirmed: { label: "Confirmed", badge: "bg-emerald-100 text-emerald-800" },
    cancelled: { label: "Cancelled", badge: "bg-red-100 text-red-800" },
    completed: { label: "Completed", badge: "bg-blue-100 text-blue-800" },
};

function getStatus(status) {
    return (
        statusConfig[status] ?? {
            label: status,
            badge: "bg-gray-100 text-gray-700",
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
    () => bookings.value.filter((b) => b.status === "confirmed").length,
);
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50">
            <!-- ── HERO BANNER ── -->
            <section
                class="relative overflow-hidden bg-gradient-to-br from-[#0d2137] via-[#1a4a6e] to-[#2e7db5] px-6 pb-16 pt-12 rounded-t-lg"
            >
                <!-- Wave decoration -->
                <div
                    class="pointer-events-none absolute bottom-0 left-0 right-0"
                >
                    <svg
                        viewBox="0 0 1200 60"
                        preserveAspectRatio="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="block h-12 w-full"
                    >
                        <path
                            d="M0,30 C200,60 400,0 600,30 C800,60 1000,0 1200,30 L1200,60 L0,60 Z"
                            fill="rgba(255,255,255,0.05)"
                        />
                        <path
                            d="M0,42 C150,15 350,55 600,38 C850,22 1050,50 1200,38 L1200,60 L0,60 Z"
                            fill="rgba(255,255,255,0.03)"
                        />
                    </svg>
                </div>

                <!-- Content -->
                <div
                    class="relative z-10 mx-auto flex flex-wrap items-center gap-2"
                >
                        <svg
                            class="w-20 h-20 fill-brass flex-shrink-0"
                            viewBox="0 0 48 48"
                        >
                            <path
                                d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                            />
                        </svg>

                    <div class="flex-1 min-w-[180px]">
                        <p
                            class="text-xs font-semibold uppercase tracking-widest text-stone-200"
                        >
                            Welcome aboard,
                        </p>
                        <h1
                            class="mt-1 text-3xl font-bold tracking-tight text-brass hover:text-gold"
                        >
                            {{ user?.name ?? "Sailor" }}
                        </h1>
                        <p class="mt-1 text-sm text-stone-400">
                            Your voyage dashboard at The Butal Ship Hauz
                        </p>
                    </div>

                    <!-- Unread alert badge -->

                    
                    <Link
                        :href="route('client.notifications')"
                        method="get"
                        as="button"
                        class="flex shrink-0 flex-col items-center rounded-md w-full sm:w-auto bg-amber-400 py-2 sm:px-6 sm:py-3 text-center shadow-lg shadow-amber-400/40"
                    >
                        
                        <span
                            class="text-2xl font-bold leading-none text-[#0d2137]"
                            >{{ unreadCount }}</span
                        >
                        <span
                            class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-[#0d2137]"
                        >
                            new alert{{ unreadCount !== 1 ? "s" : "" }}
                        </span>
                    </Link>
                </div>
            </section>

            <!-- ── STAT CARDS ── -->
            <div
                class="relative z-10 mx-auto -mt-7 grid grid-cols-2 gap-3 h-24 px-3 sm:px-6 sm:grid-cols-4"
            >
                <div
                    class="flex items-center gap-3 rounded-xl border-b-4 border-cyan-400 bg-white h-full px-4 py-4 shadow-md"
                >
                    <font-awesome-icon class="text-2xl bg-cyan-400 text-white p-2 rounded" icon="fa-solid fa-calendar" />
                    <div>
                        <p
                            class="text-xl font-bold leading-none text-[#0d2137]"
                        >
                            {{ bookings.length }}
                        </p>
                        <p
                            class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400"
                        >
                            Total Bookings
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-3 rounded-xl border-b-4 border-amber-400 bg-white h-full px-4 py-4 shadow-md"
                >
                    <font-awesome-icon class="text-2xl bg-amber-400 text-white p-2 rounded" icon="fa-solid fa-clock" />
                    <div>
                        <p
                            class="text-xl font-bold leading-none text-[#0d2137]"
                        >
                            {{ pendingCount }}
                        </p>
                        <p
                            class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400"
                        >
                            Pending
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-3 rounded-xl border-b-4 border-emerald-400 bg-white h-full px-4 py-4 shadow-md"
                >
                    <font-awesome-icon class="text-2xl bg-emerald-400 text-white p-2 rounded" icon="fa-solid fa-check" />
                    <div>
                        <p
                            class="text-xl font-bold leading-none text-[#0d2137]"
                        >
                            {{ confirmedCount }}
                        </p>
                        <p
                            class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400"
                        >
                            Confirmed
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-3 rounded-xl border-b-4 border-rose-400 bg-white h-full px-4 py-4 shadow-md"
                >
                    <font-awesome-icon class="text-2xl bg-rose-400 text-white p-2 rounded" icon="fa-solid fa-bell" />
                    <div>
                        <p
                            class="text-xl font-bold leading-none text-[#0d2137]"
                        >
                            {{ unreadCount }}
                        </p>
                        <p
                            class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-400"
                        >
                            Unread Alerts
                        </p>
                    </div>
                </div>
            </div>

            <!-- ── TABBED CONTENT ── -->
            <div class="mx-auto mt-28 sm:mt-8  px-3 sm:px-6 pb-16">
                <!-- Tab bar -->
                <div
                    class="mb-5 flex gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm"
                >
                    <button
                        class="flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-all"
                        :class="
                            activeTab === 'bookings'
                                ? 'bg-[#0d2137] text-white shadow'
                                : 'text-slate-500 hover:text-slate-700'
                        "
                        @click="activeTab = 'bookings'"
                    >
                        🗂 Recent Booking
                    </button>
                    <button
                        class="relative flex flex-1 items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium transition-all"
                        :class="
                            activeTab === 'notifications'
                                ? 'bg-[#0d2137] text-white shadow'
                                : 'text-slate-500 hover:text-slate-700'
                        "
                        @click="activeTab = 'notifications'"
                    >
                        📢 Recent Notifications
                        <span
                            v-if="unreadCount"
                            class="ml-1 rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-bold text-[#0d2137]"
                        >
                            {{ unreadCount }}
                        </span>
                    </button>
                </div>

                <!-- ── BOOKINGS PANEL ── -->
                <div v-if="activeTab === 'bookings'">
                    <!-- Empty state -->
                    <div
                        v-if="bookings.length === 0"
                        class="rounded-xl bg-white py-16 text-center shadow-sm"
                    >
                        <div class="text-5xl">🚢</div>
                        <h3 class="mt-3 text-base font-semibold text-[#0d2137]">
                            No bookings yet
                        </h3>
                        <p class="mx-auto mt-1 max-w-xs text-sm text-slate-500">
                            Ready to chart your course? Make your first
                            reservation at The Butal Ship Hauz.
                        </p>
                        <a
                            href="/bookings/create"
                            class="mt-5 inline-block rounded-full bg-[#1a4a6e] px-7 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0d2137]"
                        >
                            Book a Visit
                        </a>
                    </div>

                    <!-- Booking cards -->
                    <div v-else class="flex flex-col gap-4">
                        <div
                            v-for="booking in bookings"
                            :key="booking.id"
                            class="rounded-xl border-l-4 border-[#2e7db5] bg-white p-5 shadow-sm transition-shadow hover:shadow-md"
                        >
                            <div
                                class="flex flex-wrap items-start justify-between gap-3"
                            >
                                <div>
                                    <p
                                        class="text-[10px] font-semibold uppercase tracking-widest text-slate-400"
                                    >
                                        Ref #{{ booking.id }}
                                    </p>
                                    <p
                                        class="mt-0.5 text-base font-semibold text-[#0d2137]"
                                    >
                                        {{
                                            booking.package ??
                                            booking.event_type ??
                                            "Venue Booking"
                                        }}
                                    </p>
                                </div>
                                <span
                                    class="rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide"
                                    :class="getStatus(booking.status).badge"
                                >
                                    {{ getStatus(booking.status).label }}
                                </span>
                            </div>

                            <div
                                class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600"
                            >
                                <span class="flex items-center gap-1.5">
                                    📅 {{ formatDate(booking.check_in) }}
                                </span>
                                <span
                                    v-if="booking.check_out"
                                    class="flex items-center gap-1.5"
                                >
                                    🏁 {{ formatDate(booking.check_out) }}
                                </span>
                                <span
                                    v-if="booking.guests"
                                    class="flex items-center gap-1.5"
                                >
                                    👥 {{ booking.guests }} guest{{
                                        booking.guests !== 1 ? "s" : ""
                                    }}
                                </span>
                                <span
                                    v-if="booking.total_amount"
                                    class="flex items-center gap-1.5 font-medium text-[#1a4a6e]"
                                >
                                    💳 ₱{{
                                        Number(
                                            booking.total_amount,
                                        ).toLocaleString()
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── NOTIFICATIONS PANEL ── -->
                <div v-if="activeTab === 'notifications'">
                    <!-- Empty state -->
                    <div
                        v-if="notifications.length === 0"
                        class="rounded-xl bg-white py-16 text-center shadow-sm"
                    >
                        <div class="text-5xl">🔔</div>
                        <h3 class="mt-3 text-base font-semibold text-[#0d2137]">
                            All clear on deck
                        </h3>
                        <p class="mx-auto mt-1 max-w-xs text-sm text-slate-500">
                            No notifications right now. We'll signal you when
                            something needs your attention.
                        </p>
                    </div>

                    <!-- Notification list -->
                    <div v-else class="flex flex-col gap-3">
                        <div
                            v-for="notif in notifications"
                            :key="notif.id"
                            class="flex items-start gap-4 rounded-xl border-l-4 bg-white p-5 shadow-sm transition-shadow hover:shadow-md"
                            :class="
                                notif.read_at
                                    ? 'border-transparent'
                                    : 'border-amber-400 bg-amber-60/20'
                            "
                        >
                            <!-- Unread dot -->
                            <div class="mt-1.5 shrink-0">
                                <div
                                    v-if="!notif.read_at"
                                    class="h-2.5 w-2.5 rounded-full bg-amber-400"
                                ></div>
                                <div
                                    v-else
                                    class="h-2.5 w-2.5 rounded-full bg-slate-200"
                                ></div>
                            </div>

                            <div class="flex-1">
                                <p class="text-sm font-semibold text-[#0d2137]">
                                    {{ notif.title }}
                                </p>
                                <p class="mt-0.5 text-sm text-slate-600">
                                    {{ notif.message }}
                                </p>
                                <p class="mt-2 text-[11px] text-slate-400">
                                    {{ formatDate(notif.created_at) }} ·
                                    {{ formatTime(notif.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
