<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { useFormatter } from "@/Composables/useFormatter";

const props = defineProps({
    stats: Object,
    upcomingBookings: Array,
    recentMessages: Array,
    popularPackages: Array,
});

const { formatAmount } = useFormatter();

const maxPackage = computed(() =>
    Math.max(...props.popularPackages.map((p) => p.count)),
);

const statusStyles = {
    confirmed: "bg-emerald-50 text-emerald-700 border-emerald-200",
    pending: "bg-amber-50 text-amber-700 border-amber-200",
    cancelled: "bg-red-50 text-red-700 border-red-200",
};
</script>

<template>
    <AdminLayout>
        <div class="space-y-3">
            <!-- Header strip -->
            <div
                class="flex flex-col gap-1 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <p
                        class="font-mono text-[11px] uppercase tracking-[.18em] text-brass"
                    >
                        Dashboard &mdash;
                        {{
                            new Date().toLocaleDateString("en-PH", {
                                weekday: "long",
                                month: "long",
                                day: "numeric",
                            })
                        }}
                    </p>
                    <h1 class="font-display text-2xl font-semibold text-navy">
                        Welcome back, Admin
                    </h1>
                </div>
                <Link
                    :href="route('admin.bookings.index')"
                    class="inline-flex w-fit items-center gap-2 rounded-md bg-navy px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800"
                >
                    <font-awesome-icon icon="fa-solid fa-book-bookmark" />
                    View all bookings
                </Link>
            </div>

            <!-- Stat plaques -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <div class="absolute inset-y-0 left-0 w-1 bg-brass"></div>
                    <p
                        class="font-mono text-[11px] uppercase tracking-[.14em] text-slate-400"
                    >
                        Total Bookings
                    </p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="font-display text-3xl font-semibold text-navy"
                            >{{ stats.totalBookings }}</span
                        >
                        <span
                            :class="
                                stats.bookingsDelta >= 0
                                    ? 'text-emerald-600'
                                    : 'text-red-500'
                            "
                            class="text-xs font-semibold"
                        >
                            <font-awesome-icon
                                :icon="
                                    stats.bookingsDelta >= 0
                                        ? 'fa-solid fa-arrow-up'
                                        : 'fa-solid fa-arrow-down'
                                "
                                class="text-[10px]"
                            />
                            {{ Math.abs(stats.bookingsDelta) }}%
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">vs. last month</p>
                </div>

                <div
                    class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <div class="absolute inset-y-0 left-0 w-1 bg-brass"></div>
                    <p
                        class="font-mono text-[11px] uppercase tracking-[.14em] text-slate-400"
                    >
                        Revenue This Month
                    </p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="font-display text-3xl font-semibold text-navy"
                            >{{ formatAmount(stats.revenueThisMonth) }}</span
                        >
                        <span
                            :class="
                                stats.revenueDelta >= 0
                                    ? 'text-emerald-600'
                                    : 'text-red-500'
                            "
                            class="text-xs font-semibold"
                        >
                            <font-awesome-icon
                                :icon="
                                    stats.revenueDelta >= 0
                                        ? 'fa-solid fa-arrow-up'
                                        : 'fa-solid fa-arrow-down'
                                "
                                class="text-[10px]"
                            />
                            {{ Math.abs(stats.revenueDelta) }}%
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">vs. last month</p>
                </div>

                <div
                    class="relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <div class="absolute inset-y-0 left-0 w-1 bg-brass"></div>
                    <p
                        class="font-mono text-[11px] uppercase tracking-[.14em] text-slate-400"
                    >
                        New Clients
                    </p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="font-display text-3xl font-semibold text-navy"
                            >{{ stats.newClients }}</span
                        >
                        <span
                            :class="
                                stats.clientsDelta >= 0
                                    ? 'text-emerald-600'
                                    : 'text-red-500'
                            "
                            class="text-xs font-semibold"
                        >
                            <font-awesome-icon
                                :icon="
                                    stats.clientsDelta >= 0
                                        ? 'fa-solid fa-arrow-up'
                                        : 'fa-solid fa-arrow-down'
                                "
                                class="text-[10px]"
                            />
                            {{ Math.abs(stats.clientsDelta) }}%
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">vs. last month</p>
                </div>

                <Link
                    :href="route('admin.messages.index')"
                    class="relative overflow-hidden rounded-lg border border-slate-200 bg-navy p-5 shadow-sm transition hover:bg-slate-800"
                >
                    <div class="absolute inset-y-0 left-0 w-1 bg-brass"></div>
                    <p
                        class="font-mono text-[11px] uppercase tracking-[.14em] text-brass"
                    >
                        Unread Messages
                    </p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span
                            class="font-display text-3xl font-semibold text-white"
                            >{{ stats.unreadMessages }}</span
                        >
                        <font-awesome-icon
                            icon="fa-solid fa-envelope"
                            class="text-white/40"
                        />
                    </div>
                    <p class="mt-1 text-xs text-slate-400">awaiting reply</p>
                </Link>
            </div>

            <!-- Main grid -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Manifest: upcoming bookings -->
                <div
                    class="lg:col-span-2 rounded-lg border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center justify-between border-b border-slate-200 px-5 py-4"
                    >
                        <div>
                            <h2
                                class="font-display text-base font-semibold text-navy"
                            >
                                Upcoming Manifest
                            </h2>
                            <p
                                class="font-mono text-[11px] uppercase tracking-[.12em] text-slate-400"
                            >
                                Next bookings on the calendar
                            </p>
                        </div>
                        <Link
                            :href="route('admin.bookings.index')"
                            class="text-xs font-medium text-brass hover:text-gold"
                        >
                            See all
                            <font-awesome-icon
                                icon="fa-solid fa-arrow-right"
                                class="text-[10px]"
                            />
                        </Link>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <div
                            v-for="booking in upcomingBookings"
                            :key="booking.id"
                            class="flex items-center gap-4 px-5 py-3.5 transition hover:bg-slate-50"
                        >
                            <div
                                class="flex w-14 flex-shrink-0 flex-col items-center rounded-md border border-slate-200 bg-slate-50 py-1.5"
                            >
                                <span
                                    class="font-mono text-[10px] uppercase text-slate-400"
                                >
                                    {{
                                        new Date(
                                            booking.date,
                                        ).toLocaleDateString("en-PH", {
                                            month: "short",
                                        })
                                    }}
                                </span>
                                <span
                                    class="font-display text-lg font-semibold text-navy"
                                >
                                    {{ new Date(booking.date).getDate() }}
                                </span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <p
                                    class="truncate text-sm font-medium text-slate-800"
                                >
                                    {{ booking.client }}
                                </p>
                                <p class="truncate text-xs text-slate-400">
                                    {{ booking.eventType }} &middot;
                                    {{ booking.package }} &middot;
                                    {{ booking.pax }} pax
                                </p>
                            </div>

                            <span
                                class="flex-shrink-0 rounded-full border px-2.5 py-1 text-[11px] font-medium capitalize"
                                :class="statusStyles[booking.status]"
                            >
                                {{ booking.status }}
                            </span>
                        </div>

                        <div
                            v-if="!upcomingBookings.length"
                            class="px-5 py-10 text-center"
                        >
                            <p class="text-sm text-slate-400">
                                No bookings on the horizon yet.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Side column -->
                <div class="space-y-6">
                    <!-- Recent messages -->
                    <div
                        class="rounded-lg border border-slate-200 bg-white shadow-sm"
                    >
                        <div
                            class="flex items-center justify-between border-b border-slate-200 px-5 py-4"
                        >
                            <h2
                                class="font-display text-base font-semibold text-navy"
                            >
                                Recent Messages
                            </h2>
                            <Link
                                :href="route('admin.messages.index')"
                                class="text-xs font-medium text-brass hover:text-gold"
                            >
                                Inbox
                                <font-awesome-icon
                                    icon="fa-solid fa-arrow-right"
                                    class="text-[10px]"
                                />
                            </Link>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div
                                v-for="(msg, i) in recentMessages"
                                :key="i"
                                class="flex items-start gap-3 px-5 py-3"
                            >
                                <span
                                    class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full"
                                    :class="
                                        msg.unread ? 'bg-brass' : 'bg-slate-200'
                                    "
                                />
                                <div class="min-w-0 flex-1">
                                    <div
                                        class="flex items-baseline justify-between gap-2"
                                    >
                                        <p
                                            class="truncate text-sm font-medium text-slate-800"
                                        >
                                            {{ msg.client }}
                                        </p>
                                        <span
                                            class="flex-shrink-0 text-[11px] text-slate-400"
                                            >{{ msg.time }}</span
                                        >
                                    </div>
                                    <p class="truncate text-xs text-slate-400">
                                        {{ msg.preview }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Popular packages -->
                    <div
                        class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm"
                    >
                        <h2
                            class="font-display text-base font-semibold text-navy"
                        >
                            Popular Packages
                        </h2>
                        <p
                            class="mb-4 font-mono text-[11px] uppercase tracking-[.12em] text-slate-400"
                        >
                            By bookings this quarter
                        </p>
                        <div class="space-y-3">
                            <div v-for="pkg in popularPackages" :key="pkg.name">
                                <div
                                    class="mb-1 flex items-center justify-between text-xs"
                                >
                                    <span class="font-medium text-slate-700">{{
                                        pkg.name
                                    }}</span>
                                    <span class="text-slate-400">{{
                                        pkg.count
                                    }}</span>
                                </div>
                                <div
                                    class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100"
                                >
                                    <div
                                        class="h-full rounded-full bg-brass"
                                        :style="{
                                            width:
                                                (pkg.count / maxPackage) * 100 +
                                                '%',
                                        }"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
