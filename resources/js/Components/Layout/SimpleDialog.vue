<script setup>
import {useI18n} from "vue-i18n";
import {computed} from "vue";

const {t} = useI18n({})
const props = defineProps({
        header: {
            type: String,
            default: ''
        },
        content: {
            type: String,
            default: ''
        },
        cancelText: {
            type: String,
            default: ''
        },
        confirmText: {
            type: String,
            default: ''
        }
    }
)
const emit = defineEmits(['confirm', 'cancel'])
const getCancelText = computed(() => {
    return props.cancelText ? props.cancelText :t('general.cancel')
})
const getConfirmText = computed(() => {
    return props.confirmText ? props.confirmText :t('general.confirm')
})
</script>

<template>
    <div class="bg-gray-900 bg-opacity-50 fixed inset-0 z-50 overflow-y-auto bg"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0 z-50"
             @click.self="emit('cancel')">
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl rtl:text-right dark:bg-gray-900 sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6">
                <div>

                    <div class="mt-2 text-center">
                        <h3 class="text-lg font-medium leading-6 text-gray-800 capitalize dark:text-white"
                            id="modal-title">{{ header }}</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ content }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 sm:flex sm:items-center justify-center">
                    <div class="flex items-center">
                        <button @click="emit('cancel')"
                                class="w-full px-4 py-2 mt-2 text-sm font-medium tracking-wide text-gray-700 capitalize transition-colors duration-300 transform border border-gray-200 rounded-md sm:mt-0 sm:w-auto sm:mx-2 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800 hover:bg-gray-100 focus:outline-none focus:ring focus:ring-gray-300 focus:ring-opacity-40">
                            {{ getCancelText }}
                        </button>
                        <button @click="emit('confirm')"
                                class="w-full px-4 py-2 mt-2 text-sm font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-md sm:w-auto sm:mt-0 hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-40">
                            {{ getConfirmText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
