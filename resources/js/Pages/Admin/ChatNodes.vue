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
import { ref, watch } from "vue";
import { useModal } from "@/Composables/useModal";

const props = defineProps({
    chatBotNodes: Array,
});

const tableColumns = [
    { key: "id", label: "ID" },
    { key: "node_key", label: "Node Key" },
    { key: "message", label: "Message", slot: "message" },
    { key: "status", label: "Status", slot: "status" },
    { key: "actions", label: "Actions", slot: "actions" },
];

const tableData = ref(
    props.chatBotNodes.map((item, index) => ({
        id: index + 1,
        chat_node_id: item.id,
        node_key: item.node_key,
        message: item.message,
        status: item.status,
    })),
);

watch(
    () => props.chatBotNodes,
    (newChatBotNodes) => {
        tableData.value = newChatBotNodes.map((item, index) => ({
            id: index + 1,
            chat_node_id: item.id,
            node_key: item.node_key,
            message: item.message,
            status: item.status,
        }));
    },
);

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
    form.id = item.chat_node_id;
    form.node_key = item.node_key;
    form.message = item.message;
    form.status = item.status;

    modal.title.value = "Edit Chat Node";
    modal.type.value = "Edit";
    modal.icon.value = "fa-solid fa-pen-to-square";
    modal.openModal();
};

const deleteModalOpen = (item) => {
    form.id = item.chat_node_id;

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
    if (modal.type.value === "Add")
        form.post(route("admin.chat-nodes.store"), {
            onSuccess: () => modalClose(),
        });
    else if (modal.type.value === "Edit")
        form.put(route("admin.chat-nodes.update", form.id), {
            onSuccess: () => modalClose(),
        });
    else if (modal.type.value === "Delete")
        form.delete(route("admin.chat-nodes.destroy", form.id), {
            onSuccess: () => modalClose(),
        });
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
                    <h1 class="text-xl font-bold text-stone-800">Chat Nodes</h1>
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
                        <span
                            class="text-sm text-gray-600 text-wrap"
                            v-html="value"
                        ></span>
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
                :show="
                    modal.type.value === 'Add' || modal.type.value === 'Edit'
                "
                @close="!form.processing && modalClose()"
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
                            @keypress.enter="submit"
                        />

                        <InputError
                            :message="form.errors.node_key"
                            class="mt-2"
                        />
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
                            :class="{ 'opacity-25': form.processing }"
                            @click="modalClose"
                            :disabled="form.processing"
                        >
                            Cancel
                        </SecondaryButton>

                        <PrimaryButton
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
                            Save
                            <font-awesome-icon icon="fa-solid fa-paper-plane" />
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>

            <!-- Delete Modal -->
            <Modal
                :show="modal.type.value === 'Delete'"
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
                            Are you sure you want to delete this chat node?
                        </p>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <SecondaryButton
                            class="flex items-center"
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
