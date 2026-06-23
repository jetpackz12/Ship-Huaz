<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Table from "@/Components/Table.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextAreaInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useFormatter } from "@/Composables/useFormatter";
import { useModal } from "@/Composables/useModal";

const tableColumns = [
    { key: "id", label: "#" },
    { key: "title", label: "Title" },
    { key: "description", label: "Description" },
    { key: "price", label: "Price", slot: "price" },
    { key: "status", label: "Status", slot: "status" },
    { key: "actions", label: "Actions", slot: "actions" },
];

const tableData = ref([
    {
        id: 1,
        title: "Captain's Experience",
        description: "Uniforms + immersive ship photo ops for all guests",
        price: "500",
        status: "active",
    },
    {
        id: 2,
        title: "Catering Package",
        description: "Filipino-style buffet with drinks for your group",
        price: "3500",
        status: "inactive",
    },
    {
        id: 3,
        title: "Event Decoration",
        description: "Themed ship décor, balloons, and table setup",
        price: "2000",
        status: "active",
    },
    {
        id: 4,
        title: "Photo & Video Coverage",
        description: "Professional photographer for the duration of event",
        price: "4500",
        status: "active",
    },
    {
        id: 5,
        title: "Sound System",
        description: "PA system with wireless microphone and playlist setup",
        price: "1500",
        status: "inactive",
    },
    {
        id: 6,
        title: "Guest Entry Passes",
        description: "Guest entry passes for all guests",
        price: "500",
        status: "active",
    },
]);

const tableActions = {
    isDateFilterShow: false,
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

const { formatAmount } = useFormatter();

const modal = useModal();

const addModalOpen = () => {
    modal.title.value = "Add Package Add-On";
    modal.type.value = "Add";
    modal.icon.value = "fa-solid fa-plus-circle";
    modal.openModal();
};

const editModalOpen = (item) => {
    form.id = item.id;
    form.title = item.title;
    form.description = item.description;
    form.price = item.price;
    form.status = item.status;

    modal.title.value = "Edit Package Add-On";
    modal.type.value = "Edit";
    modal.icon.value = "fa-solid fa-pen-to-square";
    modal.openModal();
};

const deleteModalOpen = (item) => {
    form.id = item.id;
    
    modal.title.value = "Delete Package Add-On";
    modal.type.value = "Delete";
    modal.icon.value = "fa-solid fa-trash";
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
    title: "",
    description: "",
    price: "",
    status: "",
});

const submit = () => {
    console.log(form);
};
</script>

<template>
    <Head title="Package Add-Ons" />

    <AdminLayout>
        <div>
            <div
                class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden px-4 py-4"
            >
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2"
                >
                    <h1 class="text-xl font-bold text-stone-800">
                        Package Add-Ons
                    </h1>
                    <PrimaryButton
                        class="flex items-center gap-1"
                        @click="addModalOpen"
                    >
                        <font-awesome-icon icon="fa-solid fa-plus-circle" />
                        Add Package Add-On
                    </PrimaryButton>
                </div>

                <Table
                    :data="tableData"
                    :columns="tableColumns"
                    :actions="tableActions"
                >
                    <template #price="{ value }">
                        {{ formatAmount(value) }}
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
                            <button
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-3 py-2 rounded-md transition-colors"
                                @click="deleteModalOpen(row)"
                            >
                                <font-awesome-icon icon="fa-solid fa-trash" />
                                Delete
                            </button>
                        </div>
                    </template>
                </Table>
            </div>

            <!-- Add & Edit Modal -->
            <Modal
                :show="modal.type.value === 'Add' || modal.type.value === 'Edit'"
                @close="modalClose"
                :maxWidth="'lg'"
            >
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h2 class="text-lg font-medium text-gray-900">
                        <font-awesome-icon :icon="modal.icon.value" />
                        {{ modal.title.value }}
                    </h2>

                    <div class="mt-6">
                        <InputLabel for="title" value="Title" />

                        <TextInput
                            id="title"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.title"
                            required
                            placeholder="e.g. Captain's Experience"
                        />

                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="description" value="Description" />

                        <TextAreaInput
                            id="description"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.description"
                            rows="4"
                            required
                            placeholder="e.g. Lorem ipsum dolor sit amet"
                        />

                        <InputError
                            :message="form.errors.description"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="price" value="Price" />

                        <TextInput
                            id="price"
                            type="number"
                            class="mt-1 block w-full"
                            v-model="form.price"
                            required
                            placeholder="e.g. 1000"
                        />

                        <InputError :message="form.errors.price" class="mt-2" />
                    </div>

                    <div class="mt-2" v-if="modal.type.value === 'Edit'">
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

            <!-- Delete Modal -->
            <Modal
                :show="modal.type.value === 'Delete'"
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
                            Are you sure you want to delete this package add-on?
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
                            Detete
                            <font-awesome-icon icon="fa-solid fa-trash" />
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AdminLayout>
</template>
