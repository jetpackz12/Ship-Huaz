<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CalendarPicker from "@/Components/CalendarPicker.vue";
import Table from "@/Components/Table.vue";
import Modal from "@/Components/Modal.vue";
import axios from "axios";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import { useFormatter } from "@/Composables/useFormatter";
import { useModal } from "@/Composables/useModal";

const props = defineProps({
    bookings: Array,
    eventTypes: Array,
    venuePackages: Array,
    packageAddOns: Array,
    paymentOptions: Array,
});

const localBookings = ref([...(props.bookings ?? [])]);

watch(
    () => props.bookings,
    (newVal) => {
        localBookings.value = [...(newVal ?? [])];
    },
);

const page = usePage();

const errors = computed(() => page.props.errors ?? {});

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
    { key: "actions", label: "Actions", slot: "actions" },
];

// ─── Table: Default Data ───────────────────────────────────────────────────
const tableData = computed(() =>
    localBookings.value.map((b) => {
        if (b.ref !== undefined) return b;
        return {
            id: b.id,
            ref: b.booking_ref,
            event: b.event_type?.type ?? "—",
            date: b.date,
            time: b.time_slot,
            package: b.venue_package?.title ?? "—",
            addons:
                Array.isArray(b.package_add_ons) && b.package_add_ons.length
                    ? b.package_add_ons.map((a) => a.title ?? a).join(", ")
                    : "",
            amount: b.total_payment,
            payment_method: b.payment_option?.payment ?? "—",
            payment_ref: b.payment_transaction_ref ?? "—",
            status: b.status,
        };
    }),
);

const tableActions = {
    isDateFilterShow: false,
    isPerPageShow: false,
    isSearchShow: true,
};

// ─── Cancel Booking ─────────────────────────────────────────────────────────
const {
    open: showCancelModal,
    openModal: openCancelModalRaw,
    closeModal: closeCancelModal,
} = useModal();

const bookingToCancel = ref(null);
const isCancelling = ref(false);

const openCancelModal = (row) => {
    if (row.status === "cancelled") return;
    bookingToCancel.value = row;
    openCancelModalRaw();
};

const confirmCancel = () => {
    if (!bookingToCancel.value) return;

    isCancelling.value = true;

    router.put(
        route("client.booking.cancel", {
            booking: bookingToCancel.value.id,
        }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                closeCancelModal();
                bookingToCancel.value = null;
            },
            onError: () => {
                alert("Could not cancel this booking. Please try again.");
            },
            onFinish: () => {
                isCancelling.value = false;
            },
        },
    );
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

// ─── Terms & Conditions Modal ───────────────────────────────────────────────
const {
    open: showTermsModal,
    openModal: openTermsModal,
    closeModal: closeTermsModal,
} = useModal();

const acceptTerms = () => {
    closeTermsModal();
    nextStep();
};

// ─── Step 1: Event Date & Time ─────────────────────────────────────────────
const eventDate = ref("");
const timeSlot = ref("");
const today = new Date().toISOString().split("T")[0];

const timeSlots = [
    { id: "morning", label: "Morning", time: "8:00 AM – 12:00 PM" },
    { id: "afternoon", label: "Afternoon", time: "1:00 PM – 5:00 PM" },
    { id: "fullday", label: "Full Day", time: "8:00 AM – 5:00 PM" },
];

const eventType = ref("");

const selectedEventTypeLabel = computed(
    () =>
        activeEventTypes.value.find((e) => e.id === eventType.value)?.type ??
        "",
);

// ─── Step 1: Availability Check ───────────────────────────────────────────
const availabilityChecked = ref(false);
const isAvailable = ref(true);
const isCheckingAvailability = ref(false);
const availabilityError = ref("");

const checkAvailability = async () => {
    if (!eventDate.value || !timeSlot.value) return;

    availabilityChecked.value = false;
    isAvailable.value = true;
    availabilityError.value = "";
    isCheckingAvailability.value = true;

    try {
        const { data } = await axios.post(
            route("client.booking.check-availability"),
            {
                date: eventDate.value,
                time_slot: timeSlot.value,
            },
        );

        isAvailable.value = data.available;
        availabilityChecked.value = true;

        if (data.available) {
            nextStep();
        } else {
            availabilityError.value =
                "This date and time slot is already booked. Please choose another.";
        }
    } catch (error) {
        console.error(error);
        availabilityError.value =
            "Could not check availability. Please try again.";
    } finally {
        isCheckingAvailability.value = false;
    }
};

watch([eventDate, timeSlot], () => {
    availabilityChecked.value = false;
    isAvailable.value = true;
    availabilityError.value = "";
});

