<template>
        <span class="rounded px-1" :title="label">
            <BaseIcon v-if="icon" :path="icon" :size="iconSize" />
        </span>

</template>

<script setup>
import BaseIcon from "@/Components/Layout/BaseIcon.vue";

const props = defineProps({
    label: {
        type: [String, Number],
        default: null,
    },
    icon: {
        type: String,
        default: null,
    },
    iconSize: {
        type: [String, Number],
        default: null,
    }
});

</script>

<style scoped>

</style>
