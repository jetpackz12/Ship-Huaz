<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Table from "@/Components/Table.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Link, Head, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { useFormatter } from "@/Composables/useFormatter";
import { useModal } from "@/Composables/useModal";

const props = defineProps({
    bookings: Array,
});

const localBookings = ref([...props.bookings]);

const page = usePage();
const flash = computed(() => page.props.flash);

watch(flash, (newFlash) => {
    if (newFlash?.success) {
        console.log(newFlash.success);
    }
});

const tableColumns = [
    { key: "booking_ref", label: "Booking Ref", slot: "ref" },
    { key: "event_details", label: "Event Details", slot: "event_details" },
    { key: "package", label: "Package", slot: "package" },
    { key: "contact", label: "Contact", slot: "contact" },
    { key: "payment", label: "Payment", slot: "payment" },
    { key: "date", label: "Date", slot: "date" },
    { key: "status", label: "Status", slot: "status" },
    { key: "actions", label: "Actions", slot: "actions" },
];

const tableActions = {
    isDateFilterShow: true,
    isPerPageShow: true,
    isSearchShow: true,
};

const statusConfig = {
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
    completed: {
        label: "Completed",
        classes: "bg-green-100 text-green-700 border border-green-200",
    },
};

const actionConfig = {
    confirm: {
        label: "Confirm Booking",
        icon: "fa-solid fa-check",
        description: "Are you sure you want to confirm this booking?",
        status: "confirmed",
    },
    cancel: {
        label: "Cancel Booking",
        icon: "fa-solid fa-xmark",
        description: "Are you sure you want to cancel this booking?",
        status: "cancelled",
    },
    complete: {
        label: "Complete Booking",
        icon: "fa-solid fa-check-double",
        description: "Are you sure you want to mark this booking as completed?",
        status: "completed",
    },
};

const { formatAmount, formatDate, formatPhone } = useFormatter();
const modal = useModal();
const currentAction = ref(null);

const openStatusModal = (item, action) => {
    form.id = item.id;
    form.status = actionConfig[action].status;
    currentAction.value = action;

    modal.title.value = actionConfig[action].label;
    modal.type.value = "Status";
    modal.icon.value = actionConfig[action].icon;
    modal.openModal();
};

const modalClose = () => {
    form.reset();
    currentAction.value = null;
    modal.closeModal();
};

const form = useForm({
    id: "",
    status: "",
});

const submit = () => {
    form.put(route("admin.bookings.update", form.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            const booking = localBookings.value.find(b => b.id === form.id);
            if (booking) booking.status = form.status;
            modalClose();
        },
    });
};
</script>

<template>
    <Head title="Bookings" />

    <AdminLayout>
        <div>
            <div
                class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden px-4 py-4"
            >
                <div
                    class="flex flex-col sm:flex-row sm:items-center gap-3 mb-2"
                >
                    <h1 class="text-xl font-bold text-stone-800">Bookings</h1>
                </div>

                <Table
                    :data="localBookings"
                    :columns="tableColumns"
                    :actions="tableActions"
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
                            <span v-if="row.walk_in_guests > 0">
                                + {{ row.walk_in_guests }} walk-in
                            </span>
                        </p>
                        <p
                            v-if="row.guest_request_notes"
                            class="text-xs text-stone-400 italic mt-1"
                        >
                            "{{ row.guest_request_notes }}"
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
                                v-for="(addon, index) in row.package_add_ons"
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
                                {{ formatPhone(row.payment_account_number) }}
                            </p>
                        </template>
                    </template>

                    <!-- Date -->
                    <template #date="{ row }">
                        <p class="text-sm text-stone-600">
                            {{ row.date ? formatDate(row.date) : "—" }}
                        </p>
                    </template>

                    <!-- Status -->
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

                    <!-- Actions -->
                    <template #actions="{ row }">
                        <div class="flex flex-col gap-1">
                            <Link
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-3 py-2 rounded-md transition-colors text-center text-sm"
                                :href="route('admin.messages')"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-envelope"
                                />
                                Message
                            </Link>

                            <button
                                v-if="row.status === 'pending'"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-3 py-2 rounded-md transition-colors text-sm"
                                @click="openStatusModal(row, 'confirm')"
                            >
                                <font-awesome-icon icon="fa-solid fa-check" />
                                Confirm
                            </button>

                            <button
                                v-if="
                                    ['pending', 'confirmed'].includes(
                                        row.status,
                                    )
                                "
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-3 py-2 rounded-md transition-colors text-sm"
                                @click="openStatusModal(row, 'cancel')"
                            >
                                <font-awesome-icon icon="fa-solid fa-xmark" />
                                Cancel
                            </button>

                            <button
                                v-if="row.status === 'confirmed'"
                                class="bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-2 rounded-md transition-colors text-sm"
                                @click="openStatusModal(row, 'complete')"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-check-double"
                                />
                                Complete
                            </button>
                        </div>
                    </template>
                </Table>
            </div>

            <!-- Status Modal -->
            <Modal
                :show="modal.type.value === 'Status'"
                @close="!form.processing && modalClose()"
                :maxWidth="'sm'"
            >
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h2 class="text-lg font-medium text-gray-900">
                        <font-awesome-icon :icon="modal.icon.value" />
                        {{ modal.title.value }}
                    </h2>

                    <div class="mt-6">
                        <p class="text-gray-600">
                            {{
                                currentAction
                                    ? actionConfig[currentAction].description
                                    : "Are you sure?"
                            }}
                        </p>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <SecondaryButton
                            :class="{ 'opacity-25': form.processing }"
                            @click="modalClose"
                            :disabled="form.processing"
                        >
                            Cancel
                        </SecondaryButton>

                        <DangerButton
                            class="flex items-center gap-1"
                            :class="{ 'opacity-25': form.processing }"
                            @click="submit"
                            :disabled="form.processing"
                        >
                            <div class="text-sm" v-if="form.processing">
                                <font-awesome-icon
                                    icon="fa-solid fa-spinner"
                                    spin
                                />
                            </div>
                            Confirm
                            <font-awesome-icon icon="fa-solid fa-thumbs-up" />
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AdminLayout>
</template>
