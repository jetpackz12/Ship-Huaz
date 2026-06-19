<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CalendarPicker from "@/Components/CalendarPicker.vue";
import Table from "@/Components/Table.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { useFormatter } from "@/Composables/useFormatter";

// ─── View Mode ─────────────────────────────────────────────────────────────
const showForm = ref(false);

const tableColumns = [
    { key: "ref", label: "Booking Ref", slot: "ref" },
    { key: "event", label: "Event" },
    { key: "date", label: "Date", slot: "date" },
    { key: "time", label: "Time" },
    { key: "package", label: "Package", slot: "package" },
    { key: "amount", label: "Amount", slot: "amount" },
    { key: "payment_method", label: "Payment", slot: "payment" },
    { key: "status", label: "Status", slot: "status" },
];

// ─── Table: Default Data ───────────────────────────────────────────────────
const tableData = ref([
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

const tableActions = {
    isDateFilterShow: false,
    isPerPageShow: false,
    isSearchShow: true,
};

// ─── Step Management ───────────────────────────────────────────────────────
const currentStep = ref(1);
const totalSteps = 6;

const steps = [
    { id: 1, label: "Event Date", icon: "📅" },
    { id: 2, label: "Packages", icon: "⚓" },
    { id: 3, label: "Contact", icon: "👤" },
    { id: 4, label: "Review", icon: "📋" },
    { id: 5, label: "Payment", icon: "💳" },
    { id: 6, label: "Confirmed", icon: "✅" },
];

const goToStep = (step) => {
    if (step >= 1 && step <= totalSteps) currentStep.value = step;
};
const nextStep = () => goToStep(currentStep.value + 1);
const prevStep = () => goToStep(currentStep.value - 1);

// ─── Step 1: Event Date & Time ─────────────────────────────────────────────
const eventDate = ref("");
const timeSlot = ref("");
const today = new Date().toISOString().split("T")[0];

const timeSlots = [
    { id: "morning", label: "Morning", time: "8:00 AM – 12:00 PM", icon: "🌅" },
    {
        id: "afternoon",
        label: "Afternoon",
        time: "1:00 PM – 5:00 PM",
        icon: "☀️",
    },
    { id: "evening", label: "Evening", time: "6:00 PM – 10:00 PM", icon: "🌙" },
    { id: "fullday", label: "Full Day", time: "8:00 AM – 8:00 PM", icon: "⚓" },
    {
        id: "overnight",
        label: "Overnight",
        time: "8:00 PM – 8:00 AM",
        icon: "🌟",
    },
];

const eventTypes = [
    "Birthday Party",
    "Wedding Reception",
    "Debut / 18th Birthday",
    "Corporate Event",
    "Team Building",
    "Photo / Video Shoot",
    "Anniversary Celebration",
    "Other",
];
const eventType = ref("");

const step1Valid = computed(
    () => eventDate.value && timeSlot.value && eventType.value,
);

// ─── Step 2: Venue Packages & Add-ons ─────────────────────────────────────
const packages = [
    {
        id: "deck",
        name: "Upper Deck Experience",
        desc: "Open-air upper deck with panoramic views. Complimentary coffee/juice included.",
        price: 3500,
        capacity: "Up to 30 guests",
        icon: "🌊",
    },
    {
        id: "main",
        name: "Main Deck Venue",
        desc: "Spacious main deck area perfect for celebrations and gatherings.",
        price: 6500,
        capacity: "Up to 80 guests",
        icon: "⚓",
    },
    {
        id: "fullship",
        name: "Full Ship Exclusive",
        desc: "Exclusive use of the entire Butal Ship Hauz — all decks, all areas.",
        price: 12000,
        capacity: "Up to 150 guests",
        icon: "🚢",
    },
    {
        id: "overnight",
        name: "Overnight Stay Package",
        desc: "Private overnight booking for intimate gatherings or special celebrations.",
        price: 18000,
        capacity: "Up to 20 guests",
        icon: "🌟",
    },
];

const addons = [
    {
        id: "captain",
        name: "Captain's Experience",
        desc: "Uniforms + immersive ship photo ops for all guests",
        price: 500,
        icon: "👨‍✈️",
    },
    {
        id: "catering",
        name: "Catering Package",
        desc: "Filipino-style buffet with drinks for your group",
        price: 3500,
        icon: "🍽️",
    },
    {
        id: "decor",
        name: "Event Decoration",
        desc: "Themed ship décor, balloons, and table setup",
        price: 2000,
        icon: "🎊",
    },
    {
        id: "photo",
        name: "Photo & Video Coverage",
        desc: "Professional photographer for the duration of event",
        price: 4500,
        icon: "📸",
    },
    {
        id: "sound",
        name: "Sound System",
        desc: "PA system with wireless microphone and playlist setup",
        price: 1500,
        icon: "🎵",
    },
    {
        id: "entrypass",
        name: "Guest Entry Passes",
        desc: "₱50/head tour access for walk-in attendees",
        price: 50,
        perHead: true,
        icon: "🎟️",
    },
];

const selectedPackage = ref(null);
const selectedAddons = ref([]);
const walkInGuests = ref(0);

const toggleAddon = (id) => {
    const idx = selectedAddons.value.indexOf(id);
    idx === -1
        ? selectedAddons.value.push(id)
        : selectedAddons.value.splice(idx, 1);
};

const selectedPackageData = computed(() =>
    packages.find((p) => p.id === selectedPackage.value),
);
const selectedAddonData = computed(() =>
    addons.filter((a) => selectedAddons.value.includes(a.id)),
);

const packageTotal = computed(() => selectedPackageData.value?.price ?? 0);
const addonTotal = computed(() =>
    selectedAddonData.value.reduce((sum, a) => {
        if (a.perHead) return sum + a.price * walkInGuests.value;
        return sum + a.price;
    }, 0),
);
const grandTotal = computed(() => packageTotal.value + addonTotal.value);

const step2Valid = computed(() => !!selectedPackage.value);

// ─── Step 3: Contact Information ───────────────────────────────────────────
const contact = ref({
    firstName: "",
    lastName: "",
    email: "",
    phone: "",
    guestCount: 20,
    requests: "",
});

const step3Valid = computed(
    () =>
        contact.value.firstName &&
        contact.value.lastName &&
        contact.value.email &&
        contact.value.phone,
);

// ─── Step 5: Payment ───────────────────────────────────────────────────────
const payment = ref({
    method: "gcash",
    accountNumber: "",
    transactionNumber: "",
});

const step5Valid = computed(() => {
    if (
        payment.value.method === "gcash" ||
        payment.value.method === "paymaya"
    ) {
        return (
            payment.value.accountNumber.trim() !== "" &&
            payment.value.transactionNumber.trim() !== ""
        );
    }
    return true;
});

// ─── Confirmation ──────────────────────────────────────────────────────────
const reservationCode = ref("");

const confirmReservation = () => {
    reservationCode.value =
        "BSH-" + Math.random().toString(36).substr(2, 8).toUpperCase();

    // Add to table
    const paymentLabel =
        payment.value.method === "gcash"
            ? "GCash"
            : payment.value.method === "paymaya"
              ? "Maya"
              : "Pay at Venue";

    nextStep();
};

// ─── Reset Form ────────────────────────────────────────────────────────────
const resetForm = () => {
    currentStep.value = 1;
    eventDate.value = "";
    timeSlot.value = "";
    eventType.value = "";
    selectedPackage.value = null;
    selectedAddons.value = [];
    walkInGuests.value = 0;
    contact.value = {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
        guestCount: 20,
        requests: "",
    };
    payment.value = {
        method: "gcash",
        accountNumber: "",
        transactionNumber: "",
    };
    reservationCode.value = "";
    showForm.value = false;
};

// ─── Helpers ───────────────────────────────────────────────────────────────
const fmt = (n) => "₱" + n.toLocaleString("en-PH");

const fmtDate = (d) =>
    d
        ? new Date(d + "T00:00:00").toLocaleDateString("en-PH", {
              weekday: "long",
              month: "long",
              day: "numeric",
              year: "numeric",
          })
        : "—";

const selectedTimeSlotData = computed(() =>
    timeSlots.find((t) => t.id === timeSlot.value),
);

const statusConfig = {
    paid: {
        label: "Paid",
        classes: "bg-green-100 text-green-700 border border-green-200",
    },
    pending: {
        label: "Pending",
        classes: "bg-yellow-100 text-yellow-700 border border-yellow-200",
    },
    confirmed: {
        label: "Confirmed",
        classes: "bg-blue-100 text-blue-700 border border-blue-200",
    },
    cancelled: {
        label: "Cancelled",
        classes: "bg-red-100 text-red-700 border border-red-200",
    },
};

const { formatDate, formatAmount } = useFormatter();
</script>

<template>
    <Head title="Event Booking – Butal Ship Hauz" />

    <AuthenticatedLayout>
        <div class="min-h-screen">
            <!-- ══════════════════════════════════════════════════════════ -->
            <!-- BOOKINGS TABLE VIEW                                        -->
            <!-- ══════════════════════════════════════════════════════════ -->
            <div v-if="!showForm">
                <!-- Header Row -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4"
                >
                    <div>
                        <h1 class="text-xl font-bold text-stone-800">
                            Event Bookings
                        </h1>
                        <p class="text-sm text-stone-500">
                            Manage all Butal Ship Hauz reservations
                        </p>
                    </div>
                    <button
                        @click="
                            showForm = true;
                            currentStep = 1;
                        "
                        class="flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow transition-colors"
                    >
                        <font-awesome-icon icon="fa-solid fa-plus-circle" />
                        Add Booking
                    </button>
                </div>

                <!-- Table -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden px-4 py-4"
                >
                    <Table
                        :data="tableData"
                        :columns="tableColumns"
                        :actions="tableActions"
                    >
                        <template #ref="{ value }">
                            <span
                                class="font-mono text-xs text-blue-900 font-semibold"
                                >{{ value }}</span
                            >
                        </template>

                        <template #date="{ value }">
                            {{ formatDate(value) }}
                        </template>

                        <template #package="{ row }">
                            <span class="font-medium">{{ row.package }}</span>
                            <span
                                v-if="row.addons"
                                class="block text-wrap text-gray-400 text-xs mt-0.5"
                            >
                                + {{ row.addons }}
                            </span>
                        </template>

                        <template #amount="{ value }">
                            {{ formatAmount(value) }}
                        </template>

                        <template #payment="{ row }">
                            <span class="font-medium">{{
                                row.payment_method
                            }}</span>
                            <span class="block text-gray-400 text-xs">{{
                                row.payment_ref
                            }}</span>
                        </template>

                        <template #status="{ value }">
                            <span
                                class="inline-block text-xs font-semibold px-2.5 py-0.5 rounded-full capitalize"
                                :class="
                                    statusConfig[value]?.classes ??
                                    'bg-gray-100 text-gray-600'
                                "
                            >
                                {{ statusConfig[value]?.label ?? value }}
                            </span>
                        </template>
                    </Table>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════ -->
            <!-- BOOKING FORM VIEW                                          -->
            <!-- ══════════════════════════════════════════════════════════ -->
            <div v-if="showForm">
                <!-- Back to Table (only while filling form, not on confirmed) -->
                <div v-if="currentStep < 6" class="mb-4">
                    <button
                        @click="
                            showForm = false;
                            currentStep = 1;
                        "
                        class="flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-800 font-medium transition-colors"
                    >
                        <font-awesome-icon icon="fa-solid fa-angles-left" />
                        Back to Bookings
                    </button>
                </div>

                <!-- Step Indicator -->
                <div
                    class="bg-white py-4 px-2 rounded-2xl mb-4"
                    v-if="currentStep < 6"
                >
                    <div class="max-w-7xl mx-auto">
                        <div
                            class="flex items-center justify-between px-2"
                            v-if="currentStep < 6"
                        >
                            <template
                                v-for="(step, i) in steps.slice(0, 5)"
                                :key="step.id"
                            >
                                <button
                                    @click="
                                        step.id < currentStep &&
                                        goToStep(step.id)
                                    "
                                    :class="[
                                        'flex flex-col items-center gap-1 focus:outline-none',
                                        step.id < currentStep
                                            ? 'cursor-pointer'
                                            : 'cursor-default',
                                    ]"
                                >
                                    <div
                                        :class="[
                                            'w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-200',
                                            step.id === currentStep
                                                ? 'bg-blue-700 border-blue-700 text-white shadow-md scale-110'
                                                : step.id < currentStep
                                                  ? 'bg-blue-900 border-blue-900 text-white'
                                                  : 'bg-white border-stone-300 text-stone-400',
                                        ]"
                                    >
                                        <span v-if="step.id < currentStep"
                                            >✓</span
                                        >
                                        <span v-else>{{ step.id }}</span>
                                    </div>
                                    <span
                                        :class="[
                                            'text-xs font-medium hidden sm:block',
                                            step.id === currentStep
                                                ? 'text-blue-700'
                                                : step.id < currentStep
                                                  ? 'text-blue-900'
                                                  : 'text-stone-400',
                                        ]"
                                        >{{ step.label }}</span
                                    >
                                </button>
                                <div
                                    v-if="i < 4"
                                    :class="[
                                        'flex-1 h-0.5 mx-1 transition-all duration-300',
                                        step.id < currentStep
                                            ? 'bg-blue-900'
                                            : 'bg-stone-200',
                                    ]"
                                />
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Card Wrapper -->
                <div
                    class="bg-white rounded-2xl shadow-sm border border-stone-100 overflow-hidden"
                >
                    <!-- ── STEP 1: EVENT DATE & TIME ──────────────────────── -->
                    <div v-if="currentStep === 1" class="p-8">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl">📅</span>
                            <h2 class="text-xl font-bold text-stone-800">
                                Event Date & Time
                            </h2>
                        </div>
                        <p class="text-sm text-stone-500 mb-8 ml-7">
                            Select the date and time slot for your event at
                            Butal Ship Hauz.
                        </p>

                        <div class="mb-6">
                            <label
                                class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                >Event Date</label
                            >
                            <CalendarPicker v-model="eventDate" :min="today" />
                        </div>

                        <div class="mb-6">
                            <label
                                class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-3"
                                >Time Slot</label
                            >
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"
                            >
                                <button
                                    v-for="slot in timeSlots"
                                    :key="slot.id"
                                    @click="timeSlot = slot.id"
                                    :class="[
                                        'text-left border-2 rounded-xl p-4 transition-all duration-150',
                                        timeSlot === slot.id
                                            ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-100'
                                            : 'border-stone-200 hover:border-stone-300',
                                    ]"
                                >
                                    <span class="text-xl block mb-1">{{
                                        slot.icon
                                    }}</span>
                                    <p
                                        class="font-semibold text-stone-800 text-sm"
                                    >
                                        {{ slot.label }}
                                    </p>
                                    <p class="text-xs text-stone-500 mt-0.5">
                                        {{ slot.time }}
                                    </p>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                >Event Type</label
                            >
                            <select
                                v-model="eventType"
                                class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                            >
                                <option value="" disabled>
                                    Select event type…
                                </option>
                                <option
                                    v-for="type in eventTypes"
                                    :key="type"
                                    :value="type"
                                >
                                    {{ type }}
                                </option>
                            </select>
                        </div>

                        <div
                            v-if="eventDate && timeSlot && eventType"
                            class="mt-6 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 flex items-center gap-3"
                        >
                            <span class="text-2xl">⚓</span>
                            <div>
                                <p class="text-sm font-semibold text-stone-800">
                                    {{ eventType }}
                                </p>
                                <p class="text-xs text-stone-500">
                                    {{ fmtDate(eventDate) }} &bull;
                                    {{ selectedTimeSlotData?.time }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 2: PACKAGES ────────────────────────────────── -->
                    <div v-if="currentStep === 2" class="p-8">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl">⚓</span>
                            <h2 class="text-xl font-bold text-stone-800">
                                Venue Package
                            </h2>
                        </div>
                        <p class="text-sm text-stone-500 mb-6 ml-7">
                            Choose a venue package for your event.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                            <button
                                v-for="pkg in packages"
                                :key="pkg.id"
                                @click="selectedPackage = pkg.id"
                                :class="[
                                    'text-left border-2 rounded-xl p-5 transition-all duration-150',
                                    selectedPackage === pkg.id
                                        ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-100'
                                        : 'border-stone-200 hover:border-stone-300',
                                ]"
                            >
                                <div
                                    class="flex justify-between items-start mb-2"
                                >
                                    <span class="text-2xl">{{ pkg.icon }}</span>
                                    <span
                                        v-if="selectedPackage === pkg.id"
                                        class="text-blue-700 text-xs font-bold bg-blue-100 px-2 py-0.5 rounded-full"
                                        >Selected</span
                                    >
                                </div>
                                <p class="font-semibold text-stone-800 text-sm">
                                    {{ pkg.name }}
                                </p>
                                <p class="text-xs text-stone-500 mt-1 mb-1">
                                    {{ pkg.desc }}
                                </p>
                                <p class="text-xs text-stone-400 mb-3">
                                    👥 {{ pkg.capacity }}
                                </p>
                                <p class="text-blue-700 font-bold text-sm">
                                    {{ fmt(pkg.price)
                                    }}<span class="text-stone-400 font-normal"
                                        >/event</span
                                    >
                                </p>
                            </button>
                        </div>

                        <div class="border-t border-stone-100 pt-6">
                            <p
                                class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-4"
                            >
                                Add-ons
                                <span
                                    class="normal-case font-normal text-stone-400"
                                    >(optional)</span
                                >
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button
                                    v-for="addon in addons"
                                    :key="addon.id"
                                    @click="toggleAddon(addon.id)"
                                    :class="[
                                        'text-left border-2 rounded-xl p-4 transition-all duration-150',
                                        selectedAddons.includes(addon.id)
                                            ? 'border-blue-600 bg-blue-50'
                                            : 'border-stone-200 hover:border-stone-300',
                                    ]"
                                >
                                    <div class="flex items-start gap-3">
                                        <span class="text-xl flex-shrink-0">{{
                                            addon.icon
                                        }}</span>
                                        <div class="min-w-0 flex-1">
                                            <p
                                                class="text-xs font-semibold text-stone-800"
                                            >
                                                {{ addon.name }}
                                            </p>
                                            <p
                                                class="text-xs text-stone-500 mt-0.5 leading-relaxed"
                                            >
                                                {{ addon.desc }}
                                            </p>
                                            <p
                                                class="text-xs text-blue-700 font-bold mt-1"
                                            >
                                                {{ fmt(addon.price)
                                                }}<span
                                                    v-if="addon.perHead"
                                                    class="text-stone-400 font-normal"
                                                    >/head</span
                                                >
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <div
                                v-if="selectedAddons.includes('entrypass')"
                                class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4"
                            >
                                <p
                                    class="text-xs font-semibold text-stone-600 mb-3"
                                >
                                    Number of walk-in guests (₱50/head)
                                </p>
                                <div class="flex items-center gap-3">
                                    <button
                                        @click="
                                            walkInGuests = Math.max(
                                                0,
                                                walkInGuests - 1,
                                            )
                                        "
                                        class="w-10 h-10 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 font-bold"
                                    >
                                        −
                                    </button>
                                    <span
                                        class="text-stone-800 font-semibold w-8 text-center"
                                        >{{ walkInGuests }}</span
                                    >
                                    <button
                                        @click="walkInGuests++"
                                        class="w-10 h-10 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 font-bold"
                                    >
                                        +
                                    </button>
                                    <span class="text-sm text-stone-500 ml-1"
                                        >= {{ fmt(walkInGuests * 50) }}</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 3: CONTACT INFORMATION ────────────────────── -->
                    <div v-if="currentStep === 3" class="p-8">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl">👤</span>
                            <h2 class="text-xl font-bold text-stone-800">
                                Contact Information
                            </h2>
                        </div>
                        <p class="text-sm text-stone-500 mb-8 ml-7">
                            Who should we coordinate with for this event?
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >First Name *</label
                                >
                                <input
                                    v-model="contact.firstName"
                                    type="text"
                                    placeholder="Juan"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Last Name *</label
                                >
                                <input
                                    v-model="contact.lastName"
                                    type="text"
                                    placeholder="dela Cruz"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Email *</label
                                >
                                <input
                                    v-model="contact.email"
                                    type="email"
                                    placeholder="juan@email.com"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Phone *</label
                                >
                                <input
                                    v-model="contact.phone"
                                    type="tel"
                                    placeholder="+63 9XX XXX XXXX"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Expected Number of Guests</label
                                >
                                <div class="flex items-center gap-3">
                                    <button
                                        @click="
                                            contact.guestCount = Math.max(
                                                1,
                                                contact.guestCount - 5,
                                            )
                                        "
                                        class="w-10 h-10 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 font-bold"
                                    >
                                        −
                                    </button>
                                    <span
                                        class="text-stone-800 font-semibold w-10 text-center"
                                        >{{ contact.guestCount }}</span
                                    >
                                    <button
                                        @click="contact.guestCount += 5"
                                        class="w-10 h-10 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 font-bold"
                                    >
                                        +
                                    </button>
                                    <span class="text-xs text-stone-400 ml-1"
                                        >guests (approx.)</span
                                    >
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Special Requests or Notes</label
                                >
                                <textarea
                                    v-model="contact.requests"
                                    rows="3"
                                    placeholder="Specific setup, dietary needs, theme requests, accessibility concerns…"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- ── STEP 4: REVIEW ─────────────────────────────────── -->
                    <div v-if="currentStep === 4" class="p-8">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl">📋</span>
                            <h2 class="text-xl font-bold text-stone-800">
                                Review Your Booking
                            </h2>
                        </div>
                        <p class="text-sm text-stone-500 mb-8 ml-7">
                            Please confirm all details before proceeding to
                            payment.
                        </p>

                        <div
                            class="bg-gradient-to-r from-blue-900 to-slate-700 rounded-xl px-5 py-4 mb-5 flex items-center gap-3 text-white"
                        >
                            <span class="text-3xl">🚢</span>
                            <div>
                                <p class="font-bold text-sm">Butal Ship Hauz</p>
                                <p class="text-blue-200 text-xs">
                                    Talibon, Bohol
                                </p>
                            </div>
                        </div>

                        <div class="bg-stone-100 rounded-xl p-5 mb-5">
                            <p
                                class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-3"
                            >
                                Event Details
                            </p>
                            <div class="grid grid-cols-2 gap-y-2 text-sm">
                                <span class="text-stone-700">Event Type</span>
                                <span
                                    class="font-semibold text-stone-800 text-right"
                                    >{{ eventType }}</span
                                >
                                <span class="text-stone-700">Date</span>
                                <span
                                    class="font-semibold text-stone-800 text-right"
                                    >{{ fmtDate(eventDate) }}</span
                                >
                                <span class="text-stone-700">Time Slot</span>
                                <span
                                    class="font-semibold text-stone-800 text-right"
                                    >{{ selectedTimeSlotData?.label }}
                                    <span
                                        class="font-normal text-stone-400 text-xs"
                                        >({{
                                            selectedTimeSlotData?.time
                                        }})</span
                                    ></span
                                >
                                <span class="text-stone-700"
                                    >Expected Guests</span
                                >
                                <span
                                    class="font-semibold text-stone-800 text-right"
                                    >~{{ contact.guestCount }} guests</span
                                >
                            </div>
                        </div>

                        <div class="bg-stone-100 rounded-xl p-5 mb-5">
                            <p
                                class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-3"
                            >
                                Package
                            </p>
                            <div
                                class="flex justify-between items-center text-sm"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="text-xl">{{
                                        selectedPackageData?.icon
                                    }}</span>
                                    <div>
                                        <p class="font-semibold text-stone-800">
                                            {{ selectedPackageData?.name }}
                                        </p>
                                        <p class="text-stone-700 text-xs">
                                            {{ selectedPackageData?.capacity }}
                                        </p>
                                    </div>
                                </div>
                                <span class="font-bold text-stone-800">{{
                                    fmt(packageTotal)
                                }}</span>
                            </div>
                        </div>

                        <div
                            v-if="selectedAddonData.length"
                            class="bg-stone-100 rounded-xl p-5 mb-5"
                        >
                            <p
                                class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-3"
                            >
                                Add-ons
                            </p>
                            <div
                                v-for="addon in selectedAddonData"
                                :key="addon.id"
                                class="flex justify-between text-sm py-1"
                            >
                                <span class="text-stone-700"
                                    >{{ addon.icon }} {{ addon.name }}</span
                                >
                                <span class="font-semibold text-stone-800">{{
                                    addon.perHead
                                        ? fmt(addon.price * walkInGuests)
                                        : fmt(addon.price)
                                }}</span>
                            </div>
                        </div>

                        <div class="bg-stone-100 rounded-xl p-5 mb-5">
                            <p
                                class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-3"
                            >
                                Contact Person
                            </p>
                            <div class="grid grid-cols-2 gap-y-2 text-sm">
                                <span class="text-stone-700">Name</span>
                                <span
                                    class="font-semibold text-stone-800 text-right"
                                    >{{ contact.firstName }}
                                    {{ contact.lastName }}</span
                                >
                                <span class="text-stone-700">Email</span>
                                <span
                                    class="font-semibold text-stone-800 text-right truncate"
                                    >{{ contact.email }}</span
                                >
                                <span class="text-stone-700">Phone</span>
                                <span
                                    class="font-semibold text-stone-800 text-right"
                                    >{{ contact.phone }}</span
                                >
                            </div>
                        </div>

                        <div
                            class="border-t-2 border-stone-200 pt-4 flex justify-between items-center"
                        >
                            <span class="font-bold text-stone-800">Total</span>
                            <span class="text-2xl font-bold text-blue-700">{{
                                fmt(grandTotal)
                            }}</span>
                        </div>
                    </div>

                    <!-- ── STEP 5: PAYMENT ─────────────────────────────────── -->
                    <div v-if="currentStep === 5" class="p-8">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xl">💳</span>
                            <h2 class="text-xl font-bold text-stone-800">
                                Payment
                            </h2>
                        </div>
                        <p class="text-sm text-stone-500 mb-8 ml-7">
                            Total due:
                            <strong class="text-blue-700">{{
                                fmt(grandTotal)
                            }}</strong>
                        </p>

                        <div class="flex gap-3 mb-8">
                            <button
                                v-for="m in [
                                    { id: 'gcash', label: 'GCash', icon: '📱' },
                                    {
                                        id: 'paymaya',
                                        label: 'Maya',
                                        icon: '💜',
                                    },
                                    {
                                        id: 'property',
                                        label: 'Pay at Venue',
                                        icon: '🚢',
                                    },
                                ]"
                                :key="m.id"
                                @click="
                                    payment.method = m.id;
                                    payment.accountNumber = '';
                                    payment.transactionNumber = '';
                                "
                                :class="[
                                    'flex-1 border-2 rounded-xl py-3 px-2 text-center text-xs font-semibold transition-all',
                                    payment.method === m.id
                                        ? 'border-blue-600 bg-blue-50 text-blue-700'
                                        : 'border-stone-200 text-stone-500 hover:border-stone-300',
                                ]"
                            >
                                <span class="text-lg block mb-1">{{
                                    m.icon
                                }}</span>
                                {{ m.label }}
                            </button>
                        </div>

                        <div
                            v-if="payment.method === 'gcash'"
                            class="space-y-5"
                        >
                            <div
                                class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3"
                            >
                                <span class="text-2xl">📱</span>
                                <div>
                                    <p
                                        class="text-sm font-semibold text-green-800 mb-0.5"
                                    >
                                        Send your GCash payment to:
                                    </p>
                                    <p
                                        class="text-lg font-bold text-green-700 tracking-widest"
                                    >
                                        0917 XXX XXXX
                                    </p>
                                    <p class="text-xs text-green-600 mt-0.5">
                                        Account Name:
                                        <strong>Butal Ship Hauz</strong>
                                    </p>
                                    <p class="text-xs text-stone-500 mt-2">
                                        After sending, fill in your details
                                        below so we can verify your payment.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Your GCash Account Number *</label
                                >
                                <input
                                    v-model="payment.accountNumber"
                                    type="tel"
                                    placeholder="09XX XXX XXXX"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >GCash Transaction Reference Number *</label
                                >
                                <input
                                    v-model="payment.transactionNumber"
                                    type="text"
                                    placeholder="e.g. 1234567890"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                                <p class="text-xs text-stone-400 mt-1.5">
                                    You can find this in your GCash app under
                                    <strong>Transaction History</strong>.
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="payment.method === 'paymaya'"
                            class="space-y-5"
                        >
                            <div
                                class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-start gap-3"
                            >
                                <span class="text-2xl">💜</span>
                                <div>
                                    <p
                                        class="text-sm font-semibold text-purple-800 mb-0.5"
                                    >
                                        Send your Maya payment to:
                                    </p>
                                    <p
                                        class="text-lg font-bold text-purple-700 tracking-widest"
                                    >
                                        0917 XXX XXXX
                                    </p>
                                    <p class="text-xs text-purple-600 mt-0.5">
                                        Account Name:
                                        <strong>Butal Ship Hauz</strong>
                                    </p>
                                    <p class="text-xs text-stone-500 mt-2">
                                        After sending, fill in your details
                                        below so we can verify your payment.
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Your Maya Account Number *</label
                                >
                                <input
                                    v-model="payment.accountNumber"
                                    type="tel"
                                    placeholder="09XX XXX XXXX"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                    >Maya Transaction Reference Number *</label
                                >
                                <input
                                    v-model="payment.transactionNumber"
                                    type="text"
                                    placeholder="e.g. TXN-1234567890"
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                                <p class="text-xs text-stone-400 mt-1.5">
                                    You can find this in your Maya app under
                                    <strong>Transaction History</strong>.
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="payment.method === 'property'"
                            class="bg-blue-50 border border-blue-200 rounded-xl p-5 text-sm text-blue-800"
                        >
                            🚢 You'll settle the full amount of
                            <strong>{{ fmt(grandTotal) }}</strong> upon arrival
                            at Butal Ship Hauz, Talibon, Bohol. Your booking
                            will be held pending confirmation by our team within
                            24 hours.
                        </div>
                    </div>

                    <!-- ── STEP 6: CONFIRMED ───────────────────────────────── -->
                    <div v-if="currentStep === 6" class="p-10 text-center">
                        <div
                            class="flex items-center justify-center mx-auto mb-5"
                        >
                            <svg
                                class="w-10 h-10 fill-brass flex-shrink-0"
                                viewBox="0 0 48 48"
                            >
                                <path
                                    d="M24 6a6 6 0 1 0 0 12A6 6 0 0 0 24 6zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 6c-1.1 0-2 .9-2 2v16.5C15 37 8.5 31 8.5 24H12c.83 0 1.5-.67 1.5-1.5S12.83 21 12 21H6c-.83 0-1.5.67-1.5 1.5S5.17 24 6 24c0 8.84 7.16 16 16 16s16-7.16 16-16h2.5a1.5 1.5 0 0 0 0-3H36c-.83 0-1.5.67-1.5 1.5S35.17 24 36 24c0 7-6.5 13-13.5 13.5V22c0-1.1-.9-2-2-2z"
                                />
                            </svg>
                            <div>
                                <div
                                    class="font-display text-navy text-base font-semibold leading-tight"
                                >
                                    Butal Ship Hauz
                                </div>
                                <div
                                    class="font-mono text-brass text-[12px] tracking-[.18em] uppercase leading-none"
                                >
                                    Talibon, Bohol
                                </div>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-stone-800 mb-2">
                            Booking Confirmed!
                        </h2>
                        <p class="text-stone-500 mb-6">
                            A confirmation has been sent to
                            <strong class="text-stone-700">{{
                                contact.email
                            }}</strong>
                        </p>

                        <div
                            class="bg-blue-50 border border-blue-200 rounded-2xl p-6 inline-block mb-8"
                        >
                            <p
                                class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1"
                            >
                                Booking Reference
                            </p>
                            <p
                                class="text-3xl font-bold font-mono text-blue-700 tracking-widest"
                            >
                                {{ reservationCode }}
                            </p>
                        </div>

                        <div class="text-sm text-stone-600 space-y-1 mb-8">
                            <p>🚢 Butal Ship Hauz, Talibon, Bohol</p>
                            <p>🎉 {{ eventType }}</p>
                            <p>📅 {{ fmtDate(eventDate) }}</p>
                            <p>🕐 {{ selectedTimeSlotData?.time }}</p>
                            <p>⚓ {{ selectedPackageData?.name }}</p>
                            <p>
                                👤 {{ contact.firstName }}
                                {{ contact.lastName }}
                            </p>
                            <p>💰 {{ fmt(grandTotal) }}</p>
                            <p v-if="payment.method !== 'property'">
                                💳 Paid via
                                {{
                                    payment.method === "gcash"
                                        ? "GCash"
                                        : "Maya"
                                }}
                                · Ref# {{ payment.transactionNumber }}
                            </p>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row items-center justify-center gap-3"
                        >
                            <button
                                @click="
                                    currentStep = 1;
                                    eventDate = '';
                                    timeSlot = '';
                                    eventType = '';
                                    selectedPackage = null;
                                    selectedAddons = [];
                                    walkInGuests = 0;
                                    contact = {
                                        firstName: '',
                                        lastName: '',
                                        email: '',
                                        phone: '',
                                        guestCount: 20,
                                        requests: '',
                                    };
                                    payment = {
                                        method: 'gcash',
                                        accountNumber: '',
                                        transactionNumber: '',
                                    };
                                    reservationCode = '';
                                "
                                class="text-sm text-blue-700 hover:text-blue-800 font-semibold underline underline-offset-2"
                            >
                                Book another event
                            </button>
                            <span class="text-stone-300 hidden sm:block"
                                >|</span
                            >
                            <button
                                @click="resetForm"
                                class="text-sm text-stone-500 hover:text-stone-700 font-semibold underline underline-offset-2"
                            >
                                Back to Bookings Table
                            </button>
                        </div>
                    </div>

                    <!-- ── NAVIGATION ─────────────────────────────────────── -->
                    <div
                        v-if="currentStep < 6"
                        class="px-8 py-5 border-t border-stone-100 flex justify-between items-center"
                    >
                        <button
                            v-if="currentStep > 1"
                            @click="prevStep"
                            class="flex items-center gap-2 text-sm font-semibold text-stone-500 hover:text-stone-700 transition-colors"
                        >
                            ← Back
                        </button>
                        <div v-else />

                        <button
                            v-if="currentStep === 5"
                            @click="confirmReservation"
                            :disabled="!step5Valid"
                            :class="[
                                'px-8 py-3 rounded-xl font-semibold text-sm transition-all',
                                step5Valid
                                    ? 'bg-blue-700 hover:bg-blue-800 text-white shadow-md'
                                    : 'bg-stone-200 text-stone-400 cursor-not-allowed',
                            ]"
                        >
                            Confirm Booking
                        </button>

                        <button
                            v-else
                            @click="nextStep"
                            :disabled="
                                (currentStep === 1 && !step1Valid) ||
                                (currentStep === 2 && !step2Valid) ||
                                (currentStep === 3 && !step3Valid)
                            "
                            :class="[
                                'px-8 py-3 rounded-xl font-semibold text-sm transition-all',
                                (currentStep === 1 && step1Valid) ||
                                (currentStep === 2 && step2Valid) ||
                                (currentStep === 3 && step3Valid) ||
                                currentStep === 4
                                    ? 'bg-blue-700 hover:bg-blue-800 text-white shadow-md'
                                    : 'bg-stone-200 text-stone-400 cursor-not-allowed',
                            ]"
                        >
                            {{
                                currentStep === 4
                                    ? "Proceed to Payment →"
                                    : "Continue →"
                            }}
                        </button>
                    </div>
                </div>
                <!-- /card -->

                <!-- Step counter -->
                <p class="text-center text-xs text-stone-400 mt-4">
                    Step {{ Math.min(currentStep, 5) }} of 5
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
