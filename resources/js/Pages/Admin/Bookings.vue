<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Table from "@/Components/Table.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Link, Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useFormatter } from "@/Composables/useFormatter";
import { useModal } from "@/Composables/useModal";

const tableColumns = [
    { key: "id", label: "ID" },
    { key: "client", label: "Client" },
    { key: "event_details", label: "Event Details", slot: "event_details" },
    { key: "package", label: "Package" },
    { key: "package_add_ons", label: "Package Add-Ons", slot: "package_add_ons" },
    { key: "contact", label: "Contact", slot: "contact" },
    { key: "date", label: "Date", slot: "date" },
    { key: "status", label: "Status", slot: "status" },
    { key: "actions", label: "Actions", slot: "actions" },
];

const tableData = ref([
    {
        id: 1,
        client: "Alex",
        event_details: [
            {
                event_type: "Birthday Party",
                date: "2026-06-24",
                time_slot: "Full Day (8:00 AM – 8:00 PM)",
                guests: 30,
            },
        ],
        package: "Upper Deck Experience 3500",
        package_add_ons: [
            { id: 1, title: "Captain's Experience", price: "500" },
            { id: 2, title: "Catering Package", price: "3500" },
            { id: 3, title: "Event Decoration", price: "2000" },
            { id: 4, title: "Photo & Video Coverage", price: "4500" },
            { id: 5, title: "Sound System", price: "1500" },
        ],
        contact: [
            {
                name: "John Doe",
                email: "Q8S4F@example.com",
                phone: "09123456789",
            },
        ],
        date: "2026-06-24",
        status: "pending",
    },
]);

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

const { formatAmount, formatDate } = useFormatter();

const modal = useModal();

const editStatusModalOpen = (item) => {
    form.id = item.id;

    modal.title.value = "Edit Status";
    modal.type.value = "Status";
    modal.icon.value = "fa-solid fa-pen-to-square";
    modal.openModal();
};

const modalClose = () => {
    form.reset();
    modal.closeModal();
};

const form = useForm({
    id: "",
});

const submit = () => {
    console.log(form);
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
                    :data="tableData"
                    :columns="tableColumns"
                    :actions="tableActions"
                >

                    <template #event_details="{ value }">
                        <div v-for="item in value" :key="item.id">
                            <p class="text-sm text-stone-600">
                                {{ item.event_type }}
                            </p>
                            <p class="text-sm text-stone-600">
                                {{ formatDate(item.date) }}
                            </p>
                            <p class="text-sm text-stone-600">
                                {{ item.time_slot }}
                            </p>
                            <p class="text-sm text-stone-600">
                                {{ item.guests }}
                            </p>
                        </div>
                    </template>

                    <template #package_add_ons="{ value }">
                        <div v-for="item in value" :key="item.id">
                            <p class="text-sm text-stone-600">
                                {{ item.title }}
                            </p>
                            <p class="text-sm text-stone-600">
                                {{ formatAmount(item.price) }}
                            </p>
                        </div>
                    </template>

                    <template #contact="{ value }">
                        <div v-for="item in value" :key="item.id">
                            <p class="text-sm text-stone-600">
                                {{ item.name }}
                            </p>
                            <p class="text-sm text-stone-600">
                                {{ item.email }}
                            </p>
                            <p class="text-sm text-stone-600">
                                {{ item.phone }}
                            </p>
                        </div>
                    </template>

                    <template #date="{ value }">
                        <p class="text-sm text-stone-600">
                            {{ formatDate(value) }}
                        </p>
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
                        <div class="flex flex-col  gap-1">
                            <Link
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-3 py-2 rounded-md transition-colors"
                                :href="route('admin.messages')"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-envelope"
                                />
                                Message
                            </Link>
                            <button
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-3 py-2 rounded-md transition-colors"
                                @click="editStatusModalOpen(row)"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-check"
                                />
                                Confirm
                            </button>
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-3 py-2 rounded-md transition-colors"
                                @click="editStatusModalOpen(row)"
                            >
                                <font-awesome-icon icon="fa-solid fa-xmark" />
                                Cancel
                            </button>
                            <button
                                class="bg-green-500 hover:bg-green-600 text-white font-semibold px-3 py-2 rounded-md transition-colors"
                                @click="editStatusModalOpen(row)"
                            >
                                <font-awesome-icon icon="fa-solid fa-check-double" />
                                Complete
                            </button>
                        </div>
                    </template>
                </Table>
            </div>

            <!-- Status Modal -->
            <Modal
                :show="modal.type.value === 'Status'"
                @close="modalClose"
                :maxWidth="'sm'"
            >
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h2 class="text-lg font-medium text-gray-900">
                        <font-awesome-icon :icon="modal.icon.value" />
                        {{ modal.title.value }}
                    </h2>

                    <div class="mt-6">
                        <p class="text-gray-600">
                            Are you sure you want to update the status of this booking?
                        </p>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <SecondaryButton
                            class="flex items-center"
                            @click="modalClose"
                        >
                            Cancel
                        </SecondaryButton>

                        <DangerButton
                            class="flex items-center gap-1"
                            @click="submit"
                        >
                            Confirm
                            <font-awesome-icon icon="fa-solid fa-thumbs-up" />
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AdminLayout>
</template>
