<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Table from "@/Components/Table.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { useFormatter } from "@/Composables/useFormatter";

const props = defineProps({
    bookings: Array,
});

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
    user_information.put(route("update-information"));
};

const submitCredentials = () => {
    user_credentials.put(route("update-credentials"), {
        onSuccess: () =>
            user_credentials.reset("password", "password_confirmation"),
    });
};

// --- Booking History ---

const TableColumns = [
    { key: "ref", label: "Booking Ref", slot: "ref" },
    { key: "event", label: "Event" },
    { key: "date", label: "Date", slot: "date" },
    { key: "time", label: "Time" },
    { key: "package", label: "Package", slot: "package" },
    { key: "amount", label: "Amount", slot: "amount" },
    { key: "payment_method", label: "Payment", slot: "payment" },
    { key: "status", label: "Status", slot: "status" },
];

const tableData = computed(() =>
    (props.bookings ?? []).map((b) => ({
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
        payment_method: b.payment_option?.payment ?? "Pay at Venue",
        payment_ref: b.payment_transaction_ref ?? "—",
        status: b.status,
    })),
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
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="flex flex-col sm:flex-row gap-3 mx-auto">
            <!-- Left Column: Information & Credentials -->

            <div class="w-full sm:w-2/3">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg max-h-full overflow-y-auto"
                >
                    <div class="p-6 text-gray-900">
                        <!-- Header -->
                        <div class="flex items-center mb-4">
                            <h1 class="sm:text-xl font-bold">
                                <font-awesome-icon
                                    icon="fa-solid fa-clock-rotate-left"
                                />
                                Booking History
                            </h1>
                        </div>

                        <!-- Table -->
                        <Table :data="tableData" :columns="TableColumns">
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
                                <span class="font-medium">{{
                                    row.package
                                }}</span>
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
                                <InputLabel
                                    for="first_name"
                                    value="First Name"
                                />
                                <TextInput
                                    id="first_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="user_information.first_name"
                                    required
                                    autocomplete="first_name"
                                    placeholder="e.g. Alex"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="
                                        user_information.errors.first_name
                                    "
                                />
                            </div>
                            <div class="mt-3">
                                <InputLabel
                                    for="middle_name"
                                    value="Middle Name"
                                />
                                <TextInput
                                    id="middle_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="user_information.middle_name"
                                    autocomplete="middle_name"
                                    placeholder="e.g. Alexey"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="
                                        user_information.errors.middle_name
                                    "
                                />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="last_name" value="Last Name" />
                                <TextInput
                                    id="last_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="user_information.last_name"
                                    required
                                    autocomplete="last_name"
                                    placeholder="e.g. Doe"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="user_information.errors.last_name"
                                />
                            </div>
                            <div class="mt-3">
                                <InputLabel
                                    for="birth_date"
                                    value="Birth Date"
                                />
                                <TextInput
                                    id="birth_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="user_information.birth_date"
                                    required
                                    autocomplete="birth_date"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="
                                        user_information.errors.birth_date
                                    "
                                />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="phone" value="Phone" />
                                <TextInput
                                    id="phone"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="user_information.phone"
                                    required
                                    autocomplete="phone"
                                    placeholder="e.g. 9123456789"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="user_information.errors.phone"
                                />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="address" value="Address" />
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="user_information.address"
                                    required
                                    autocomplete="address"
                                    placeholder="e.g. Pob. Talibon, Bohol"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="user_information.errors.address"
                                />
                            </div>
                            <div class="mt-3 flex items-center justify-center">
                                <PrimaryButton
                                    :class="{
                                        'opacity-25':
                                            user_information.processing,
                                    }"
                                    :disabled="
                                        user_information.processing ||
                                        user_credentials.processing
                                    "
                                >
                                    <div
                                        class="text-sm"
                                        v-if="user_information.processing"
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-spinner"
                                            spin
                                        />
                                    </div>
                                    <font-awesome-icon
                                        class="mx-1"
                                        icon="fa-solid fa-paper-plane"
                                    />
                                    Save Information
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>

                <div
                    class="overflow-hidden bg-white mt-4 shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <h1 class="sm:text-xl font-bold">
                            <font-awesome-icon icon="fa-solid fa-id-card" />
                            Credentials
                        </h1>
                        <form @submit.prevent="submitCredentials">
                            <div class="mt-3">
                                <InputLabel for="email" value="Email" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="user_credentials.email"
                                    required
                                    autocomplete="username"
                                    placeholder="e.g. alex@gmail.com"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="user_credentials.errors.email"
                                />
                            </div>
                            <div class="mt-3">
                                <InputLabel for="password" value="Password" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="user_credentials.password"
                                    autocomplete="new-password"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="user_credentials.errors.password"
                                />
                            </div>
                            <div class="mt-3">
                                <InputLabel
                                    for="password_confirmation"
                                    value="Confirm Password"
                                />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="
                                        user_credentials.password_confirmation
                                    "
                                    autocomplete="new-password"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="
                                        user_credentials.errors
                                            .password_confirmation
                                    "
                                />
                            </div>
                            <div class="mt-3 flex items-center justify-center">
                                <PrimaryButton
                                    :class="{
                                        'opacity-25':
                                            user_credentials.processing,
                                    }"
                                    :disabled="
                                        user_credentials.processing ||
                                        user_information.processing
                                    "
                                >
                                    <div
                                        class="text-sm"
                                        v-if="user_credentials.processing"
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-spinner"
                                            spin
                                        />
                                    </div>
                                    <font-awesome-icon
                                        class="me-1"
                                        icon="fa-solid fa-paper-plane"
                                    />
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
