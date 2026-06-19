<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

const user = usePage().props.auth.user;

const user_information = useForm({
    first_name: user.user_info?.first_name,
    middle_name: user.user_info?.middle_name,
    last_name: user.user_info?.last_name,
    birth_date: user.user_info?.birth_date,
    phone: user.user_info?.phone,
    address: user.user_info?.address,
});

const user_credentials = useForm({
    email: user.email,
    password: "",
    password_confirmation: "",
});

const submitInformation = () => {
    user_information.put(route("client.profile.update-information"));
};

const submitCredentials = () => {
    user_credentials.put(route("client.profile.update-credentials"), {
        onSuccess: () =>
            user_credentials.reset("password", "password_confirmation"),
    });
};

// --- Booking History ---

// Replace this with real data passed via Inertia props: defineProps({ bookings: Array })
const bookings = ref([
    {
        ref: "BSH-QYQ70ZOW",
        event: "Birthday Party",
        date: "2026-06-18",
        time: "8:00 AM – 8:00 PM",
        package: "Upper Deck Experience",
        addons: "Captain's Experience, Catering Package, Event Decoration, Photo & Video Coverage, Sound System, Guest Entry Passes",
        amount: 15500,
        payment_method: "GCash",
        payment_ref: "Ref#12312312312",
        status: "paid",
    },
    {
        ref: "BSH-XKL29MNP",
        event: "Wedding Reception",
        date: "2026-05-10",
        time: "4:00 PM – 10:00 PM",
        package: "Premium Deck Package",
        addons: "Catering Package, Event Decoration, Photo & Video Coverage",
        amount: 28000,
        payment_method: "Bank Transfer",
        payment_ref: "Ref#98765432100",
        status: "paid",
    },
    {
        ref: "BSH-RTA88QWE",
        event: "Corporate Event",
        date: "2026-07-04",
        time: "9:00 AM – 5:00 PM",
        package: "Main Hall Experience",
        addons: "Sound System, Guest Entry Passes",
        amount: 12000,
        payment_method: "GCash",
        payment_ref: "Ref#11223344556",
        status: "pending",
    },
    {
        ref: "BSH-MNB55CXZ",
        event: "Debut",
        date: "2026-08-20",
        time: "6:00 PM – 11:00 PM",
        package: "Upper Deck Experience",
        addons: "Event Decoration, Photo & Video Coverage, Sound System",
        amount: 18500,
        payment_method: "Cash",
        payment_ref: "—",
        status: "confirmed",
    },
    {
        ref: "BSH-LOP11WER",
        event: "Reunion",
        date: "2026-04-01",
        time: "10:00 AM – 4:00 PM",
        package: "Standard Package",
        addons: "Catering Package",
        amount: 7500,
        payment_method: "GCash",
        payment_ref: "Ref#55667788990",
        status: "cancelled",
    },
    {
        ref: "BSH-TYU33IOP",
        event: "Anniversary",
        date: "2026-03-15",
        time: "7:00 PM – 11:00 PM",
        package: "Sunset Deck Package",
        addons: "Event Decoration, Photo & Video Coverage",
        amount: 9800,
        payment_method: "Bank Transfer",
        payment_ref: "Ref#44332211009",
        status: "paid",
    },
    {
        ref: "BSH-GHJ77KLM",
        event: "Graduation Party",
        date: "2026-02-28",
        time: "5:00 PM – 10:00 PM",
        package: "Standard Package",
        addons: "Sound System, Photo & Video Coverage",
        amount: 8500,
        payment_method: "GCash",
        payment_ref: "Ref#77889900112",
        status: "paid",
    },
    {
        ref: "BSH-ZXC44VBN",
        event: "Birthday Party",
        date: "2025-12-10",
        time: "3:00 PM – 9:00 PM",
        package: "Main Hall Experience",
        addons: "Catering Package, Event Decoration",
        amount: 11200,
        payment_method: "Cash",
        payment_ref: "—",
        status: "paid",
    },
    {
        ref: "BSH-QAZ99WSX",
        event: "Corporate Event",
        date: "2026-09-15",
        time: "8:00 AM – 6:00 PM",
        package: "Premium Deck Package",
        addons: "Sound System, Guest Entry Passes, Photo & Video Coverage",
        amount: 22000,
        payment_method: "Bank Transfer",
        payment_ref: "Ref#33221100998",
        status: "confirmed",
    },
    {
        ref: "BSH-EDC11RFV",
        event: "Wedding Reception",
        date: "2025-11-05",
        time: "5:00 PM – 11:00 PM",
        package: "Upper Deck Experience",
        addons: "Catering Package, Event Decoration, Photo & Video Coverage, Sound System",
        amount: 31500,
        payment_method: "Bank Transfer",
        payment_ref: "Ref#66554433221",
        status: "paid",
    },
    {
        ref: "BSH-TGB22YHN",
        event: "Reunion",
        date: "2026-10-01",
        time: "11:00 AM – 5:00 PM",
        package: "Standard Package",
        addons: "Guest Entry Passes",
        amount: 6000,
        payment_method: "GCash",
        payment_ref: "Ref#99887766554",
        status: "pending",
    },
    {
        ref: "BSH-UJM55IKO",
        event: "Debut",
        date: "2025-10-20",
        time: "6:00 PM – 11:00 PM",
        package: "Sunset Deck Package",
        addons: "Event Decoration, Photo & Video Coverage, Catering Package",
        amount: 17000,
        payment_method: "GCash",
        payment_ref: "Ref#12398745600",
        status: "cancelled",
    },
]);

