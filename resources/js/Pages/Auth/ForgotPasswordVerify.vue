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
const props = defineProps({
    status: String,
    email: String,
    phone: String,
});

const form = useForm({
    phone: props.phone,
    email: props.email,
    otp: '',
});

const submit = () => {
    form.post(route('password.verify'));
};
</script>

<script>
import GuestLayout from '@/Layouts/Guest.vue';

export default {layout: GuestLayout}
</script>

<template>
    <Head :title="t('auth.forgot_password')"/>
    <SectionFullScreen v-slot="{ cardClass }">
        <CardBox :class="cardClass" is-form @submit.prevent="submit">
            <div class="w-full p-5 max-w-[20rem] m-auto">
                <Logo :inline="true"/>
            </div>
            <div class="mb-4 text-sm text-gray-600">
                {{ t('auth.forgot_password_otp_content') }}
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <BaseValidationErrors class="mb-4"/>


            <FormField :label="t('auth.user.otp')" help="" label-for="otp">
                <FormControl
                    type="text"
                    name="otp"
                    v-model="form.otp"
                    placeholder=""
                    required
                    autocomplete="new-password"
                />
            </FormField>

            <div class="flex items-center justify-end mt-4">
                <BaseButton type="submit" color="info" :label="t('general.submit')"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>

            </div>
        </CardBox>
    </SectionFullScreen>
</template>
