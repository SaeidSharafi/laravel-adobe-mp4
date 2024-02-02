<script setup>
import {computed} from 'vue'
import {Head} from '@inertiajs/vue3';
import BaseButton from "@/Components/Form/BaseButton.vue";
import {mdiBackspace} from "@mdi/js";
import {useI18n} from "vue-i18n";

const {t} = useI18n({});

const props = defineProps({
    status: Number,
})

const title = computed(() => {
    return {
        503: 'سرویس در دسترس نمیباشد.',
        500: 'خطای سرور',
        404: 'صفحه پیدا نشد',
        403: 'عدم دسترسی',
    }[props.status]
})
function back(){
    window.history.back();
}
const description = computed(() => {
    return {
        503: 'در حال حاضر سروسی مورد نظر در دسترس نمیباشد.',
        500: 'یک خطا رخ داده است، لطفا با پشتیبان سرور تماس بگیرید',
        404: 'متاسفانه صفحه مورد نظر یافت نشد.',
        403: 'متاسفانه شما امکان دسترسی به این صفحه را ندارید.',
    }[props.status]
})
</script>

<template>
    <Head title="Error"/>

    <div class="flex flex-col absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 items-center justify-center">
        <H1>{{ title }}</H1>
        <div>{{ description }}</div>
        <BaseButton
            type="button"
            @click="back"
            :icon="mdiBackspace"
            :label="t('general.back')"
            color="info"
            rounded-full
            small
        />
    </div>
</template>
