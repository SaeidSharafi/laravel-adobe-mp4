<script setup>
import {Head, usePage} from '@inertiajs/vue3';
import Table from '@/Components/DataTable/Table.vue';
import BaseButton from "@/Components/Form/BaseButton.vue";
import {
  mdiAccountMultiple,
  mdiAccountPlus,
  mdiApplicationEditOutline,
  mdiDatabaseImport,
  mdiRestore,
  mdiTrashCan
} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {can, canManageRole} from "@/utils/permission";
import DialogButton from "@/Components/Layout/DialogButton.vue";
import {useI18n} from "vue-i18n";

const {t} = useI18n({});

defineProps({
  users: Object,
  status: String,
})
const checkAdmin = (user) => {
  return !(user.is_admin && usePage().props.auth.user.id !== user.id);

}
const getRoleNames = (roles) => {
  return roles.map(function (role) {
    return role.name;
  })
}
</script>

<template>
  <Head :title="t('auth.user.title.self')"/>

  <SectionMain>
    <SectionTitleLineWithButton
        :icon="mdiAccountMultiple"
        :title="t('auth.user.title.self')"
        main
    >
      <div class="flex gap-2">
        <BaseButton
            :href="route('users.user.create')"
            :icon="mdiAccountPlus"
            :label="t('general.create')"
            color="success"
            rounded-full
            small
        />
        <BaseButton
            v-if="can('users.user.create')"
            :href="route('users.user.import.create')"
            class="mr-2"
            :icon="mdiDatabaseImport"
            :label="t('general.import')"
            color="success"
            rounded-full
        />
      </div>
    </SectionTitleLineWithButton>

    <div class="py-12 dark:bg-slate-900 rounded">
      <Table :resource="users" :export-route="route('users.user.export')">

        <template #cell(actions)="{item: user}">
          <div class="flex">
            <BaseButton
                v-if="canManageRole('users.user.update',getRoleNames(user.roles)) && checkAdmin(user) && !user.deleted_at"
                color="info"
                :icon="mdiApplicationEditOutline"
                small
                :href="route('users.user.edit',user.uuid)"
                class="ml-2"
            />
            <DialogButton
                v-if="canManageRole('users.user.delete',getRoleNames(user.roles)) && checkAdmin(user)"
                color="danger"
                method="delete"
                :icon="mdiTrashCan"
                route-name="users.user.destroy"
                :route-params="user.uuid"
                small
                class="ml-2"
            />
            <DialogButton
                v-if="canManageRole('users.user.delete',getRoleNames(user.roles)) && checkAdmin(user) && user.deleted_at"
                color="success"
                method="put"
                :icon="mdiRestore"
                route-name="users.user.restore"
                :route-params="user.uuid"
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
