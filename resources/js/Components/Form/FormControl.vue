<script setup>
import {computed, onBeforeUnmount, onMounted, reactive, ref} from "vue";
import {useMainStore} from "@/admin/main";
import FormControlIcon from "@/Components/Form/FormControlIcon.vue";
// noinspection ES6UnusedImports
import {vMaska} from "maska";

const maskedValue = reactive({});
const props = defineProps({
    name: {
        type: String,
        default: null,
    },
    id: {
        type: String,
        default: null,
    },
    autocomplete: {
        type: String,
        default: null,
    },
    placeholder: {
        type: String,
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    inputmode: {
        type: String,
        default: null,
    },
    icon: {
        type: String,
        default: null,
    },
    options: {
        type: Array,
        default: null,
    },
    type: {
        type: String,
        default: "text",
    },
    mask: {
        type: [String, Array],
        default: null
    },
    maskToken: {
        type: [String, Array],
        default: null
    },
    maskReversed: {
        type: Boolean,
        default: false
    },
    masked: {
        type: Boolean,
        default: false
    },
    inputClass: {
        type: String,
        default: ""
    },
    modelValue: {
        type: [String, Number, Boolean, Array, Object],
        default: "",
    },
    hasErrors:{
        type: Boolean,
        default: false
    },
    required: Boolean,
    borderless: Boolean,
    transparent: Boolean,
    ctrlKFocus: Boolean,
});

const emit = defineEmits(["update:modelValue", "setRef"]);

const computedValue = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit("update:modelValue", value);
    },
});

const onMaska = (event) => {

    emit("update:modelValue",
        props.masked ? event.detail.masked : event.detail.unmasked);
}
const inputElClass = computed(() => {
    const base = [
        "px-3 py-2 max-w-full focus:outline-none rounded w-full",
        "dark:placeholder-gray-400 dark:text-gray-200",
        computedType.value === "textarea" ? "h-24" : "h-10",
        props.borderless ? "border-0" : "border",
        props.transparent ? "bg-transparent" : "bg-white dark:bg-slate-800",
        props.hasErrors ? "border-red-600 text-red-600" : "border-gray-300 dark:border-slate-700",
        props.inputClass
    ];

    if (props.icon) {
        base.push("pl-10");
    }

    return base;
});

const computedType = computed(() => (props.options ? "select" : props.type));

const controlIconH = computed(() =>
    props.type === "textarea" ? "h-full" : "h-12"
);

const mainStore = useMainStore();

const selectEl = ref(null);

const textareaEl = ref(null);

const inputEl = ref(null);

onMounted(() => {
    if (selectEl.value) {
        emit("setRef", selectEl.value);
    } else if (textareaEl.value) {
        emit("setRef", textareaEl.value);
    } else {
        emit("setRef", inputEl.value);
    }
});

if (props.ctrlKFocus) {
    const fieldFocusHook = (e) => {
        if (e.ctrlKey && e.key === "k") {
            e.preventDefault();
            inputEl.value.focus();
        } else if (e.key === "Escape") {
            inputEl.value.blur();
        }
    };

    onMounted(() => {
        if (!mainStore.isFieldFocusRegistered) {
            window.addEventListener("keydown", fieldFocusHook);
            mainStore.isFieldFocusRegistered = true;
        } else {
            // console.error('Duplicate field focus event')
        }
    });

    onBeforeUnmount(() => {
        window.removeEventListener("keydown", fieldFocusHook);
        mainStore.isFieldFocusRegistered = false;
    });
}
</script>

<template>
    <div class="relative">
        <select
            v-if="computedType === 'select'"
            :id="id"
            v-model="computedValue"
            :name="name"
            :class="inputElClass"
            :disabled="disabled"
        >
            <option
                v-for="option in options"
                :key="option.id ?? option"
                :value="option"
            >
                {{ option.label ?? option }}
            </option>
        </select>
        <textarea
            v-else-if="computedType === 'textarea'"
            :id="id"
            v-model="computedValue"
            :class="inputElClass"
            :name="name"
            :placeholder="placeholder"
            :required="required"
            :disabled="disabled"
        />
        <input
            v-else-if="computedType === 'number_format'"
            :id="id"
            ref="inputEl"
            v-maska="maskedValue"
            data-maska="9,99#,###"
            data-maska-tokens="9:[0-9]:repeated"
            data-maska-reversed
            :name="name"
            :value="modelValue"
            :inputmode="inputmode"
            @maska="onMaska"
            :autocomplete="autocomplete"
            :required="required"
            :placeholder="placeholder"
            :type="computedType"
            :class="inputElClass"
            :disabled="disabled"
        />
        <input
            v-else-if="computedType === 'text' && mask"
            :id="id"
            ref="inputEl"
            v-maska="maskedValue"
            :data-maska="mask"
            :data-maska-tokens="maskToken"
            :data-maska-reversed="maskReversed"
            :name="name"
            :value="modelValue"
            :inputmode="inputmode"
            @maska="onMaska"
            :autocomplete="autocomplete"
            :required="required"
            :placeholder="placeholder"
            :type="computedType"
            :class="inputElClass"
            :disabled="disabled"
        />
        <input
            v-else
            :id="id"
            ref="inputEl"
            v-model="computedValue"
            :name="name"
            :inputmode="inputmode"
            :autocomplete="autocomplete"
            :required="required"
            :placeholder="placeholder"
            :type="computedType"
            :class="inputElClass"
            :disabled="disabled"
        />
        <FormControlIcon v-if="icon" :icon="icon" :h="controlIconH"/>
    </div>
</template>
