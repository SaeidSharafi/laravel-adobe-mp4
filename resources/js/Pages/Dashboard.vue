<script setup>
import {Head} from '@inertiajs/vue3';
import {useI18n} from 'vue-i18n';
import {mdiMonitor} from "@mdi/js"
import SectionTitleLineWithButton from "@/Components/Layout/SectionTitleLineWithButton.vue";
import SectionMain from "@/Components/Layout/SectionMain.vue";
import Logo from "@/Components/Logo.vue";

const {t} = useI18n({});
const props = defineProps({

})

</script>


<template>
    <Head :title="t('app.nav.side.dashboard')"/>
    <SectionMain>
        <SectionTitleLineWithButton
            :icon="mdiMonitor"
            :title="t('app.nav.side.dashboard')"
            main
        >
        </SectionTitleLineWithButton>
        <div class="py-12 dark:bg-slate-900 rounded px-3">
            <div class="w-full p-5 max-w-2xl m-auto">
                <Logo/>
            </div>
        </div>
    </SectionMain>
</template>
