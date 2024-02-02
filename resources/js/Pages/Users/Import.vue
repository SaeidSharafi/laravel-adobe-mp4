<script setup>
import BaseButton from '@/Components/Form/BaseButton.vue'
import {Head, useForm, usePage} from '@inertiajs/vue3';
import Table from '@/Components/DataTable/Table.vue';
import {mdiAccountMultiple, mdiAccountMultiplePlus, mdiBackspace, mdiHospitalMarker,} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from 'vue-i18n';
import FileUpload from "@/Components/Form/FileUpload.vue";
import {isEmpty} from "lodash-es";
import {computed, ref} from "vue";
import Checkbox from "@/Components/Form/Checkbox.vue";
import FormField from "@/Components/Form/FormField.vue";

const {t} = useI18n({});

const props = defineProps({
  rows: Object,
  headers: Array,
  validated: Boolean,

})
const form = useForm({
  files: [],
  confirmed: false,
});
const showAll = ref(false)

function getValue(key, item) {
  return item.values[key];
}

const submit = () => {
  if (props.rows && props.validated) {
    form.confirmed = true;
  }
  console.log(form.confirmed, props.validated, props.rows)
  form.post(route('users.user.import.store'));
};
const computedSubmitLabel = computed(() => {
  return props.validated ? t('general.import') : t('general.validate')
})
</script>


<template>
  <Head :title="t('auth.user.title.self')"/>
  <SectionMain>
    <SectionTitleLineWithButton
        :icon="mdiAccountMultiplePlus"
        :title="t('auth.user.title.self')"
        main
    >
      <BaseButton

          :href="route('users.user.index',usePage().props.filters?.users)"
          :icon="mdiBackspace"
          :label="t('general.back')"
          color="info"
          rounded-full
          small
      />
    </SectionTitleLineWithButton>

    <div class="py-12 dark:bg-slate-900 rounded">
      <div class="mb-2">
        <form @submit.prevent="submit">
          <FileUpload file-type="csv" v-model="form.files" :multiple="false"></FileUpload>
          <div class="flex items-center justify-between mt-4">
            <FormField :label="t('general.all')"
                       help="" label-for="sowAll">
              <Checkbox
                  type="switch"
                  inputValue="1"
                  name="sowAll"
                  v-model="showAll"
              />
            </FormField>
            <BaseButton type="submit" color="info" :label="computedSubmitLabel" v-if="!isEmpty(form.files)"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing"/>
          </div>
        </form>
      </div>
      <div class="overflow-auto">
        <table class="min-w-full" v-if="rows">
          <thead class="">
          <tr class="!border-b border-gray-300">
            <th>
              <div class="px-1 w-full">
                    <span class="flex flex-row items-center">
                        <span class="uppercase"> ردیف </span>
                    </span>
              </div>
            </th>
            <th v-if="!validated">
              <div class="px-1 w-full">
                    <span class="flex flex-row items-center">
                      خطا
                     </span>
              </div>
            </th>
            <template v-for="(header,key) in headers">
              <th>
                <div class="px-1 w-full">
                    <span class="flex flex-row items-center">
                        <span class="uppercase" :key="key"> {{ header }} </span>
                    </span>
                </div>
              </th>
            </template>
          </tr>

          </thead>
          <tbody>
          <template
              v-for="(item, key) in rows"
              :key="`table--row-${key}`"
          >
            <tr
                v-if="showAll || !isEmpty(item.errors) || validated"
                :key="`table--row-${key}`"
                :class="{
              '!bg-red-400' : !isEmpty(item.errors)
            }"
            >
              <td class="text-sm py-4">
                {{ item.row }}
              </td>
              <td v-if="!validated" class="text-sm py-4">
                <ul class="list-disc whitespace-nowrap mr-4">
                  <template v-for="(error) in item.errors">
                    <li v-for="msg in error">{{ msg }}</li>
                  </template>
                </ul>
              </td>
              <td v-for="(header,key) in headers"
                  :key="`table-row-${key}-column-${header}`"
                  class="text-sm py-4">
              <span class="w-full">
                {{ getValue(key, item) }}
              </span>
              </td>
            </tr>
          </template>
          </tbody>
        </table>
      </div>

    </div>
  </SectionMain>
</template>
