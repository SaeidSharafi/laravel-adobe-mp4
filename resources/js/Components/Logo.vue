<template>
    <div :class="computedClass">
    <div class="w-1/2 block">
    <LogoSymbol></LogoSymbol>
    </div>
      <h1>Adobe MP4</h1>
    </div>
</template>

<script setup>
import LogoSymbol from "@/Components/Logo-symbol.vue";
import {computed} from "vue";

const props = defineProps({
    inline: {
        type: Boolean,
        default: false
    },
})

const computedClass = computed(() =>{
    let style = 'flex flex-row-reverse items-center justify-center m-auto w-full gap-4';
    if (!props.inline){
        style += ' flex-col gap-0';
    }
    return style
})
</script>
