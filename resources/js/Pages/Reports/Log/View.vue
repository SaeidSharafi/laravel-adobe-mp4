<script setup>
import {Head} from '@inertiajs/vue3';
import {mdiBackspace, mdiScriptTextOutline} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import {useI18n} from "vue-i18n";
import BaseButton from "@/Components/Form/BaseButton.vue";
import FormField from "@/Components/Form/FormField.vue";

const {t} = useI18n({});

const props = defineProps({
    activity: Object
})

</script>

<template>
    <Head :title="t('reports.log.title.self')"/>

    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiScriptTextOutline"
            :title="t('reports.log.title.self')"
            main
        >
            <BaseButton
                :href="route('reports.activity.index')"
                :icon="mdiBackspace"
                :label="t('general.back')"
                color="info"
                rounded-full
                small
            />
        </SectionTitleLineWithButton>

        <div class="py-12 dark:bg-slate-900 rounded">
            <div v-if="activity.causer">
                <span>{{ t('reports.log.causer') }}</span>
                <div class="grid grid-cols-2 mb-2 border-b-2 border-black py-2">
                    <div class="px-2">
                        <p class="">
                            {{ activity.causer?.first_name }} {{ activity.causer?.last_nam }}
                        </p>
                    </div>
                    <div class="px-2">
                        <p class="">
                            {{ activity.causer?.email }}
                        </p>
                        <p class="">
                            {{ activity.causer?.phone }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2">
                <div class="px-2" v-if="activity.properties.old">
                    <span v-if="activity.properties.attributes">{{ t('reports.log.from') }}</span>
                    <span v-else>{{ t('reports.log.data') }}</span>
                    <div class="p-2 rounded bg-red-300">
                        <template v-for="(attribute,key) in activity.properties.old">
                            <FormField class="grid grid-cols-2" :label="t('validation.attributes.'+ key)" help="" label-for="term">
                                <span>{{ attribute }}</span>
                            </FormField>
                        </template>
                    </div>
                </div>
                <div class="px-2" v-if="activity.properties.attributes">
                    <span v-if="activity.properties.old">{{ t('reports.log.to') }}</span>
                    <span v-else>{{ t('reports.log.data') }}</span>
                    <div class="p-2 rounded bg-green-400">
                        <template v-for="(attribute,key) in activity.properties.attributes">
                            <FormField class="grid grid-cols-2" :label="t('validation.attributes.'+ key)" help="" label-for="term">
                                <span>{{ attribute }}</span>
                            </FormField>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </SectionMain>

</template>
