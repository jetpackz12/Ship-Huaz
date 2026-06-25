<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Table from "@/Components/Table.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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
    paymentOptions: Array,
});

const tableColumns = [
    { key: "id", label: "ID" },
    { key: "payment", label: "Payment" },
    { key: "number", label: "Number" },
    { key: "account", label: "Account" },
    { key: "description", label: "Description" },
    { key: "status", label: "Status", slot: "status" },
    { key: "actions", label: "Actions", slot: "actions" },
];

const tableData = ref(
    props.paymentOptions.map((paymentOption, index) => ({
        id: index + 1,
        payment_option_id: paymentOption.id,
        payment: paymentOption.payment,
        number: paymentOption.number,
        account: paymentOption.account,
        description: paymentOption.description,
        status: paymentOption.status,
    })),
);

watch(
    () => props.paymentOptions,
    (newPaymentOptions) => {
        tableData.value = newPaymentOptions.map((paymentOption, index) => ({
            id: index + 1,
            payment_option_id: paymentOption.id,
            payment: paymentOption.payment,
            number: paymentOption.number,
            account: paymentOption.account,
            description: paymentOption.description,
            status: paymentOption.status,
        }));
    },
);

const tableActions = {
    isDateFilterShow: false,
    isPerPageShow: false,
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

const editPaymentOption = (item) => {
    form.id = item.payment_option_id;
    form.payment = item.payment;
    form.number = item.number;
    form.account = item.account;
    form.description = item.description;
    form.status = item.status;

    modal.title.value = "Edit Payment Option";
    modal.type.value = "Edit";
    modal.icon.value = "fa-solid fa-pen-to-square";
    modal.openModal();
};

const closeEditModal = () => {
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
    payment: "",
    number: "",
    account: "",
    description: "",
    status: "",
});

const submit = () => {
    form.put(route("admin.payment-options.update", form.id), {
        onSuccess: () => closeEditModal(),
    });
};
</script>

<template>
    <Head title="Payment Options" />

    <AdminLayout>
        <div>
            <div
                class="bg-white rounded-xl shadow-sm border border-stone-100 overflow-hidden px-4 py-4"
            >
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-2"
                >
                    <h1 class="text-xl font-bold text-stone-800">
                        Payment Options
                    </h1>
                </div>

                <Table
                    :data="tableData"
                    :columns="tableColumns"
                    :actions="tableActions"
                >
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
                        <div class="flex items-center justify-center">
                            <button
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-3 py-2 rounded-md transition-colors"
                                @click="editPaymentOption(row)"
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

            <Modal
                :show="modal.open.value"
                @close="!form.processing && closeEditModal()"
                :maxWidth="'lg'"
            >
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h2 class="text-lg font-medium text-gray-900">
                        <font-awesome-icon :icon="modal.icon.value" />
                        {{ modal.title.value }}
                    </h2>

                    <div class="mt-6">
                        <InputLabel for="payment" value="Payment" />

                        <TextInput
                            id="payment"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.payment"
                            required
                            placeholder="e.g. Gcash"
                        />

                        <InputError
                            :message="form.errors.payment"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="number" value="Number" />

                        <TextInput
                            id="number"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.number"
                            required
                            placeholder="e.g. 0987654321"
                        />

                        <InputError
                            :message="form.errors.number"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-2">
                        <InputLabel for="account" value="Account" />

                        <TextInput
                            id="account"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.account"
                            required
                            placeholder="e.g. Butal Ship Hauz"
                        />

                        <InputError
                            :message="form.errors.account"
                            class="mt-2"
                        />
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
                            @click="closeEditModal"
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
        </div>
    </AdminLayout>
</template>