// ── Mapped from props.eventTypes ──────────────────────────────────────────
const activeEventTypes = computed(() =>
    (props.eventTypes ?? []).filter((e) => e.status === "active"),
);

const step1Valid = computed(
    () => eventDate.value && timeSlot.value && eventType.value,
);

// ─── Step 2: Venue Packages & Add-ons ─────────────────────────────────────
// ── Mapped from props.venuePackages ──────────────────────────────────────
const packages = computed(() =>
    (props.venuePackages ?? [])
        .filter((p) => p.status === "active")
        .map((p) => ({
            id: p.id,
            name: p.title,
            desc: p.description,
            price: Number(p.price),
            capacity: `Up to ${p.guests} guests`,
        })),
);

// ── Mapped from props.packageAddOns ──────────────────────────────────────
const addons = computed(() =>
    (props.packageAddOns ?? [])
        .filter((a) => a.status === "active")
        .map((a) => ({
            id: a.id,
            name: a.title,
            desc: a.description,
            price: Number(a.price),
        })),
);

const selectedPackage = ref(null);
const selectedAddons = ref([]);

const toggleAddon = (id) => {
    const idx = selectedAddons.value.indexOf(id);
    idx === -1
        ? selectedAddons.value.push(id)
        : selectedAddons.value.splice(idx, 1);
};

const selectedPackageData = computed(() =>
    packages.value.find((p) => p.id === selectedPackage.value),
);
const selectedAddonData = computed(() =>
    addons.value.filter((a) => selectedAddons.value.includes(a.id)),
);

