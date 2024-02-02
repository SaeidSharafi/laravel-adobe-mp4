<script setup>
import {useForm} from '@inertiajs/vue3';
import BaseButton from '@/Components/Form/BaseButton.vue';
import FormField from '@/Components/Form/FormField.vue';
import FormControl from '@/Components/Form/FormControl.vue';
import ValidationErrors from "@/Components/Form/ValidationErrors.vue";
import {ref} from "vue";
import ModalLayout from "@/Layouts/ModalLayout.vue";
import Multiselect from "@vueform/multiselect"
import {useI18n} from 'vue-i18n';

const {t} = useI18n({});


const props = defineProps({
    role: Object,
    roles: Object
})
const multiselect = ref(null);

const form = useForm({
    id: props.role.id,
    name: props.role.name,
    label: props.role.label,
    allowoverride: props.role.allowoverride,
});

const submit = () => {
    form.put(route('users.role.update', form.id));
};
</script>


<template>
    <modal-layout>
        <div class="m-auto">
            <validation-errors class="mb-4"/>
            <form @submit.prevent="submit">
                <FormField :label="t('auth.role.name')" help="" label-for="name">
                    <FormControl
                        type="text"
                        name="name"
                        v-model="form.name"
                        placeholder=""
                        :disabled="true"
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
                    <div class="flex w-full">
                        <BaseButton type="button" color="success" :label="t('general.all')" @click="multiselect.selectAll()"/>
                        <Multiselect
                            :options="roles"
                            v-model="form.allowoverride"
                            :close-on-select="false"
                            :rtl="true"
                            mode="tags"
                            name="role"
                            :create-option="false"
                            ref="multiselect"
                        ></Multiselect>
                    </div>
                </FormField>

                <div class="flex items-center justify-end mt-4">
                    <BaseButton type="submit" color="info" :label="t('general.submit')" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
                </div>
            </form>
        </div>
    </modal-layout>
</template>