// --- Search & Filter ---
const searchQuery = ref("");
const filterStatus = ref("all");
const filterEvent = ref("all");
const dateFrom = ref("");
const dateTo = ref("");

// --- Pagination ---
const currentPage = ref(1);
const perPage = ref(5);
const perPageOptions = [5, 10, 25, 50];

const uniqueEvents = computed(() => {
    const events = [...new Set(bookings.value.map((b) => b.event))];
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
    return bookings.value.filter((b) => {
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

        return matchesSearch && matchesStatus && matchesEvent && matchesFrom && matchesTo;
    });
});

// Reset to page 1 when filters/search/perPage changes
watch([searchQuery, filterStatus, filterEvent, dateFrom, dateTo, perPage], () => {
    currentPage.value = 1;
});

const totalPages = computed(() => Math.ceil(filteredBookings.value.length / perPage.value) || 1);

const paginatedBookings = computed(() => {
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

const statusConfig = {
    paid:      { label: "Paid",      classes: "bg-green-100 text-green-700 border border-green-200" },
    pending:   { label: "Pending",   classes: "bg-yellow-100 text-yellow-700 border border-yellow-200" },
    confirmed: { label: "Confirmed", classes: "bg-blue-100 text-blue-700 border border-blue-200" },
    cancelled: { label: "Cancelled", classes: "bg-red-100 text-red-700 border border-red-200" },
};

const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return d.toLocaleDateString("en-PH", { year: "numeric", month: "short", day: "numeric" });
};

const formatAmount = (amount) => {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP",
        minimumFractionDigits: 0,
    }).format(amount);
};

