<template>
    <TransitionRoot appear as="template" :show="show">
        <Dialog as="div" class="relative z-40" @close="emit('close')" :class="{ dark: styleStore.darkMode }">
            <TransitionChild
                as="template"
                @afterEnter="emit('afterEnter')"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/75 transition-opacity"/>
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-lg transform rounded-2xl bg-white dark:bg-slate-900 p-6 align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 pb-2">
                                <div class="flex justify-between dark:text-gray-200">
                                    <span>
                                        <slot name="title"></slot>
                                    </span>
                                    <BaseButton
                                        color="danger"
                                        @click="emit('close')"
                                        type="button"
                                        :icon="mdiClose"
                                        small/>
                                </div>
                            </DialogTitle>
                            <div>
                                <slot name="body"></slot>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from "@headlessui/vue"
import {mdiClose} from "@mdi/js"
import BaseButton from '@/Components/Form/BaseButton.vue';
import {useStyleStore} from "@/admin/style";

const styleStore = useStyleStore();
const emit = defineEmits(["afterEnter", "close"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
})
</script>

<style scoped>

</style>
