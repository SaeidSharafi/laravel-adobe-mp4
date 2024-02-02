<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import {useI18n} from "vue-i18n";
import BaseButton from '@/Components/Form/BaseButton.vue';
import Checkbox from "@/Components/Form/Checkbox.vue";
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {mdiBackspace, mdiPoliceBadge} from "@mdi/js";

const {t} = useI18n({})

const props = defineProps({
    active_permission: Object,
    permissions: Object,
    role: Object
})

const form = useForm({
    id: props.role.id,
    permissions: props.active_permission,
});
const update_checked = (value, e) => {

    form.permissions[e] = value;
    console.log(e, value, form.permissions);
}
const submit = () => {
    console.table(form.permissions);
    form.post(route('users.permission-role.update', form.id));
};

</script>



<template>
    <Head :title="t('auth.permission.title.index')"/>
     <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiPoliceBadge"
                :title="t('auth.permission.title.index')"
                main
            >
                <BaseButton
                    :href="route('users.role.index')"
                    :icon="mdiBackspace"
                    :label="t('general.back')"
                    color="info"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>

            <div class="py-12 w-3/5 m-auto">
                <form @submit.prevent="submit">
                    <div v-for="(permission) in permissions" class="border-2 border-amber-200 p-3 mb-2">

                        <h4 class="text-start font-bold">{{ t(permission.title) }}</h4>
                        <div class="flex justify-between">
                            <template v-for="(sub_permission,id) in permission.sub_permissions">
                                <Checkbox input-value="1" name="permissions[]" :is-disabled="sub_permission.disabled"
                                          :value="id" v-model="sub_permission.checked" @update:checked="update_checked($event,id)"
                                          :label="t('auth.permission.'+sub_permission.title)"/>
                            </template>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <BaseButton class="ml-4" type="submit" color="info" :label="t('general.submit')" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
                    </div>
                </form>
            </div>
        </SectionMain>
</template>
