<script setup>
import ValidationErrors from '@/Components/Form/ValidationErrors.vue';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import BaseButton from '@/Components/Form/BaseButton.vue';
import FormField from '@/Components/Form/FormField.vue';
import FormControl from '@/Components/Form/FormControl.vue';
import Multiselect from "@vueform/multiselect"
import {ref} from "vue";
import {mdiAccountPlus, mdiBackspace} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from "vue-i18n";
import MultiSelectAdvanced from "@/Components/Form/MultiSelectAdvanced.vue";

const {t} = useI18n({});

defineProps({
    roles: Object
})

const form = useForm({
    first_name: '',
    last_name: '',
    phone: '',
    email: '',
    roles: [],
    password: '',
    password_confirmation: '',
});

const roleModel = ref();

const submit = () => {

    form.post(route('users.user.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>



<template>
    <Head :title="t('auth.user.title.create')"/>

        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiAccountPlus"
                :title="t('auth.user.title.create')"
                main
            >
                <BaseButton

                    :href="route('users.user.index',usePage().props.filters?.user)"
                    :icon="mdiBackspace"
                    :label="t('general.back')"
                    color="info"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>

            <div class="py-12 dark:bg-slate-900 rounded">
                <div class="w-1/3 m-auto">

                    <ValidationErrors class="mb-4"/>

                    <form @submit.prevent="submit">
                        <FormField :label="t('auth.user.first_name')" help="" label-for="first_name">
                            <FormControl
                                type="text"
                                name="first_name"
                                v-model="form.first_name"
                                placeholder=""
                            />
                        </FormField>
                        <FormField :label="t('auth.user.last_name')" help="" label-for="last_name">
                            <FormControl
                                type="text"
                                name="last_name"
                                v-model="form.last_name"
                                placeholder=""
                            />
                        </FormField>
                        <FormField :label="t('auth.user.phone')" help="" label-for="phone">
                            <FormControl
                                type="tel"
                                name="tel"
                                v-model="form.phone"
                                placeholder=""
                            />
                        </FormField>

                        <FormField :label="t('auth.user.email')" help="" label-for="email">
                            <FormControl
                                type="email"
                                name="email"
                                v-model="form.email"
                                placeholder=""
                            />
                        </FormField>

                        <FormField :label="t('auth.role.title.self')" help="" label-for="roles">
                            <Multiselect
                                :options="roles"
                                v-model="form.roles"
                                :close-on-select="false"
                                :rtl="true"
                                mode="tags"
                                :create-option="false"
                            ></Multiselect>

                        </FormField>
                        <FormField :label="t('auth.user.password')" help="" label-for="password">
                            <FormControl
                                type="password"
                                name="password"
                                v-model="form.password"
                                placeholder=""
                                autocomplete="new-password"
                            />
                        </FormField>

                        <FormField :label="t('auth.user.password_confirmation')" help="" label-for="password_confirmation">
                            <FormControl
                                type="password"
                                name="password_confirmation"
                                v-model="form.password_confirmation"
                                placeholder=""
                                autocomplete="new-password"
                            />
                        </FormField>

                        <div class="flex items-center justify-end mt-4">
                            <BaseButton type="submit" color="info" :label="t('general.submit')" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
                        </div>

                    </form>
                </div>

            </div>
        </SectionMain>
</template>
