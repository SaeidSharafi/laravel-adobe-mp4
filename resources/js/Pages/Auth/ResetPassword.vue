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
    username: String,
    token: String,
});

const form = useForm({
    token: props.token,
    username: props.username,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<script>
import GuestLayout from '@/Layouts/Guest.vue';

export default {layout: GuestLayout}
</script>

<template>
    <Head :title="t('auth.reset_password')"/>
    <SectionFullScreen v-slot="{ cardClass }">
        <CardBox :class="cardClass" is-form @submit.prevent="submit">
            <div class="w-full p-5 max-w-[20rem] m-auto">
                <Logo :inline="true"/>
            </div>

            <BaseValidationErrors class="mb-4"/>

            <FormField :label="t('auth.user.phone')" help="" label-for="username">
                <FormControl
                    type="text"
                    name="username"
                    v-model="form.username"
                    placeholder=""
                    required
                    autocomplete="username"
                />
            </FormField>

            <FormField :label="t('auth.user.password')" help="" label-for="password">
                <FormControl
                    type="password"
                    name="password"
                    v-model="form.password"
                    placeholder=""
                    required
                    autocomplete="new-password"
                />
            </FormField>
            <FormField :label="t('auth.user.password_confirmation')" help="" label-for="password_confirmation">
                <FormControl
                    type="password"
                    name="password_confirmation"
                    v-model="form.password_confirmation"
                    placeholder=""
                    required
                    autocomplete="new-password"
                />
            </FormField>


            <div class="flex items-center justify-end mt-4">
                <BaseButton type="submit" color="info" :label="t('auth.reset_password')"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
            </div>
        </CardBox>
    </SectionFullScreen>
</template>
