<template>
    <div class="bg-white dark:bg-slate-800 flex items-start w-full rounded-lg p-6 h-full card">
        <component
            :is="href ? Link : 'div'"
            :href="href"
            class="w-full h-full"
        >
            <div class="card-body flex">
                <div class="card-title flex items-start justify-between mb-2 w-1/3">
                    <div class="avatar flex-shrink-0">
                        <BaseIcon :class="computedColor" :path="icon" :size="computedSize" :h="computedHeight"
                                  :w="computedWidth"/>
                    </div>
                </div>
                <div class="w-2/3">
                    <span class="block text-l font-bold mb-1">{{ title }}</span>
                    <h3 class="text-xl mb-1">
                        {{ value }}
                        <span style="font-size: 14px">{{ unitString }}</span>
                    </h3>
                    <div class="text-base" :class="growthColor(growth)">
                        <BaseIcon :class="growthColor(growth)"
                                  :path="growthIcon(growth)"
                                  size="14"
                                  h="h-[14px]" w="w-[14px]"/>
                         <span class="inline-block mr-2 dir-ltr">{{ growthFormat(growth) }}</span>
                    </div>
                </div>
            </div>
        </component>
    </div>
</template>

<script setup>
import BaseIcon from "@/Components/Layout/BaseIcon.vue";
import {computed} from "vue";
import {mdiArrowDown, mdiArrowUp} from "@mdi/js";
import {useI18n} from 'vue-i18n';
import {Link} from "@inertiajs/vue3";

const {t} = useI18n({});

const props = defineProps({
    title: {
        Type: String,
        default: ''
    },
    value: {
        Type: Number,
        default: 0
    },
    href: {
        Type: String,
        default: null
    },
    growth: {
        Type: Number,
        default: 0
    },
    unit: {
        Type: String,
        default: null
    },
    desc: {
        Type: String,
        default: ''
    },
    icon: {
        type: String,
        default: null,
        required: true,
    },
    iconSize: {
        type: String,
        default: null,
        validator: (value) => ["small", "normal", "larg"].includes(value),
    },
    color: {
        type: String,
        default: 'success',
    }
})

const unitString = props.unit || t('dashboard.units.person');

function growthColor(growth) {
    if (growth < 0) {
        return 'text-red-400';
    }
    return 'text-green-400';
}

function growthIcon(growth) {
    if (growth < 0) {
        return mdiArrowDown;
    }
    return mdiArrowUp;
}

function growthFormat(growth) {
    if (growth < 0) {
        return growth + '%';
    }
    return growth + '%';
}

const computedSize = computed(() => {
    switch (props.iconSize) {
        case "xlarg":
            return 68
        case "larg":
            return 32
        default:
            return 22
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
        case "success":
            return "text-green-500"
        case "danger":
            return "text-red-500"
        case "info":
            return "text-blue-500"
        case "yellow":
            return "text-yellow-500"
        case "warning":
            return "text-orange-500"
        case "purple":
            return "text-purple-500"
        default:
            return props.color
    }
})
</script>

<style scoped>

</style>
