<script setup>
import { ref, computed } from "vue";

const props = defineProps({ modelValue: String, min: String });
const emit = defineEmits(["update:modelValue"]);

const DAYS   = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
const MONTHS = ["January","February","March","April","May","June",
                "July","August","September","October","November","December"];

const today = new Date(); today.setHours(0, 0, 0, 0);
const current = ref(new Date(today.getFullYear(), today.getMonth(), 1));

const year  = computed(() => current.value.getFullYear());
const month = computed(() => current.value.getMonth());
const monthLabel = computed(() => `${MONTHS[month.value]} ${year.value}`);

const firstDayOffset = computed(() =>
    new Date(year.value, month.value, 1).getDay()
);
const daysInMonth = computed(() =>
    new Date(year.value, month.value + 1, 0).getDate()
);

const minDate = computed(() =>
    props.min ? new Date(props.min) : today
);

const canGoPrev = computed(() => {
    const prev = new Date(year.value, month.value - 1, 1);
    return prev >= new Date(today.getFullYear(), today.getMonth(), 1);
});

function prevMonth() { current.value = new Date(year.value, month.value - 1, 1); }
function nextMonth() { current.value = new Date(year.value, month.value + 1, 1); }

function isPast(d) {
    return new Date(year.value, month.value, d) < minDate.value;
}

function isToday(d) {
    const date = new Date(year.value, month.value, d);
    return date.getTime() === today.getTime();
}

function isSelected(d) {
    if (!props.modelValue) return false;
    const [y, m, day] = props.modelValue.split("-").map(Number);
    return year.value === y && month.value === m - 1 && d === day;
}

function selectDay(d) {
    if (isPast(d)) return;
    // format as YYYY-MM-DD to match your existing eventDate ref
    const m = String(month.value + 1).padStart(2, "0");
    const day = String(d).padStart(2, "0");
    emit("update:modelValue", `${year.value}-${m}-${day}`);
}
</script>

<template>
  <div class="bg-stone-200 border border-stone-200 rounded-xl overflow-hidden select-none">
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-stone-100">
      <button
        @click="prevMonth"
        :disabled="!canGoPrev"
        class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 text-stone-900 hover:bg-stone-50 disabled:opacity-30 disabled:cursor-not-allowed transition"
      >‹</button>
      <span class="text-sm font-semibold text-stone-800">{{ monthLabel }}</span>
      <button
        @click="nextMonth"
        class="w-8 h-8 flex items-center justify-center rounded-lg border border-stone-200 text-stone-900 hover:bg-stone-50 transition"
      >›</button>
    </div>

    <!-- Day labels -->
    <div class="grid grid-cols-7 px-3 pt-3">
      <div
        v-for="d in DAYS" :key="d"
        class="text-center text-[10px] font-semibold text-stone-400 uppercase tracking-wider pb-2"
      >{{ d }}</div>
    </div>

    <!-- Day grid -->
    <div class="grid grid-cols-7 px-3 pb-3 gap-0.5">
      <!-- empty offset cells -->
      <div v-for="n in firstDayOffset" :key="'e' + n" />

      <button
        v-for="d in daysInMonth" :key="d"
        @click="selectDay(d)"
        :disabled="isPast(d)"
        :class="[
          'text-center text-md py-1.5 rounded-lg transition-all',
          isSelected(d)
            ? 'bg-blue-600 text-white font-semibold'
            : isToday(d)
            ? 'border border-blue-500 text-blue-600 font-semibold hover:bg-blue-50'
            : isPast(d)
            ? 'text-stone-400 cursor-not-allowed'
            : 'text-stone-700 hover:bg-stone-100'
        ]"
      >{{ d }}</button>
    </div>
  </div>
</template>