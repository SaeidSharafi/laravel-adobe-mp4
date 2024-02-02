<script setup>
import BaseButton from '@/Components/Form/BaseButton.vue'
import {Head} from '@inertiajs/vue3';
import Table from '@/Components/DataTable/Table.vue';
import {mdiApplicationEditOutline, mdiPoliceBadge, mdiShieldPlus, mdiTrashCan} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {canManageRole} from "@/utils/permission";
import DialogButton from "@/Components/Layout/DialogButton.vue";
import {useI18n} from 'vue-i18n';

const {t} = useI18n({});

const props = defineProps({
    roles: Object,
    status: String,
})


</script>


<template>
    <Head :title="t('auth.role.title.index')"/>
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiPoliceBadge"
            :title="t('auth.role.title.index')"
            main
        >
            <BaseButton
                :href="route('users.role.create')"
                :icon="mdiShieldPlus"
                :label="t('general.create')"
                color="success"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>

        <div class="py-12 dark:bg-slate-900 rounded">
            <Table :resource="roles">
                <template #cell(actions)="{item: role}">
                    <div class="flex">
                        <BaseButton
                            v-if="canManageRole('users.role.update',role.name)"
                            color="success"
                            :icon="mdiPoliceBadge"
                            small
                            :href="route('users.permission-role.index',role.id)"
                            class="ml-2"
                        />
                        <BaseButton
                            v-if="canManageRole('users.role.update',role.name)"
                            color="info"
                            :icon="mdiApplicationEditOutline"
                            small
                            :href="route('users.role.edit',role.id)"
                            class="ml-2"
                        />
                        <DialogButton
                            v-if="canManageRole('users.role.delete',role.name)"
                            color="danger"
                            method="delete"
                            :icon="mdiTrashCan"
                            route-name="users.role.destroy"
                            :route-params="role.id"
                            small
                        />
                        <div
                            class="h-8 w-1"
                        ></div>
                    </div>
                </template>
            </Table>

        </div>
    </SectionMain>
</template>
