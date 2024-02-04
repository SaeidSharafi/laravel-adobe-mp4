<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import BaseButton from '@/Components/Form/BaseButton.vue';
import FormField from '@/Components/Form/FormField.vue';
import FormControl from '@/Components/Form/FormControl.vue';
import ValidationErrors from "@/Components/Form/ValidationErrors.vue";
import {mdiAccountEdit, mdiBackspace} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from 'vue-i18n';

const {t} = useI18n({});

const props = defineProps({
    adobeServerSettings: Object
})

const form = useForm({
    server_address: props.adobeServerSettings.server_address,
    username: props.adobeServerSettings.username,
    password: props.adobeServerSettings.password,
});

const submit = () => {
    form.post(route('settings.adobe-server.store'));
};
</script>



<template>
    <Head :title="t('auth.user.title.edit')"/>

        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiAccountEdit"
                :title="t('auth.user.title.edit')"
                main
            >
                <BaseButton
                    :href="route('profile.view')"
                    :icon="mdiBackspace"
                    :label="t('general.back')"
                    color="info"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>

            <div class="py-12 dark:bg-slate-900 rounded">
                <validation-errors class="mb-4"/>
                <div class="w-1/3 m-auto">
                    <form @submit.prevent="submit">

                        <FormField :label="t('setting.adobe_server.server_address')" help="" label-for="server_address">
                            <FormControl
                                type="text"
                                name="server_address"
                                v-model="form.server_address"
                                placeholder=""
                            />
                        </FormField>
                        <FormField :label="t('setting.adobe_server.username')" help="" label-for="username">
                            <FormControl
                                type="text"
                                name="username"
                                v-model="form.username"
                                placeholder=""
                            />
                        </FormField>
                        <FormField :label="t('setting.adobe_server.password')" help="" label-for="password">
                            <FormControl
                                type="text"
                                name="password"
                                v-model="form.password"
                                placeholder=""
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
