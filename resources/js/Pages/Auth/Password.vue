<script setup>
import BaseButton from '@/Components/Form/BaseButton.vue';
import BaseCheckbox from '@/Components/Form/Checkbox.vue';
import FormField from '@/Components/Form/FormField.vue';
import FormControl from '@/Components/Form/FormControl.vue';
import BaseValidationErrors from '@/Components/Form/ValidationErrors.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import CardBox from "@/Components/Layout/CardBox.vue";
import SectionFullScreen from "@/Components/Layout/SectionFullScreen.vue";
import {useI18n} from "vue-i18n";
import Logo from "@/Components/Logo.vue";

const {t} = useI18n({})
const props = defineProps({
    otp: String,
    username: String,
    useOTP: Boolean,
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    username: props.username,
    otp: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('authenticate.password'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<script>
import GuestLayout from '@/Layouts/Guest.vue';

export default {layout: GuestLayout}
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
            <div v-if="otp">
                token: {{ otp }}
            </div>
            <input hidden type="text" autocomplete="username" name="username" id="username" v-model="form.username"/>
            <FormField :label="t('auth.user.otp')" help="" label-for="otp" v-if="useOTP">
                <FormControl
                    type="text"
                    name="otp"
                    v-model="form.otp"
                    placeholder=""
                    required
                    autocomplete="new-password"
                />
            </FormField>
            <FormField :label="t('auth.password')" help="" label-for="password" v-else>
                <FormControl
                    type="password"
                    name="password"
                    v-model="form.password"
                    placeholder=""
                    required
                    autocomplete="current-password"
                />
            </FormField>
            <div class="block mt-4">
                <label class="flex items-center">
                    <BaseCheckbox input-value="1" name="remember" v-model="form.remember"/>
                    <span class="mr-2 text-sm text-gray-600">{{ t('auth.remember_me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ t('auth.forgot_password') }}
                </Link>

                <BaseButton type="submit" class="mr-4" color="info" :label="t('auth.log_in')"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>

            </div>

        </CardBox>
    </SectionFullScreen>

</template>
