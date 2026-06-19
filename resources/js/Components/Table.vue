<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    data: { type: Array, required: true }, // renamed from `bookings`
    columns: { type: Array, required: true },
});

// --- Search & Filter ---
const searchQuery = ref("");
const filterStatus = ref("all");
const filterEvent = ref("all");
const dateFrom = ref("");
const dateTo = ref("");

// --- Pagination ---
const currentPage = ref(1);
const perPage = ref(5);
const perPageOptions = [5, 10, 25];

const uniqueEvents = computed(() => {
    const events = [...new Set(props.data.map((b) => b.event))];
    return events.sort();
});

const statusOptions = [
    { value: "all", label: "All Statuses" },
    { value: "paid", label: "Paid" },
    { value: "pending", label: "Pending" },
    { value: "confirmed", label: "Confirmed" },
    { value: "cancelled", label: "Cancelled" },
];

const filteredBookings = computed(() => {
    return props.data.filter((b) => {
        const query = searchQuery.value.toLowerCase();
        const matchesSearch =
            !query ||
            b.ref.toLowerCase().includes(query) ||
            b.event.toLowerCase().includes(query) ||
            b.package.toLowerCase().includes(query) ||
            b.payment_method.toLowerCase().includes(query) ||
            b.payment_ref.toLowerCase().includes(query);

        const matchesStatus =
            filterStatus.value === "all" || b.status === filterStatus.value;

        const matchesEvent =
            filterEvent.value === "all" || b.event === filterEvent.value;

        const bookingDate = new Date(b.date);
        const matchesFrom =
            !dateFrom.value || bookingDate >= new Date(dateFrom.value);
        const matchesTo =
            !dateTo.value || bookingDate <= new Date(dateTo.value);

        return (
            matchesSearch &&
            matchesStatus &&
            matchesEvent &&
            matchesFrom &&
            matchesTo
        );
    });
});

// Reset to page 1 when filters/search/perPage changes
watch(
    [searchQuery, filterStatus, filterEvent, dateFrom, dateTo, perPage],
    () => {
        currentPage.value = 1;
    },
);

const totalPages = computed(
    () => Math.ceil(filteredBookings.value.length / perPage.value) || 1,
);

const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    return filteredBookings.value.slice(start, start + perPage.value);
});

// Visible page numbers (max 5 shown at a time)
const visiblePages = computed(() => {
    const total = totalPages.value;
    const current = currentPage.value;
    const delta = 2;
    const pages = [];

    const start = Math.max(1, current - delta);
    const end = Math.min(total, current + delta);

    if (start > 1) pages.push(1);
    if (start > 2) pages.push("...");
    for (let i = start; i <= end; i++) pages.push(i);
    if (end < total - 1) pages.push("...");
    if (end < total) pages.push(total);

    return pages;
});

const goToPage = (page) => {
    if (typeof page === "number") currentPage.value = page;
};

const clearFilters = () => {
    searchQuery.value = "";
    filterStatus.value = "all";
    filterEvent.value = "all";
    dateFrom.value = "";
    dateTo.value = "";
};

const hasActiveFilters = computed(() => {
    return (
        searchQuery.value ||
        filterStatus.value !== "all" ||
        filterEvent.value !== "all" ||
        dateFrom.value ||
        dateTo.value
    );
});

