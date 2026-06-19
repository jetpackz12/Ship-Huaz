<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Table from "@/Components/Table.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import { useFormatter } from "@/Composables/useFormatter";

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
                                    required
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
