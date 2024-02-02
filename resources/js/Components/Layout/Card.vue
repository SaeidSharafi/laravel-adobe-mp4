<template>
    <Link
        :href="href">

        <div class="flex flex-col w-full shadow-center rounded overflow-hidden"
             :class="computedColor">
            <div class="flex items-start w-full p-2">
                <div class="pl-2 justify-self-start text-primary" v-if="icon">
                    <BaseIcon class="opacity-40" :path="icon" :size="computedSize" :h="computedHeight" :w="computedWidth"/>
                </div>
                <div class="flex flex-col justify-start w-full">
                    <div class="text-2xl row-span-3 col-start-2  justify-self-start whitespace-nowrap">{{ title }}</div>
                    <div class="row-span-3 col-start-2  justify-self-start whitespace-nowrap py-2">{{ value }}</div>
                    <div class="row-span-3 col-start-2  justify-self-start whitespace-nowrap" v-if="desc">{{ desc }}</div>
                </div>
            </div>
            <div v-if="accent" class="h-4" :class="computedAccentColor">
            </div>
        </div>
    </Link>
</template>

<script setup>
import BaseIcon from "@/Components/Layout/BaseIcon.vue";
import {Link} from '@inertiajs/vue3'
import {computed} from "vue";

const props = defineProps({
    title: {
        Type: String,
        default: ''
    },
    value: {
        Type: String,
        default: ''
    },
    desc: {
        Type: String,
        default: ''
    },
    icon: {
        type: String,
        default: null,
    },
    iconSize: {
        type: String,
        default: null,
        validator: (value) => ["small", "normal", "larg", "xlarg"].includes(value),
    },
    href: {
        type: String,
        default: null,
    },
    color: {
        type: String,
        default: 'green',
        validator: (value) => ['green', 'red', 'yellow', 'orange', 'purple'].includes(value)
    },
    accent: {
        type: Boolean,
        default: false
    }
})
const computedSize = computed(() => {
    switch (props.iconSize) {
        case "xlarg":
            return 68
        case "larg":
            return 64
        default:
            return 32
    }
})
const computedWidth = computed(() => {
    switch (props.iconSize) {
        case "xlarg":
            return "w-20"
        case "larg":
            return "w-9"
        default:
            return "w-6"
    }
})
const computedHeight = computed(() => {
    switch (props.iconSize) {
        case "xlarg":
            return "h-20"
        case "larg":
            return "h-9"
        default:
            return "h-6"
    }
})
const computedColor = computed(() => {
    switch (props.color) {
        case "green":
            return "bg-green-500 text-white"
        case "red":
            return "bg-red-500 text-white"
        case "yellow":
            return "bg-yellow-500"
        case "orange":
            return "bg-orange-500"
        case "purple":
            return "bg-purple-500 text-white"
    }
})
const computedAccentColor = computed(() => {
    switch (props.color) {
        case "green":
            return "bg-green-600 text-white"
        case "red":
            return "bg-red-600 text-white"
        case "yellow":
            return "bg-yellow-600"
        case "orange":
            return "bg-orange-600"
        case "purple":
            return "bg-purple-600 text-white"
    }
})
</script>

<style scoped>

</style>