// Range info e.g. "Showing 1–5 of 12"
const rangeStart = computed(() => filteredBookings.value.length === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1);
const rangeEnd   = computed(() => Math.min(currentPage.value * perPage.value, filteredBookings.value.length));
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="flex flex-col sm:flex-row gap-3 mx-auto">
            <!-- Left Column: Information & Credentials -->
             
            <div class="w-full sm:w-2/3">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg h-full">
                    <div class="p-6 text-gray-900">

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="sm:text-xl font-bold">
                                <font-awesome-icon icon="fa-solid fa-clock-rotate-left" />
                                Booking History
                            </h1>
                            <!-- Per-page selector -->
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500">Show</span>
                                <select
                                    v-model="perPage"
                                    class="text-xs border border-gray-300 rounded-md py-1 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700"
                                >
                                    <option v-for="n in perPageOptions" :key="n" :value="n">{{ n }}</option>
                                </select>
                                <span class="text-xs text-gray-500">per page</span>
                            </div>
                        </div>

                        <!-- Search & Filters -->
                        <div class="space-y-2 mb-2">
                            <!-- Filter Row -->
                            <div class="flex flex-wrap gap-2">
                                <select v-model="filterStatus"
                                    class="text-xs border border-gray-300 rounded-md py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700">
                                    <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">
                                        {{ opt.label }}
                                    </option>
                                </select>

                                <select v-model="filterEvent"
                                    class="text-xs border border-gray-300 rounded-md py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700">
                                    <option value="all">All Events</option>
                                    <option v-for="event in uniqueEvents" :key="event" :value="event">{{ event }}</option>
                                </select>

                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-gray-500">From</span>
                                    <input v-model="dateFrom" type="date"
                                        class="text-xs border border-gray-300 rounded-md py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700" />
                                </div>

                                <div class="flex items-center gap-1">
                                    <span class="text-xs text-gray-500">To</span>
                                    <input v-model="dateTo" type="date"
                                        class="text-xs border border-gray-300 rounded-md py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white text-gray-700" />
                                </div>

                                <button v-if="hasActiveFilters" @click="clearFilters"
                                    class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 px-2 py-1.5 rounded-md border border-red-200 hover:bg-red-50 transition-colors">
                                    <font-awesome-icon icon="fa-solid fa-xmark" />
                                    Clear
                                </button>
                            </div>

                            <!-- Search Bar -->
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                    <font-awesome-icon icon="fa-solid fa-magnifying-glass" class="text-xs" />
                                </span>
                                <input v-model="searchQuery" type="text"
                                    placeholder="Search by reference, event, package, or payment..."
                                    class="w-full pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto rounded-md border border-gray-200">
                            <table class="w-full text-center text-sm">
                                <thead>
                                    <tr class="bg-navy border-b border-gold">
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Booking Ref</th>
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Event</th>
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Date</th>
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Time</th>
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Package</th>
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Amount</th>
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Payment</th>
                                        <th class="px-3 py-3 border border-stone-400 text-xs font-semibold text-gold uppercase tracking-wider whitespace-nowrap">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                                    <tr v-for="booking in paginatedBookings" :key="booking.ref"
                                        class="hover:bg-gray-50 transition-colors">
                                        <td class="px-3 py-2.5 border border-stone-400 font-mono text-xs text-blue-900 font-semibold whitespace-nowrap">
                                            {{ booking.ref }}
                                        </td>
                                        <td class="px-3 py-2.5 border border-stone-400 text-xs text-black whitespace-nowrap">{{ booking.event }}</td>
                                        <td class="px-3 py-2.5 border border-stone-400 text-xs text-black whitespace-nowrap">{{ formatDate(booking.date) }}</td>
                                        <td class="px-3 py-2.5 border border-stone-400 text-xs text-black whitespace-nowrap">{{ booking.time }}</td>
                                        <td class="px-3 py-2.5 border border-stone-400 text-xs text-black text-left max-w-[200px]">
                                            <span class="font-medium">{{ booking.package }}</span>
                                            <span v-if="booking.addons" class="block text-gray-400 text-xs leading-tight mt-0.5">
                                                + {{ booking.addons }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2.5 border border-stone-400 text-xs font-semibold text-black whitespace-nowrap">
                                            {{ formatAmount(booking.amount) }}
                                        </td>
                                        <td class="px-3 py-2.5 border border-stone-400 text-xs whitespace-nowrap">
                                            <span class="text-black font-medium">{{ booking.payment_method }}</span>
                                            <span class="block text-gray-400 text-xs">{{ booking.payment_ref }}</span>
                                        </td>
                                        <td class="px-3 py-2.5 border border-stone-400 text-xs whitespace-nowrap">
                                            <span class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full capitalize"
                                                :class="statusConfig[booking.status]?.classes ?? 'bg-gray-100 text-gray-600'">
                                                {{ statusConfig[booking.status]?.label ?? booking.status }}
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Empty State -->
                                    <tr v-if="paginatedBookings.length === 0">
                                        <td colspan="8" class="py-10 text-center text-gray-400">
                                            <font-awesome-icon icon="fa-solid fa-calendar-xmark" class="text-2xl mb-2 block mx-auto" />
                                            <p class="text-sm font-medium">No bookings found</p>
                                            <p class="text-xs mt-1">Try adjusting your search or filters.</p>
                                            <button v-if="hasActiveFilters" @click="clearFilters"
                                                class="mt-3 text-xs text-blue-600 hover:underline">
                                                Clear all filters
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer -->
                        <div v-if="filteredBookings.length > 0"
                            class="flex flex-col sm:flex-row items-center justify-between gap-2 mt-3">

                            <!-- Range info -->
                            <p class="text-xs text-gray-500">
                                Showing <span class="font-semibold text-gray-700">{{ rangeStart }}–{{ rangeEnd }}</span>
                                of <span class="font-semibold text-gray-700">{{ filteredBookings.length }}</span> bookings
                            </p>

                            <!-- Page buttons -->
                            <div class="flex items-center gap-1">
                                <!-- Prev -->
                                <button
                                    @click="currentPage--"
                                    :disabled="currentPage === 1"
                                    class="px-2 py-1 rounded-md border text-xs transition-colors"
                                    :class="currentPage === 1
                                        ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                                        : 'border-gray-300 text-gray-600 hover:bg-gray-100'"
                                >
                                    <font-awesome-icon icon="fa-solid fa-chevron-left" />
                                </button>

                                <!-- Page numbers -->
                                <template v-for="(page, i) in visiblePages" :key="i">
                                    <span v-if="page === '...'" class="px-1.5 py-1 text-xs text-gray-400">…</span>
                                    <button v-else
                                        @click="goToPage(page)"
                                        class="min-w-[28px] px-2 py-1 rounded-md border text-xs font-medium transition-colors"
                                        :class="page === currentPage
                                            ? 'bg-navy text-gold border-navy'
                                            : 'border-gray-300 text-gray-600 hover:bg-gray-100'"
                                    >
                                        {{ page }}
                                    </button>
                                </template>

                                <!-- Next -->
                                <button
                                    @click="currentPage++"
                                    :disabled="currentPage === totalPages"
                                    class="px-2 py-1 rounded-md border text-xs transition-colors"
                                    :class="currentPage === totalPages
                                        ? 'border-gray-200 text-gray-300 cursor-not-allowed'
                                        : 'border-gray-300 text-gray-600 hover:bg-gray-100'"
                                >
                                    <font-awesome-icon icon="fa-solid fa-chevron-right" />
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Right Column: Booking History -->
            <div class="w-full sm:w-1/3">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="sm:text-xl font-bold">
                            <font-awesome-icon icon="fa-solid fa-circle-info" />
                            Information
                        </h1>
                        <form @submit.prevent="submitInformation">
                            <div class="mt-3">
                                <InputLabel for="first_name" value="First Name" />
                                <TextInput id="first_name" type="text" class="mt-1 block w-full"
                                    v-model="user_information.first_name" required autocomplete="first_name"
                                    placeholder="e.g. Alex" />
                                <InputError class="mt-2" :message="user_information.errors.first_name" />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="middle_name" value="Middle Name" />
                                <TextInput id="middle_name" type="text" class="mt-1 block w-full"
                                    v-model="user_information.middle_name" required autocomplete="middle_name"
                                    placeholder="e.g. Alexey" />
                                <InputError class="mt-2" :message="user_information.errors.middle_name" />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="last_name" value="Last Name" />
                                <TextInput id="last_name" type="text" class="mt-1 block w-full"
                                    v-model="user_information.last_name" required autocomplete="last_name"
                                    placeholder="e.g. Doe" />
                                <InputError class="mt-2" :message="user_information.errors.last_name" />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="birth_date" value="Birth Date" />
                                <TextInput id="birth_date" type="date" class="mt-1 block w-full"
                                    v-model="user_information.birth_date" required autocomplete="birth_date" />
                                <InputError class="mt-2" :message="user_information.errors.birth_date" />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="phone" value="Phone" />
                                <TextInput id="phone" type="number" class="mt-1 block w-full"
                                    v-model="user_information.phone" required autocomplete="phone"
                                    placeholder="e.g. 9123456789" />
                                <InputError class="mt-2" :message="user_information.errors.phone" />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="address" value="Address" />
                                <TextInput id="address" type="text" class="mt-1 block w-full"
                                    v-model="user_information.address" required autocomplete="address"
                                    placeholder="e.g. Pob. Talibon, Bohol" />
                                <InputError class="mt-2" :message="user_information.errors.address" />
                            </div>
                            <div class="mt-3 flex items-center justify-center">
                                <PrimaryButton :class="{ 'opacity-25': user_information.processing }"
                                    :disabled="user_information.processing || user_credentials.processing">
                                    <div class="text-sm" v-if="user_information.processing">
                                        <font-awesome-icon icon="fa-solid fa-spinner" spin />
                                    </div>
                                    <font-awesome-icon class="mx-1" icon="fa-solid fa-paper-plane" />
                                    Save Information
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-hidden bg-white mt-4 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="sm:text-xl font-bold">
                            <font-awesome-icon icon="fa-solid fa-id-card" />
                            Credentials
                        </h1>
                        <form @submit.prevent="submitCredentials">
                            <div class="mt-3">
                                <InputLabel for="email" value="Email" />
                                <TextInput id="email" type="email" class="mt-1 block w-full"
                                    v-model="user_credentials.email" required autocomplete="username"
                                    placeholder="e.g. alex@gmail.com" />
                                <InputError class="mt-2" :message="user_credentials.errors.email" />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="password" value="Password" />
                                <TextInput id="password" type="password" class="mt-1 block w-full"
                                    v-model="user_credentials.password" autocomplete="new-password" />
                                <InputError class="mt-2" :message="user_credentials.errors.password" />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="password_confirmation" value="Confirm Password" />
                                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                    v-model="user_credentials.password_confirmation" autocomplete="new-password" />
                                <InputError class="mt-2"
                                    :message="user_credentials.errors.password_confirmation" />
                            </div>
                            <div class="mt-3 flex items-center justify-center">
                                <PrimaryButton :class="{ 'opacity-25': user_credentials.processing }"
                                    :disabled="user_credentials.processing || user_information.processing">
                                    <div class="text-sm" v-if="user_credentials.processing">
                                        <font-awesome-icon icon="fa-solid fa-spinner" spin />
                                    </div>
                                    <font-awesome-icon class="me-1" icon="fa-solid fa-paper-plane" />
                                    Save Credentials
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>