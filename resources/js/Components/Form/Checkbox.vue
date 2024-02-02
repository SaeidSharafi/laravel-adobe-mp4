<script setup>
import {computed} from "vue";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: "checkbox",
        validator: (value) => ["checkbox", "radio", "switch"].includes(value),
    },
    label: {
        type: String,
        default: null,
    },
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    isDisabled: {
        type: Boolean,
        default: false,
    },
    modelValue: {
        type: [Array, String, Number, Boolean],
        default: null,
    },
    inputValue: {
        type: [String, Number, Boolean],
        required: true,
    },
});
const emit = defineEmits(["update:modelValue", "update:checked"]);

const toggle = () => {
    if (props.isDisabled){
        return;
    }
    computedValue.value = !computedValue.value;

    // emit("update:modelValue", computedValue);
    // emit("update:checked", computedValue);
};
const computedValue = computed({
    get: () => props.modelValue == null ? props.checked : props.modelValue,
    set: (value) => {
        emit("update:modelValue", value);
        emit("update:checked", value);
    },
});
const backgroundStyles = computed(() => {
    if (computedValue.value) {
        return 'bg-green-400'
    }
    return 'bg-gray-300'
})
const indicatorStyles = computed(() => {
    if (computedValue.value) {
        return '-translate-x-6'
    }
    return '';
})
const inputType = computed(() =>
    props.type === "radio" ? "radio" : "checkbox"
);

</script>

<template>
    <div v-if="type === 'switch'" class="flex items-center">
        <label v-if="label" class="pl-2" :class="isDisabled ? 'text-gray-400' : ''">{{ label }}</label>
        <div v-if="type === 'switch'"
             @click="toggle"
             @keydown.space.prevent="toggle"
             class="w-14 h-7 flex items-center rounded-full p-1 duration-300 ease-in-out"
             :class="backgroundStyles">
            <div class="bg-white w-6 h-6 rounded-full shadow-md transform duration-300 ease-in-out"
                 :class="indicatorStyles"></div>
        </div>
    </div>

    <label v-else :class="isDisabled ? 'text-gray-400' : ''">

        <input
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            v-model="computedValue"
            :type="inputType"
            :name="name"
            :disabled="isDisabled"
            :value="inputValue"
        />
        <span class="check"/>
        <span class="pr-2">{{ label }}</span>
    </label>
</template>
