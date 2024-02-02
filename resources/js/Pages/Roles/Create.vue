<script setup>
import ValidationErrors from '@/Components/Form/ValidationErrors.vue';
import {useForm} from '@inertiajs/vue3';
import BaseButton from '@/Components/Form/BaseButton.vue';
import FormField from '@/Components/Form/FormField.vue';
import FormControl from '@/Components/Form/FormControl.vue';
import Multiselect from "@vueform/multiselect"
import ModalLayout from "@/Layouts/ModalLayout.vue";
import {useI18n} from 'vue-i18n';

const {t} = useI18n({});

defineProps({
    roles: Object
})

const form = useForm({
    name: '',
    label: '',
    allowoverride: [],
});

const submit = () => {
    form.post(route('users.role.store'));
};
</script>


<template>
    <modal-layout>
        <div class="m-auto">
            <ValidationErrors class="mb-4"/>
            <form @submit.prevent="submit">
                <FormField :label="t('auth.role.name')" help="" label-for="name">
                    <FormControl
                        type="text"
                        name="name"
                        v-model="form.name"
                        placeholder=""
                    />
                </FormField>
                <FormField :label="t('auth.role.label')" help="" label-for="label">
                    <FormControl
                        type="text"
                        name="label"
                        v-model="form.label"
                        placeholder=""
                    />
                </FormField>

                <FormField :label="t('auth.role.can_anage')" help="" label-for="role">
                    <Multiselect
                        :options="roles"
                        v-model="form.allowoverride"
                        :close-on-select="false"
                        :rtl="true"
                        mode="tags"
                        name="role"
                        :create-option="false"
                    ></Multiselect>
                </FormField>


                <div class="flex items-center justify-end mt-4">
                    <BaseButton type="submit" color="info" :label="t('general.submit')" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
                </div>
            </form>
        </div>
    </modal-layout>
</template>
