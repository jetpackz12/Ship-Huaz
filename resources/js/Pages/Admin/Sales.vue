<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Table from "@/Components/Table.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { useFormatter } from "@/Composables/useFormatter";

const props = defineProps({
    bookings: Array,
});

const { formatAmount, formatDate, formatPhone } = useFormatter();

const tableColumns = [
    { key: "booking_ref", label: "Booking Ref", slot: "ref" },
    { key: "event_details", label: "Event Details", slot: "event_details" },
    { key: "package", label: "Package", slot: "package" },
    { key: "contact", label: "Contact", slot: "contact" },
    { key: "payment", label: "Payment", slot: "payment" },
    { key: "date", label: "Date", slot: "date" },
];

const tableActions = {
    isDateFilterShow: true,
    isPerPageShow: true,
    isSearchShow: false,
};

const filteredRows = ref([]);
const activeDateFrom = ref("");
const activeDateTo = ref("");
const hasFilters = ref(false);

const handleFilteredChange = ({
    rows,
    dateFrom,
    dateTo,
    hasFilters: active,
}) => {
    filteredRows.value = rows;
    activeDateFrom.value = dateFrom;
    activeDateTo.value = dateTo;
    hasFilters.value = active;
};

const grandTotal = computed(() =>
    filteredRows.value.reduce(
        (sum, b) => sum + Number(b.total_payment || 0),
        0,
    ),
);

const canAct = computed(
    () => hasFilters.value && filteredRows.value.length > 0,
);

const handlePrint = () => {
    const printContents = document.getElementById("print-table").innerHTML;

    const printWindow = window.open("", "_blank");

    printWindow.document.write(`
        <html>
            <head>
                <title>Daily Sales Report</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    th, td {
                        border: 1px solid #ccc;
                        padding: 8px;
                        text-align: center;
                    }

                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }

                    td {
                        font-size: 12px;
                    }
                </style>
            </head>
            <body>
                ${printContents}
            </body>
        </html>
    `);

    printWindow.document.close();

    printWindow.onload = () => {
        printWindow.print();
        printWindow.close();
    };
};
</script>

