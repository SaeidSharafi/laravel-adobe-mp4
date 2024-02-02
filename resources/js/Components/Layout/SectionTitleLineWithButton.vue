<script setup>
import {mdiCog} from "@mdi/js";
import {computed, useSlots} from "vue";
import BaseIcon from "@/Components/Layout/BaseIcon.vue";
import BaseButton from "@/Components/Form/BaseButton.vue";
import IconRounded from "@/Components/Layout/IconRounded.vue";

defineProps({
    icon: {
        type: String,
        default: null,
    },
    title: {
        type: String,
        required: true,
    },
    showDefaultButton:{
        type: Boolean,
        default: false
    },
    main: Boolean,
});

const hasSlot = computed(() => useSlots().default);
</script>

<template>
    <section
        :class="{ 'pt-6': !main }"
        class="mb-6 flex items-center justify-between"
    >
        <div class="flex items-center justify-start">
            <IconRounded
                v-if="icon && main"
                :icon="icon"
                color="light"
                class="ml-3"
                bg
            />
            <BaseIcon v-else-if="icon" :path="icon" class="ml-2" size="20" />
            <h1 :class="main ? 'text-3xl' : 'text-2xl'" class="leading-tight">
                {{ title }}
            </h1>
        </div>
        <slot v-if="hasSlot" />
        <BaseButton v-else-if="showDefaultButton" :icon="mdiCog" color="whiteDark" />
    </section>
</template>
