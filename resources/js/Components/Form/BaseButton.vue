<script setup>
import {computed} from "vue";
import {Link, usePage} from '@inertiajs/vue3'
import {getButtonColor} from "@/utils/colors";
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
    },
    href: {
        type: String,
        default: null,
    },
    target: {
        type: String,
        default: null,
    },
    routeName: {
        type: String,
        default: null,
    },
    routeParams: {
        type: [String, Number, Array, Object],
        default: null,
    },
    type: {
        type: String,
        default: null,
    },
    color: {
        type: String,
        default: "white",
    },
    as: {
        type: String,
        default: 'a',
    },
    method: {
        type: String,
        default: 'get',
    },
    small: Boolean,
    outline: Boolean,
    active: Boolean,
    disabled: Boolean,
    roundedFull: Boolean,
});
const computedReferer = computed(()=>{
    return usePage().props.returnUrl ? usePage().props.returnUrl : computedRoute.value;
})
const computedRoute = computed(() => {
    if (props.routeName) {
        if (props.routeParams) {
            return route(props.routeName, props.routeParams);
        }
        return route(props.routeName);
    }

    return props.href;
});

const labelClass = computed(() =>
    props.small && props.icon ? "px-1" : "px-2"
);
const componentClass = computed(() => {
    const base = [
        "inline-flex",
        "cursor-pointer",
        "justify-center",
        "items-center",
        "whitespace-nowrap",
        "focus:outline-none",
        "transition-colors",
        "focus:ring",
        "duration-150",
        "border",
        props.roundedFull ? "rounded-full" : "rounded",
        props.disabled ? " pointer-events-none" : "",
        getButtonColor(props.color, props.outline, !props.disabled, props.active),
    ];
    if (!props.label && props.icon) {
        base.push("p-1");
    } else if (props.small) {
        base.push("text-sm", props.roundedFull ? "px-3 py-1" : "p-1");
    } else {
        base.push("py-2", props.roundedFull ? "px-6" : "px-3");
    }
    if (props.disabled) {
        base.push(
            "cursor-not-allowed",
            props.outline ? "opacity-50" : "opacity-70"
        );
    }
    return base;
});
</script>

<template>
    <template v-if="type === 'back'">
        <Link
            :as="as"
            :class="componentClass"
            :href="computedReferer"
            :type="type"
            :target="target"
            :disabled="disabled"
            tooltip="test"
        >
            <BaseIcon v-if="icon" :path="icon" :size="iconSize"/>
            <span v-if="label" :class="labelClass">{{ label }}</span>
        </Link>
    </template>
    <template v-else-if="type === 'submit' || type === 'button' ">
        <button :type="type"
                :class="componentClass"
                :disabled="disabled">
            <BaseIcon v-if="icon" :path="icon" :size="iconSize"/>
            <span v-if="label" :class="labelClass">{{ label }}</span>
        </button>

    </template>
    <template v-else-if="type === 'href'">
        <a
            target="_blank"
            :href="href"
            :class="componentClass"
            :disabled="disabled">
            <BaseIcon v-if="icon" :path="icon" :size="iconSize"/>
            <span v-if="label" :class="labelClass">{{ label }}</span>
        </a>
    </template>
    <template v-else>
        <Link
            :as="as"
            :class="componentClass"
            :href="computedRoute"
            :type="type"
            :target="target"
            :disabled="disabled"
            :method="method"
            tooltip="test"
        >
            <BaseIcon v-if="icon" :path="icon" :size="iconSize"/>
            <span v-if="label" :class="labelClass">{{ label }}</span>
        </Link>
    </template>

</template>