<template>
    <Head title="Daily Sales Report" />

    <AdminLayout>
        <div>
            <div
                class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden px-4 py-4"
            >
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2 print:hidden"
                >
                    <h1 class="text-xl font-bold text-stone-800">
                        Daily Sales Report
                    </h1>

                    <div class="flex flex-wrap gap-1">
                        <button
                            @click="handlePrint"
                            :disabled="!canAct"
                            class="flex items-center gap-2 bg-stone-600 hover:bg-stone-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-semibold px-3 py-2 rounded-md transition-colors"
                        >
                            <font-awesome-icon icon="fa-solid fa-print" />
                            Print
                        </button>
                        <Link
                            :href="route('admin.sales.index')"
                            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-semibold px-3 py-2 rounded-md transition-colors"
                        >
                            <font-awesome-icon icon="fa-solid fa-sync" />
                            Refresh
                        </Link>
                    </div>
                </div>
                <hr class="my-2" />
                <!-- Interactive, paginated table (hidden while printing) -->
                <div>
                    <Table
                        :data="bookings"
                        :columns="tableColumns"
                        :actions="tableActions"
                        :initial-empty="true"
                        empty-state-message="Select a date range above to generate the report"
                        @filtered-change="handleFilteredChange"
                    >
                        <!-- Ref -->
                        <template #ref="{ row }">
                            <p class="text-sm font-medium text-stone-700">
                                {{ row.booking_ref }}
                            </p>
                        </template>

                        <!-- Event Details -->
                        <template #event_details="{ row }">
                            <p class="text-sm font-medium text-stone-700">
                                {{ row.event_type?.type ?? "—" }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{ row.date ? formatDate(row.date) : "—" }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{ row.time_slot ?? "—" }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{ row.guest_count }} guests
                            </p>
                        </template>

                        <!-- Package -->
                        <template #package="{ row }">
                            <p class="text-sm font-medium text-stone-700">
                                {{ row.venue_package?.title ?? "—" }}
                            </p>
                            <p class="text-sm text-stone-500">
                                {{
                                    row.venue_package?.price
                                        ? formatAmount(row.venue_package.price)
                                        : "—"
                                }}
                            </p>
                            <template v-if="row.package_add_ons?.length">
                                <hr class="my-1 border-stone-200" />
                                <p class="text-xs text-stone-400 mb-0.5">
                                    Add-ons:
                                </p>
                                <p
                                    v-for="(
                                        addon, index
                                    ) in row.package_add_ons"
                                    :key="index"
                                    class="text-sm text-stone-500"
                                >
                                    • {{ addon.title }} ({{
                                        formatAmount(addon.price)
                                    }})
                                </p>
                            </template>
                            <hr class="my-1 border-stone-200" />
                            <p class="text-sm font-semibold text-stone-700">
                                Total: {{ formatAmount(row.total_payment) }}
                            </p>
                        </template>

                        <!-- Contact -->
                        <template #contact="{ row }">
                            <p class="text-sm font-medium text-stone-700">
                                Client: {{ row.user?.user_info.first_name }}
                                {{ row.user?.user_info.last_name }}
                            </p>
                            <p class="text-sm text-stone-500">
                                Client Email: {{ row.user?.email }}
                            </p>
                            <p class="text-sm text-stone-500">
                                Client Phone:
                                {{ formatPhone(row.user?.user_info.phone) }}
                            </p>
                            <hr class="my-1 border-stone-200" />
                            <p class="text-sm font-medium text-stone-700">
                                Guest: {{ row.guest_first_name }}
                                {{ row.guest_last_name }}
                            </p>
                            <p class="text-sm text-stone-500">
                                Guest Email: {{ row.guest_email }}
                            </p>
                            <p class="text-sm text-stone-500">
                                Guest Phone: {{ formatPhone(row.guest_phone) }}
                            </p>
                        </template>

                        <!-- Payment -->
                        <template #payment="{ row }">
                            <p class="text-sm font-medium text-stone-700">
                                {{ row.payment_option?.payment ?? "Walk-in" }}
                            </p>
                            <template v-if="row.payment_option">
                                <p class="text-sm text-stone-500">
                                    {{ row.payment_option.account }}
                                </p>
                                <p class="text-sm text-stone-500">
                                    {{ formatPhone(row.payment_option.number) }}
                                </p>
                            </template>
                            <template v-if="row.payment_transaction_ref">
                                <hr class="my-1 border-stone-200" />
                                <p class="text-xs text-stone-400">Ref #</p>
                                <p class="text-sm text-stone-500">
                                    {{ row.payment_transaction_ref }}
                                </p>
                                <p class="text-sm text-stone-500">
                                    {{
                                        formatPhone(row.payment_account_number)
                                    }}
                                </p>
                            </template>
                        </template>

                        <!-- Date -->
                        <template #date="{ row }">
                            <p class="text-sm text-stone-600">
                                {{ row.date ? formatDate(row.date) : "—" }}
                            </p>
                        </template>

                        <!-- Grand total row: sums ALL filtered rows, not just the current page -->
                        <template #summary>
                            <tr class="bg-stone-50">
                                <td
                                    :colspan="tableColumns.length"
                                    class="px-3 py-2.5 border border-stone-400 text-sm text-right font-semibold text-stone-700"
                                >
                                    Grand Total ({{ filteredRows.length }}
                                    {{
                                        filteredRows.length === 1
                                            ? "sale"
                                            : "sales"
                                    }}):
                                    {{ formatAmount(grandTotal) }}
                                </td>
                            </tr>
                        </template>
                    </Table>
                </div>

                <!-- Print-only view: full filtered list, no pagination, plus a grand total -->
                <div id="print-table" class="hidden">
                    <h2 class="text-lg font-bold mb-1">Daily Sales Report - Butal ShipHauz</h2>
                    <p class="text-xs text-stone-500 mb-3">
                        {{ activeDateFrom ? formatDate(activeDateFrom) : "—" }}
                        to
                        {{ activeDateTo ? formatDate(activeDateTo) : "—" }}
                    </p>

                    <table class="w-full text-xs border-collapse">
                        <thead>
                            <tr>
                                <th class="border border-stone-400 px-2 py-1">
                                    #
                                </th>
                                <th class="border border-stone-400 px-2 py-1">
                                    Booking Ref
                                </th>
                                <th class="border border-stone-400 px-2 py-1">
                                    Event
                                </th>
                                <th class="border border-stone-400 px-2 py-1">
                                    Package
                                </th>
                                <th class="border border-stone-400 px-2 py-1">
                                    Client
                                </th>
                                <th class="border border-stone-400 px-2 py-1">
                                    Payment
                                </th>
                                <th class="border border-stone-400 px-2 py-1">
                                    Date
                                </th>
                                <th class="border border-stone-400 px-2 py-1">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in filteredRows" :key="row.id">
                                <td class="border border-stone-300 px-2 py-1">
                                    {{ ++index }}
                                </td>
                                <td class="border border-stone-300 px-2 py-1">
                                    {{ row.booking_ref }}
                                </td>
                                <td class="border border-stone-300 px-2 py-1">
                                    {{ row.event_type?.type ?? "—" }}
                                </td>
                                <td class="border border-stone-300 px-2 py-1">
                                    {{ row.venue_package?.title ?? "—" }}
                                </td>
                                <td class="border border-stone-300 px-2 py-1">
                                    {{ row.user?.user_info.first_name }}
                                    {{ row.user?.user_info.last_name }}
                                </td>
                                <td class="border border-stone-300 px-2 py-1">
                                    {{
                                        row.payment_option?.payment ?? "Walk-in"
                                    }}
                                </td>
                                <td class="border border-stone-300 px-2 py-1">
                                    {{ row.date ? formatDate(row.date) : "—" }}
                                </td>
                                <td class="border border-stone-300 px-2 py-1">
                                    {{ formatAmount(row.total_payment) }}
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td
                                    colspan="7"
                                    class="border border-stone-400 px-2 py-1 text-right font-semibold"
                                >
                                    Grand Total
                                </td>
                                <td
                                    class="border border-stone-400 px-2 py-1 font-semibold"
                                >
                                    {{ formatAmount(grandTotal) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
