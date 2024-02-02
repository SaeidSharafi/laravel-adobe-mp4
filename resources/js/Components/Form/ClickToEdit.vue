<template>
    <input
        v-if="edit"
        type="text"
        ref="input"
        autofocus
        inputmode="numeric"
        @blur="cancel()"
        @focus="$event.target.select()"
        @keydown.enter.prevent="updateValue($event)"
        v-model="localValue"
        class="text-blue-600 max-w-[50px] px-2 text-center h-8"/>
    <span class="text-center cursor-pointer" v-else @click="startEdit()">{{ localValue }}</span>
</template>

<script setup>
import {onMounted, ref, watch, watchEffect} from "vue";

const emit = defineEmits(["update:modelValue", "onUpdateValue"]);
const edit = ref(false);
const localValue = ref(null)
const input = ref(null);

const props = defineProps({
    modelValue: {
        type: [Array, String, Number, Boolean],
        default: null,
        required: true,
    },
})
onMounted(() => {
    localValue.value = props.modelValue;
})

watch(() => props.modelValue,()=>{
    localValue.value = props.modelValue;
})
watchEffect(() => {
    if (input.value) {
        input.value.focus()
    }
})

function startEdit() {
    edit.value = true;
}

function updateValue(event) {
    console.log(localValue.value)
    emit('update:modelValue', localValue.value)
    emit('onUpdateValue', localValue.value)
    edit.value = false;
}

function cancel() {
    edit.value = false;
    localValue.value = props.modelValue;
}


</script>
