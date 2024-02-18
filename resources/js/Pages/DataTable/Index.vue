<script setup>
import BaseButton from '@/Components/Form/BaseButton.vue'
import {Head} from '@inertiajs/vue3';
import Table from '@/Components/DataTable/Table.vue';
import {
    mdiBackspace, mdiTable, mdiVideoBox,
} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from 'vue-i18n';
import {can} from '@/utils/permission';

const {t} = useI18n({});

const props = defineProps({
    resources: Object,
    title: String,
    icon: String,
    exportRoute: String,
    backRoute: String,
})
const icons = {
    'recording': mdiVideoBox,
}
function getIcon(){
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
                v-if="backRoute"
                :href="backRoute"
                :icon="mdiBackspace"
                :label="t('general.back')"
                color="info"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>

        <div class="py-12 dark:bg-slate-900 rounded">
            <Table :resource="resources"
            :export-route="exportRoute">
            </Table>

        </div>
    </SectionMain>
</template>
