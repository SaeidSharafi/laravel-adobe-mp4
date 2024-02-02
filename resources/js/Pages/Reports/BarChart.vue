<script setup>
import {Head} from '@inertiajs/vue3';
import {
  mdiBackspace,
  mdiCalendarPlus,
  mdiClipboardPulse,
  mdiFolderFile,
  mdiHandshake,
} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from "vue-i18n";
import BaseButton from "@/Components/Form/BaseButton.vue";
import Table from "@/Components/DataTable/Table.vue";
import BarStackedChart from "@/Components/Charts/BarStackedChart.vue"

const {t} = useI18n({});

const props = defineProps({
  data: Array,
  title: String,
  icon: String,
  exportRoute: String,
})

const icons = {
  'introductionMethod': mdiHandshake,
  'caseFile': mdiFolderFile,
  'appointment': mdiCalendarPlus,
  'service': mdiClipboardPulse,
}
</script>

<template>
  <Head :title="title"/>

  <SectionMain>
    <SectionTitleLineWithButton
        :icon="icons[icon]"
        :title="title"
        main
    >
      <BaseButton
          :href="route('report.infertility.index')"
          :icon="mdiBackspace"
          :label="t('general.back')"
          color="info"
          rounded-full
          small
      />
    </SectionTitleLineWithButton>


    <div class="py-12 dark:bg-slate-900 rounded px-3">

      <Table :export-route="exportRoute">
      </Table>

      <div class="grid gap-2">
        <div class="col-span-full py-2 border-2 border-gray-300 mt-2 mb-2 rounded-lg">
          <BarStackedChart :data="data" :height="600"></BarStackedChart>

        </div>
      </div>
    </div>
  </SectionMain>

</template>
