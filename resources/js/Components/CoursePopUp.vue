<template>
    <span @click="open" class="cursor-pointer inline-block m-1 px-2 rounded-2xl bg-blue-600 text-white truncate max-w-[80px]">...</span>
    <BaseModal
        :show="show"
        @close="close">
        <template #body>
            <template v-for="(course,key) in items">
                <span class="inline-block m-1 px-2 rounded-2xl bg-green-600 text-white">{{course}}</span>
                <div class="w-full" v-if="(key+1) % 2 === 0"></div>
            </template>
        </template>
    </BaseModal>
</template>

<script setup>
import {ref} from "vue";
import BaseModal from "@/Components/Layout/BaseModal.vue";

const show = ref(false);
const close = () => {
    show.value = false;
}
const open = () => {
    show.value = true;
}
const props = defineProps({
    items:null,
})

</script>

<style scoped>

</style>
