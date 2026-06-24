<script setup>
import { onMounted, onBeforeUnmount, ref, computed } from 'vue';

const model = defineModel({
    type: [String, Array],
    required: true,
});

const props = defineProps({
    options: {
        type: Array,
        required: true,
        // Expected format: [{ value: 'val', label: 'Label' }] or ['val1', 'val2']
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    placeholder: {
        type: String,
        default: 'Select...',
    },
});

const container = ref(null);
const searchInput = ref(null);
const isOpen = ref(false);
const search = ref('');

const normalizedOptions = computed(() =>
    props.options.map(option =>
        typeof option === 'object'
            ? option
            : { value: option, label: option, disabled: false }
    )
);

const filteredOptions = computed(() =>
    normalizedOptions.value.filter(option =>
        option.label.toLowerCase().includes(search.value.toLowerCase())
    )
);

const selectedLabels = computed(() => {
    if (!props.multiple) return [];
    return (model.value || []).map(val => {
        const found = normalizedOptions.value.find(o => o.value === val);
        return found ? found.label : val;
    });
});

const isSelected = (value) => {
    if (props.multiple) return (model.value || []).includes(value);
    return model.value === value;
};

const toggleOption = (option) => {
    if (option.disabled) return;

    if (props.multiple) {
        const current = Array.isArray(model.value) ? [...model.value] : [];
        const index = current.indexOf(option.value);
        if (index === -1) current.push(option.value);
        else current.splice(index, 1);
        model.value = current;
    } else {
        model.value = option.value;
        closeDropdown();
    }
};

const removeTag = (value) => {
    const current = Array.isArray(model.value) ? [...model.value] : [];
    model.value = current.filter(v => v !== value);
};

const openDropdown = () => {
    isOpen.value = true;
    setTimeout(() => searchInput.value?.focus(), 50);
};

const closeDropdown = () => {
    isOpen.value = false;
    search.value = '';
};

const handleClickOutside = (e) => {
    if (container.value && !container.value.contains(e.target)) {
        closeDropdown();
    }
};

onMounted(() => {
    document.addEventListener('mousedown', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});

defineExpose({ focus: () => searchInput.value?.focus() });
</script>

<template>
    <div class="w-full" ref="container">
        <!-- Trigger Box -->
        <div
            class="min-h-[42px] w-full rounded-md border border-gray-300 bg-white px-2 py-1.5 flex flex-wrap gap-1 items-center cursor-text shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500"
            @click="openDropdown"
        >
            <!-- Tags (multiple mode) -->
            <template v-if="multiple">
                <span
                    v-for="(val, index) in (model || [])"
                    :key="val"
                    class="inline-flex items-center gap-1 bg-indigo-500 text-white text-sm px-2 py-0.5 rounded"
                >
                    {{ selectedLabels[index] }}
                    <button
                        type="button"
                        class="hover:text-indigo-200 leading-none focus:outline-none"
                        @click.stop="removeTag(val)"
                    >
                        &times;
                    </button>
                </span>
            </template>

            <!-- Single mode display -->
            <span v-else-if="model" class="text-sm text-gray-700">
                {{ normalizedOptions.find(o => o.value === model)?.label || model }}
            </span>

            <!-- Search input -->
            <input
                ref="searchInput"
                v-model="search"
                type="text"
                class="flex-1 min-w-[80px] border-0 outline-none ring-0 focus:ring-0 text-sm bg-transparent placeholder-gray-400 p-0"
                :placeholder="(!multiple && !model) || (multiple && !(model || []).length) ? placeholder : ''"
                @focus="isOpen = true"
                @keydown.escape="closeDropdown"
            />
        </div>

        <!-- Dropdown with slide-up transition -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-2 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 -translate-y-2 scale-95"
        >
            <ul
                v-if="isOpen"
                class="mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-56 overflow-y-auto origin-top"
            >
                <li
                    v-for="option in filteredOptions"
                    :key="option.value"
                    class="px-4 py-2 text-sm cursor-pointer select-none"
                    :class="{
                        'bg-indigo-500 text-white': isSelected(option.value),
                        'text-gray-400 cursor-not-allowed': option.disabled,
                        'hover:bg-gray-100': !isSelected(option.value) && !option.disabled,
                    }"
                    @mousedown.prevent="toggleOption(option)"
                >
                    {{ option.label }}
                </li>

                <li v-if="filteredOptions.length === 0" class="px-4 py-2 text-sm text-gray-400 italic">
                    No options found.
                </li>
            </ul>
        </Transition>
    </div>
</template>