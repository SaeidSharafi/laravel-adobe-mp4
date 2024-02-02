<script setup>
import {computed, useSlots} from "vue";

defineProps({
    label: {
        type: String,
        default: null,
    },
    labelWrapper: {
        type: Boolean,
        default: false
    },
    labelClass: {
        type: String,
        default: null,
    },
    labelFor: {
        type: String,
        default: null,
    },
    help: {
        type: String,
        default: null,
    },
});

const slots = useSlots();

const wrapperClass = computed(() => {
    const base = [];
    const slotsLength = slots.default().length;

    if (slotsLength > 1) {
        base.push("grid grid-cols-1 gap-3");
    }

    if (slotsLength === 2) {
        base.push("md:grid-cols-2");
    }

    return base;
});
</script>

<template>
    <div class="mb-4 last:mb-0" v-if="labelWrapper">
        <label v-if="label" :for="labelFor" class="text-gray-600 dark:text-gray-200 block font-bold mb-2" :class="labelClass">
            <div :class="wrapperClass">
                <slot/>
                <span class="mr-2 text-sm text-gray-600 dark:text-gray-200" v-if="label">{{label}}</span>
            </div>
        </label>
        <div v-if="help" class="text-xs text-gray-500 dark:text-slate-400 mt-1">
            {{ help }}
        </div>
    </div>
    <div class="mb-4 last:mb-0" v-else>
        <label v-if="label" :for="labelFor" class="text-gray-600  dark:text-gray-200 block font-bold mb-2" :class="labelClass">{{
                label
            }}</label>
        <div :class="wrapperClass">
            <slot/>
        </div>
        <div v-if="help" class="text-xs text-gray-500 dark:text-gray-200 mt-1">
            {{ help }}
        </div>
    </div>
</template>
