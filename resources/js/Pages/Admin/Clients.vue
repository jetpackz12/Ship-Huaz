<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Table from "@/Components/Table.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useFormatter } from "@/Composables/useFormatter";
import { useModal } from "@/Composables/useModal";

const tableColumns = [
    { key: "id", label: "ID" },
    { key: "first_name", label: "First Name" },
    { key: "middle_name", label: "Middle Name" },
    { key: "last_name", label: "Last Name" },
    { key: "birth_date", label: "Birth Date", slot: "birth_date" },
    { key: "phone", label: "Phone" },
    { key: "address", label: "Address" },
    { key: "email", label: "Email" },
    { key: "created_at", label: "Date", slot: "date" },
    { key: "status", label: "Status", slot: "status" },
    { key: "actions", label: "Actions", slot: "actions" },
];

const tableData = ref([
    {
        id: 1,
        first_name: "John",
        middle_name: "Doe",
        last_name: "Doe",
        birth_date: "1990-01-01",
        phone: "0987654321",
        address: "123 Main St",
        email: "john@example.com",
        created_at: "2026-06-23",
        status: "active",
    },
]);

const tableActions = {
    isDateFilterShow: true,
    isPerPageShow: true,
    isSearchShow: true,
};

const statusConfig = {
    active: {
        label: "Active",
        classes: "bg-blue-100 text-blue-700 border border-blue-200",
    },
    inactive: {
        label: "Inactive",
        classes: "bg-red-100 text-red-700 border border-red-200",
    },
};

const { formatDate } = useFormatter();

const modal = useModal();

const editModalOpen = (item) => {
    form.status = item.status;

    modal.title.value = "Edit Client Status";
    modal.type.value = "Edit";
    modal.icon.value = "fa-solid fa-pen-to-square";
    modal.openModal();
};

const modalClose = () => {
    form.reset();
    modal.closeModal();
};

const statusFormat = ref([
    { value: "", label: "-- Select status --", disabled: true },
    { value: "active", label: "Active" },
    { value: "inactive", label: "Inactive" },
]);

const form = useForm({
    id: "",
    status: "",
});

const submit = () => {
    console.log(form);
};
</script>

<template>
    <Head title="Clients" />

    <AdminLayout>
        <div>
            <div
                class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden px-4 py-4"
            >
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2"
                >
                    <h1 class="text-xl font-bold text-stone-800">Clients</h1>
                </div>
                <Table
                    :data="tableData"
                    :columns="tableColumns"
                    :actions="tableActions"
                    :dateKey="'created_at'"
                >
                    <template #birth_date="{ value }">
                        {{ formatDate(value) }}
                    </template>

                    <template #date="{ value }">
                        {{ formatDate(value) }}
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
                        <div class="flex items-center justify-center gap-1">
                            <button
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-3 py-2 rounded-md transition-colors"
                                @click="editModalOpen(row)"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-pen-to-square"
                                />
                                Edit
                            </button>
                        </div>
                    </template>
                </Table>
            </div>

            <!-- Add & Edit Modal -->
            <Modal
                :show="
                    modal.type.value === 'Add' || modal.type.value === 'Edit'
                "
                @close="modalClose"
                :maxWidth="'sm'"
            >
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h2 class="text-lg font-medium text-gray-900">
                        <font-awesome-icon :icon="modal.icon.value" />
                        {{ modal.title.value }}
                    </h2>

                    <div class="mt-2">
                        <InputLabel for="status" value="Status" />

                        <SelectInput
                            id="status"
                            :options="statusFormat"
                            class="mt-1 block w-full"
                            v-model="form.status"
                            required
                        />

                        <InputError
                            :message="form.errors.status"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6 flex justify-between">
                        <SecondaryButton
                            class="flex items-center"
                            @click="modalClose"
                        >
                            Cancel
                        </SecondaryButton>

                        <PrimaryButton
                            class="flex items-center gap-1"
                            @click="submit"
                        >
                            Save
                            <font-awesome-icon icon="fa-solid fa-paper-plane" />
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AdminLayout>
</template>
