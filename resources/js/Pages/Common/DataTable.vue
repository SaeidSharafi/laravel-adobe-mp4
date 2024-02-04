<script setup>
import {Head} from '@inertiajs/vue3';
import {
    mdiBackspace, mdiTable,
} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from "vue-i18n";
import BaseButton from "@/Components/Form/BaseButton.vue";
import Table from "@/Components/DataTable/Table.vue";

const {t} = useI18n({});

const props = defineProps({
    resource: Array,
    title: String,
    icon: String,
    exportRoute: String,
})

const icons = {}

function getIcon() {
    if (props.icon) {
        return icons[props.icon]
    }

    return mdiTable
}

</script>

<template>
    <Head :title="title"/>

    <SectionMain>
        <SectionTitleLineWithButton
            :icon="getIcon()"
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

            <Table :export-route="exportRoute" :resource="resource">
            </Table>

        </div>
    </SectionMain>

</template>