// Range info e.g. "Showing 1–5 of 12"
const rangeStart = computed(() =>
    filteredBookings.value.length === 0
        ? 0
        : (currentPage.value - 1) * perPage.value + 1,
);
const rangeEnd = computed(() =>
    Math.min(currentPage.value * perPage.value, filteredBookings.value.length),
);
</script>
<template>
    <!-- Search & Filters -->
    <div class="space-y-2 mb-2">
        <!-- Filter Row -->
        <div class="flex flex-wrap gap-2">
            <select
                v-model="filterStatus"
                class="text-xs border border-gray-300 rounded-md py-1.5 w-full sm:w-auto focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700"
            >
                <option
                    v-for="opt in statusOptions"
                    :key="opt.value"
                    :value="opt.value"
                >
                    {{ opt.label }}
                </option>
            </select>

            <select
                v-model="filterEvent"
                class="text-xs border border-gray-300 rounded-md py-1.5 w-full sm:w-auto focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700"
            >
                <option value="all">All Events</option>
                <option
                    v-for="event in uniqueEvents"
                    :key="event"
                    :value="event"
                >
                    {{ event }}
                </option>
            </select>

            <div class="flex items-center gap-1 w-full sm:w-auto">
                <span class="text-xs text-gray-500">From</span>
                <input
                    v-model="dateFrom"
                    type="date"
                    class="text-xs border border-gray-300 rounded-md py-1.5 w-full sm:w-auto focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700"
                />
            </div>

            <div class="flex items-center gap-1 w-full sm:w-auto">
                <span class="text-xs text-gray-500">To</span>
                <input
                    v-model="dateTo"
                    type="date"
                    class="text-xs border border-gray-300 rounded-md py-1.5 w-full sm:w-auto focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700"
                />
            </div>

            <button
                v-if="hasActiveFilters"
                @click="clearFilters"
                class="text-xs text-red-500 hover:text-red-700 gap-1 px-2 py-1.5 w-full sm:w-auto rounded-md border border-red-200 hover:bg-red-50 transition-colors"
            >
                <font-awesome-icon icon="fa-solid fa-xmark" />
                Clear
            </button>
        </div>

        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-2"
        >
            <!-- Search Bar -->
            <div class="relative sm:w-2/3">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-magnifying-glass"
                        class="text-xs"
                    />
                </span>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search by reference, event, package, or payment..."
                    class="w-full pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

            <!-- Per-page selector -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500">Show</span>
                <select
                    v-model="perPage"
                    class="text-xs border border-gray-300 rounded-md py-1 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700"
                >
                    <option v-for="n in perPageOptions" :key="n" :value="n">
                        {{ n }}
                    </option>
                </select>
                <span class="text-xs text-gray-500">per page</span>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-md border border-gray-200">
        <table class="w-full text-center text-sm">
            <thead>
                <tr class="bg-navy border-b border-gold">
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap"
                    >
                        {{ col.label }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                <tr
                    v-for="row in paginatedData"
                    :key="row.id ?? row.ref"
                    class="hover:bg-gray-50 transition-colors"
                >
                    <td
                        v-for="col in columns"
                        :key="col.key"
                        class="px-3 py-2.5 border border-stone-400 text-xs whitespace-nowrap"
                    >
                        <!-- If column has a slot, let the parent render it -->
                        <slot
                            v-if="col.slot"
                            :name="col.slot"
                            :row="row"
                            :value="row[col.key]"
                        />

                        <!-- Otherwise just render the value -->
                        <span v-else>{{ row[col.key] ?? "—" }}</span>
                    </td>
                </tr>

                <!-- Empty State -->
                <tr v-if="paginatedData.length === 0">
                    <td
                        :colspan="columns.length"
                        class="py-10 text-center text-gray-400"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-calendar-xmark"
                            class="text-2xl mb-2 block mx-auto"
                        />
                        <p class="text-sm font-medium">No records found</p>
                        <p class="text-xs mt-1">
                            Try adjusting your search or filters.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div
        v-if="filteredBookings.length > 0"
        class="flex flex-col sm:flex-row items-center justify-between gap-2 mt-3"
    >
        <!-- Range info -->
        <p class="text-xs text-gray-500">
            Showing
            <span class="font-semibold text-gray-700"
                >{{ rangeStart }}–{{ rangeEnd }}</span
            >
            of
            <span class="font-semibold text-gray-700">{{
                filteredBookings.length
            }}</span>
            bookings
        </p>

        <!-- Page buttons -->
        <div class="flex items-center gap-1">
            <!-- Prev -->
            <button
                @click="currentPage--"
                :disabled="currentPage === 1"
                class="px-2 py-1 rounded-md border text-xs transition-colors"
                :class="
                    currentPage === 1
                        ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                        : 'border-gray-300 text-gray-600 hover:bg-gray-100'
                "
            >
                <font-awesome-icon icon="fa-solid fa-chevron-left" />
            </button>

            <!-- Page numbers -->
            <template v-for="(page, i) in visiblePages" :key="i">
                <span
                    v-if="page === '...'"
                    class="px-1.5 py-1 text-xs text-gray-400"
                    >…</span
                >
                <button
                    v-else
                    @click="goToPage(page)"
                    class="min-w-[28px] px-2 py-1 rounded-md border text-xs font-medium transition-colors"
                    :class="
                        page === currentPage
                            ? 'bg-navy text-gold border-navy'
                            : 'border-gray-300 text-gray-600 hover:bg-gray-100'
                    "
                >
                    {{ page }}
                </button>
            </template>

            <!-- Next -->
            <button
                @click="currentPage++"
                :disabled="currentPage === totalPages"
                class="px-2 py-1 rounded-md border text-xs transition-colors"
                :class="
                    currentPage === totalPages
                        ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                        : 'border-gray-300 text-gray-600 hover:bg-gray-100'
                "
            >
                <font-awesome-icon icon="fa-solid fa-chevron-right" />
            </button>
        </div>
    </div>
</template>
