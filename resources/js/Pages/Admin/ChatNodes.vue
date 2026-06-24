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
import { useModal } from "@/Composables/useModal";

const tableColumns = [
    { key: "id", label: "ID" },
    { key: "node_key", label: "Node Key" },
    { key: "message", label: "Message", slot: "message" },
    { key: "status", label: "Status", slot: "status" },
    { key: "actions", label: "Actions", slot: "actions" },
];

const tableData = ref([
    {
        id: 1,
        node_key: "Venue Booking",
        message: "Our iconic ship-shaped venue spaces start at ₱5,000 per event. We have 8 different event spaces for intimate dinners to grand receptions. Would you like to know more?",
        status: "active",
    },
    {
        id: 2,
        node_key: "Accommodations",
        message: "Spend the night aboard the Ship Hauz! Our nautical-themed rooms start at ₱1,200/night. Enjoy stunning views of the Bohol landscape right from your room.",
        status: "active",
    },
    {
        id: 3,
        node_key: "Island Tours",
        message: "Explore Northern Bohol's natural wonders! We offer 12 island tour routes starting at ₱800/person. Hidden waterfalls, pristine beaches, and more await you.",
        status: "active",
    },
    {
        id: 4,
        node_key: "Tour Routes",
        message: "We have 12 curated routes including island-hopping tours, waterfall treks, heritage site visits, and snorkeling adventures around Northern Bohol. All tours are guided and depart from Butal Ship Hauz.",
        status: "active",
    },
    {
        id: 5,
        node_key: "Event Planning",
        message: "Let our crew handle everything! Event planning packages start at ₱15,000 and cover weddings, debuts, corporate events, and reunions — coordination, catering, and venue all included.",
        status: "active",
    },
    {
        id: 6,
        node_key: "Weddings at Ship Hauz",
        message: "Say 'I do' at Bohol's most iconic venue! Our wedding packages include full catering, coordination, venue styling, and accommodation for the couple. Contact us for a custom quote tailored to your dream day.",
        status: "active",
    },
    {
        id: 7,
        node_key: "Dining & Catering",
        message: "Savor fresh Bohol flavors at our on-site restaurant! Dining starts at ₱350/person. We also offer custom catering services for groups of all sizes — barkada, family reunions, corporate events.",
        status: "active",
    },
    {
        id: 8,
        node_key: "Photography Venue",
        message: "The Ship Hauz is Bohol's most photographed landmark! Photography sessions start at ₱2,500. Perfect for prenuptial shoots, fashion photography, debut pictorials, and commercial production.",
        status: "active",
    },
    {
        id: 9,
        node_key: "Location & Hours",
        message: "Butal Ship Hauz, Talibon, Bohol, Philippines Open daily: 7:00 AM – 9:00 PM Reservations: 8:00 AM – 6:00 PM +63 (0) 912 345 6789",
        status: "active",
    },
    {
        id: 10,
        node_key: "Directions",
        message: "We're located in Talibon, the gateway to Northern Bohol's hidden gems. From Tagbilaran City, take a bus or van to Talibon (approx. 2–3 hours). Ask locals for 'Butal Ship Hauz' — everyone knows it!",
        status: "active",
    },
    {
        id: 11,
        node_key: "Contact Us",
        message: "Our crew is ready to help! +63 (0) 912 345 6789 reservations@butalshiphauz.com.ph For reservations and inquiries, we respond within 24 hours.",
        status: "active",
    },
    {
        id: 12,
        node_key: "Our Packages",
        message: "Day Tour – ₱999 (venue access, 1 meal, guided tour) Overnight – ₱2,499 (room, 3 meals, island tour, venue access) Weekend – ₱4,299 (2 nights, all meals, 2 tours, event hall) Custom packages also available — contact us!",
        status: "active",
    },
    {
        id: 13,
        node_key: "How to Book",
        message: "Booking is easy! 3 ways to reserve: 1️ Call us at +63 (0) 912 345 6789 2️ Email reservations@butalshiphauz.com.ph 3️ Use the 'Book a Reservation' button on this page A 50% down payment is required to confirm bookings.",
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

const modal = useModal();

const addModalOpen = () => {
    modal.title.value = "Add Chat Node";
    modal.type.value = "Add";
    modal.icon.value = "fa-solid fa-plus-circle";
    modal.openModal();
};

const editModalOpen = (item) => {
    form.id = item.id;
    form.node_key = item.node_key;
    form.message = item.message;
    form.status = item.status;

    modal.title.value = "Edit Chat Node";
    modal.type.value = "Edit";
    modal.icon.value = "fa-solid fa-pen-to-square";
    modal.openModal();
};

const deleteModalOpen = (item) => {
    form.id = item.id;
    
    modal.title.value = "Delete Chat Node";
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
    node_key: "",
    message: "",
    status: "",
});

const submit = () => {
    console.log(form);
};
</script>

<template>
    <Head title="Chat Nodes" />

    <AdminLayout>
        <div>
            <div
                class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden px-4 py-4"
            >
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2"
                >
                    <h1 class="text-xl font-bold text-stone-800">
                        Chat Nodes
                    </h1>
                    <PrimaryButton
                        class="flex items-center gap-1"
                        @click="addModalOpen"
                    >
                        <font-awesome-icon icon="fa-solid fa-plus-circle" />
                        Add Chat Node
                    </PrimaryButton>
                </div>

                <Table
                    :data="tableData"
                    :columns="tableColumns"
                    :actions="tableActions"
                >
                    <template #message="{ value }">
                        <span class="text-sm text-gray-600 text-wrap" v-html="value"></span>
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
                        <InputLabel for="node_key" value="Node Key" />

                        <TextInput
                            id="node_key"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.node_key"
                            required
                            placeholder="e.g. Main"
                        />

                        <InputError :message="form.errors.node_key" class="mt-2" />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="message" value="Message" />

                        <TextAreaInput
                            id="message"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.message"
                            rows="5"
                            required
                            placeholder="e.g. Lorem ipsum dolor sit amet"
                        />

                        <InputError
                            :message="form.errors.message"
                            class="mt-2"
                        />
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
                            Are you sure you want to delete this chat node?
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
