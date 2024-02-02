<script setup>
import BaseButton from '@/Components/Form/BaseButton.vue';
import FormField from '@/Components/Form/FormField.vue';
import FormControl from '@/Components/Form/FormControl.vue';
import BaseValidationErrors from '@/Components/Form/ValidationErrors.vue';
import {Head, useForm} from '@inertiajs/vue3';
import SectionFullScreen from "@/Components/Layout/SectionFullScreen.vue";
import CardBox from "@/Components/Layout/CardBox.vue";
import {useI18n} from "vue-i18n";
import Logo from "@/Components/Logo.vue";

const {t} = useI18n({})
defineProps({
    status: String,
});

const form = useForm({
    username: '',
});

const submit = () => {
    form.post(route('authenticate.username'));
};
</script>

<script>
import GuestLayout from '@/Layouts/Guest.vue';

export default { layout: GuestLayout }
</script>

<template>
        <Head :title="t('auth.log_in')"/>
        <SectionFullScreen v-slot="{ cardClass }">
            <CardBox :class="cardClass" is-form @submit.prevent="submit">
                <div class="w-full p-5 max-w-[20rem] m-auto">
                    <Logo :inline="true"/>
                </div>
                <BaseValidationErrors class="mb-4"/>
                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
                <FormField :label="t('auth.user.email_phone')" help="" label-for="username">
                    <FormControl
                        type="text"
                        name="username"
                        v-model="form.username"
                        placeholder=""
                        required
                        autocomplete="username"
                    />
                </FormField>

                <div class="flex items-center justify-end py-4">
                    <BaseButton type="submit" color="info" :label="t('auth.log_in')" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
                </div>

            </CardBox>
        </SectionFullScreen>

</template>
