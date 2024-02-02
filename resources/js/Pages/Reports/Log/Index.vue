<script setup>
import {Head} from '@inertiajs/vue3';
import Table from '@/Components/DataTable/Table.vue';
import {mdiEyeOutline, mdiScriptTextOutline} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from "vue-i18n";
import BaseButton from "@/Components/Form/BaseButton.vue";

const {t} = useI18n({});

const props = defineProps({
    logs: Object
})

</script>

<template>
    <Head :title="t('reports.log.title.index')"/>

    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiScriptTextOutline"
            :title="t('reports.log.title.index')"
            main
        >
        </SectionTitleLineWithButton>


        <div class="py-12 dark:bg-slate-900 rounded">
            <Table :resource="logs">
                <template #cell(description)="{item: log}">
                    {{t('reports.log.action.'+log.description)}}
                </template>
                <template #cell(actions)="{item: log}">
                    <BaseButton
                        color="info"
                        method="get"
                        route-name="reports.activity.view"
                        :route-params="log.id"
                        :icon="mdiEyeOutline"
                        small/>
                </template>
                <template #cell(causer)="{item: log}">
                    {{ log.causer?.first_name }} {{ log.causer?.last_name }}
                </template>
            </Table>

        </div>
    </SectionMain>

</template>