const packageTotal = computed(() => selectedPackageData.value?.price ?? 0);
const addonTotal = computed(() =>
    selectedAddonData.value.reduce((sum, a) => sum + a.price, 0),
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
// ── Mapped from props.paymentOptions (Pay at Venue removed — online only) ──
const activePaymentOptions = computed(() =>
    (props.paymentOptions ?? [])
        .filter((o) => o.status === "active")
        .map((o) => ({
            id: o.id,
            label: o.payment,
            number: o.number,
            account: o.account,
            description: o.description,
            isOnline: true,
        })),
);

const payment = ref({
    method: "",
    accountNumber: "",
    transactionNumber: "",
});

const initPaymentMethod = () => {
    if (!payment.value.method && activePaymentOptions.value.length) {
        payment.value.method = activePaymentOptions.value[0].id;
    }
};

initPaymentMethod();

const selectedPaymentOption = computed(() =>
    activePaymentOptions.value.find((o) => o.id === payment.value.method),
);

const step5Valid = computed(() => {
    if (selectedPaymentOption.value?.isOnline) {
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
    form.date = eventDate.value;
    form.time_slot = timeSlot.value;
    form.event_type_id = eventType.value;
    form.venue_package_id = selectedPackage.value;
    form.package_add_ons = selectedAddons.value;
    form.guest_first_name = contact.value.firstName;
    form.guest_last_name = contact.value.lastName;
    form.guest_email = contact.value.email;
    form.guest_phone = contact.value.phone;
    form.guest_count = contact.value.guestCount;
    form.guest_request_notes = contact.value.requests;
    form.payment_option_id = payment.value.method;
    form.payment_account_number = payment.value.accountNumber;
    form.payment_transaction_ref = payment.value.transactionNumber;

    form.post(route("client.booking.store"), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            reservationCode.value = page.props.flash?.booking_ref;

            nextStep();
        },
    });
};

// ─── Reset Form ────────────────────────────────────────────────────────────
const resetForm = () => {
    currentStep.value = 1;
    eventDate.value = "";
    timeSlot.value = "";
    eventType.value = "";
    selectedPackage.value = null;
    selectedAddons.value = [];
    contact.value = {
        firstName: "",
        lastName: "",
        email: "",
        phone: "",
        guestCount: 20,
        requests: "",
    };
    payment.value = {
        method: activePaymentOptions.value[0]?.id ?? "",
        accountNumber: "",
        transactionNumber: "",
    };
    reservationCode.value = "";
    showForm.value = false;
};

const form = useForm({
    date: "",
    time_slot: "",
    event_type_id: "",
    venue_package_id: null,
    package_add_ons: [],
    guest_first_name: "",
    guest_last_name: "",
    guest_email: "",
    guest_phone: "",
    guest_count: 20,
    guest_request_notes: "",
    payment_option_id: "",
    payment_account_number: "",
    payment_transaction_ref: "",
});

// ─── Helpers ───────────────────────────────────────────────────────────────
const fmt = (n) => "₱" + Number(n).toLocaleString("en-PH");

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
    timeSlots.find((t) => t.time === timeSlot.value),
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
    <Head title="Event Booking" />

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
                            <span class="block text-gray-400 text-xs"
                                >Ref: {{ row.payment_ref }}</span
                            >
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

                        <template #actions="{ row }">
                            <button
                                v-if="
                                    row.status !== 'cancelled' &&
                                    row.status !== 'completed'
                                "
                                @click="openCancelModal(row)"
                                :disabled="
                                    isCancelling &&
                                    bookingToCancel?.ref === row.ref
                                "
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-3 py-2 rounded-md transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Cancel
                            </button>
                            <span v-else class="text-xs text-stone-400 italic">
                                {{
                                    row.status === "completed"
                                        ? "Completed"
                                        : "Cancelled"
                                }}
                            </span>
                        </template>
                    </Table>
                </div>
            </div>

            <!-- ══════════════════════════════════════════════════════════ -->
            <!-- BOOKING FORM VIEW                                          -->
            <!-- ══════════════════════════════════════════════════════════ -->
            <div v-if="showForm">
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
                    <!-- Error Banner -->
                    <div
                        v-if="Object.keys(errors).length"
                        class="mx-8 mt-4 bg-red-50 border border-red-200 rounded-xl px-5 py-4"
                    >
                        <p class="text-sm font-semibold text-red-700 mb-1">
                            Please fix the following errors:
                        </p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li
                                v-for="(error, field) in errors"
                                :key="field"
                                class="text-xs text-red-600"
                            >
                                {{ error }}
                            </li>
                        </ul>
                    </div>
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
                                    @click="timeSlot = slot.time"
                                    :class="[
                                        'text-left border-2 rounded-xl p-4 transition-all duration-150',
                                        timeSlot === slot.time
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
                                    v-for="type in activeEventTypes"
                                    :key="type.id"
                                    :value="type.id"
                                >
                                    {{ type.type }}
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
                                    {{ selectedEventTypeLabel }}
                                </p>
                                <p class="text-xs text-stone-500">
                                    {{ fmtDate(eventDate) }} &bull;
                                    {{ selectedTimeSlotData?.time }}
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="availabilityError"
                            class="mt-4 bg-red-50 border border-red-200 rounded-xl px-5 py-4 flex items-start gap-3"
                        >
                            <span class="text-red-500 text-lg">⚠️</span>
                            <div>
                                <p class="text-sm font-semibold text-red-700">
                                    Slot Unavailable
                                </p>
                                <p class="text-xs text-red-600 mt-0.5">
                                    {{ availabilityError }}
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
                                    class="flex justify-between items-start mb-1"
                                >
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
                                                {{ fmt(addon.price) }}
                                            </p>
                                        </div>
                                    </div>
                                </button>
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
                                    required
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
                                    required
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
                                    required
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
                                    placeholder="9123456789"
                                    pattern="^9\d{9}$"
                                    maxlength="10"
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
                                    >{{ selectedEventTypeLabel }}</span
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
                                    fmt(addon.price)
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
                                v-for="m in activePaymentOptions"
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
                                    m.icon ?? "💳"
                                }}</span>
                                {{ m.label }}
                            </button>
                        </div>

                        <div
                            v-if="selectedPaymentOption?.isOnline"
                            class="space-y-5"
                        >
                            <div
                                class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3"
                            >
                                <span class="text-2xl">💳</span>
                                <div>
                                    <p
                                        class="text-sm font-semibold text-blue-800 mb-0.5"
                                    >
                                        Send your
                                        {{ selectedPaymentOption.label }}
                                        payment to:
                                    </p>
                                    <p
                                        class="text-lg font-bold text-blue-700 tracking-widest"
                                    >
                                        {{ selectedPaymentOption.number }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-0.5">
                                        Account Name:
                                        <strong>{{
                                            selectedPaymentOption.account
                                        }}</strong>
                                    </p>
                                    <p class="text-xs text-stone-500 mt-2">
                                        {{ selectedPaymentOption.description }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                >
                                    Your
                                    {{ selectedPaymentOption.label }} Account
                                    Number *
                                </label>
                                <input
                                    v-model="payment.accountNumber"
                                    type="tel"
                                    placeholder="9123456789"
                                    pattern="^9\d{9}$"
                                    maxlength="10"
                                    required
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-2"
                                >
                                    {{ selectedPaymentOption.label }}
                                    Transaction Reference Number *
                                </label>
                                <input
                                    v-model="payment.transactionNumber"
                                    type="text"
                                    placeholder="e.g. 1234567890"
                                    required
                                    class="w-full border border-stone-200 rounded-xl px-4 py-3 text-stone-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                />
                            </div>
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
                            <p>🎉 {{ selectedEventTypeLabel }}</p>
                            <p>📅 {{ fmtDate(eventDate) }}</p>
                            <p>🕐 {{ selectedTimeSlotData?.time }}</p>
                            <p>⚓ {{ selectedPackageData?.name }}</p>
                            <p>
                                👤 {{ contact.firstName }}
                                {{ contact.lastName }}
                            </p>
                            <p>💰 {{ fmt(grandTotal) }}</p>
                            <p>
                                💳 Paid via
                                {{ selectedPaymentOption?.label }}
                                · Ref# {{ payment.transactionNumber }}
                            </p>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row items-center justify-center gap-3"
                        >
                            <button
                                @click="resetForm"
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
                            @click="
                                currentStep === 1
                                    ? checkAvailability()
                                    : currentStep === 4
                                      ? openTermsModal()
                                      : nextStep()
                            "
                            :disabled="
                                (currentStep === 1 &&
                                    (!step1Valid || isCheckingAvailability)) ||
                                (currentStep === 2 && !step2Valid) ||
                                (currentStep === 3 && !step3Valid)
                            "
                            :class="[
                                'px-8 py-3 rounded-xl font-semibold text-sm transition-all',
                                (currentStep === 1 &&
                                    step1Valid &&
                                    !isCheckingAvailability) ||
                                (currentStep === 2 && step2Valid) ||
                                (currentStep === 3 && step3Valid) ||
                                currentStep === 4
                                    ? 'bg-blue-700 hover:bg-blue-800 text-white shadow-md'
                                    : 'bg-stone-200 text-stone-400 cursor-not-allowed',
                            ]"
                        >
                            <span
                                v-if="
                                    currentStep === 1 && isCheckingAvailability
                                "
                            >
                                Checking…
                            </span>
                            <span v-else>
                                {{
                                    currentStep === 4
                                        ? "Proceed to Payment →"
                                        : "Continue →"
                                }}
                            </span>
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

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- TERMS & CONDITIONS MODAL                                       -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <Modal :show="showTermsModal" max-width="md" @close="closeTermsModal">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xl">📜</span>
                    <h3 class="text-lg font-bold text-stone-800">
                        Terms & Conditions
                    </h3>
                </div>
                <div
                    class="text-sm text-stone-600 space-y-3 mb-6 max-h-72 overflow-y-auto pr-1"
                >
                    <p>
                        By proceeding to payment, you agree to the following
                        booking terms for Butal Ship Hauz:
                    </p>
                    <ul class="list-disc list-inside space-y-2">
                        <li>
                            Bookings are confirmed only upon receipt and
                            verification of payment.
                        </li>
                        <li>
                            If you cancel your booking, you will only receive
                            <strong>50% of your total payment</strong> as a
                            refund. The remaining 50% is retained as a
                            cancellation fee.
                        </li>
                        <li>
                            Rescheduling requests are subject to venue
                            availability and must be made at least 3 days before
                            the event date.
                        </li>
                        <li>
                            No-shows on the event date are not eligible for any
                            refund.
                        </li>
                    </ul>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button
                        @click="closeTermsModal"
                        class="text-sm font-semibold text-stone-500 hover:text-stone-700 px-4 py-2.5 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        @click="acceptTerms"
                        class="bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition-colors"
                    >
                        I Agree, Continue →
                    </button>
                </div>
            </div>
        </Modal>

        <!-- ══════════════════════════════════════════════════════════════ -->
        <!-- CANCEL BOOKING CONFIRMATION MODAL                              -->
        <!-- ══════════════════════════════════════════════════════════════ -->
        <Modal :show="showCancelModal" max-width="md" @close="closeCancelModal">
            <div class="p-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xl">⚠️</span>
                    <h3 class="text-lg font-bold text-stone-800">
                        Cancel Booking
                    </h3>
                </div>
                <p class="text-sm text-stone-600 mb-2">
                    Are you sure you want to cancel booking
                    <strong class="font-mono">{{
                        bookingToCancel?.ref
                    }}</strong>
                    ?
                </p>
                <p class="text-sm text-stone-600 mb-6">
                    Per our terms, only
                    <strong>50% of the payment</strong> will be refunded upon
                    cancellation. This action cannot be undone.
                </p>
                <div class="flex items-center justify-end gap-3">
                    <button
                        @click="closeCancelModal"
                        :disabled="isCancelling"
                        class="text-sm font-semibold text-stone-500 hover:text-stone-700 px-4 py-2.5 transition-colors disabled:opacity-50"
                    >
                        Keep Booking
                    </button>
                    <button
                        @click="confirmCancel"
                        :disabled="isCancelling"
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition-colors disabled:opacity-50"
                    >
                        {{
                            isCancelling ? "Cancelling…" : "Yes, Cancel Booking"
                        }}
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
