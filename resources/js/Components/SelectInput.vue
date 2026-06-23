<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    options: {
        type: Array,
        required: true,
        // Expected format: [{ value: 'val', label: 'Label' }] or ['val1', 'val2']
    },
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
    >
        <option
            v-for="option in options"
            :key="typeof option === 'object' ? option.value : option"
            :value="typeof option === 'object' ? option.value : option"
            :disabled="typeof option === 'object' ? option.disabled : false"
        >
            {{ typeof option === 'object' ? option.label : option }}
        </option>
    </select>
</template>