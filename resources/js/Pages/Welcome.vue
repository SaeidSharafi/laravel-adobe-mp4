<script setup>
import {Head} from '@inertiajs/vue3';
import {useI18n} from 'vue-i18n';
import BaseButton from "@/Components/Form/BaseButton.vue";
import Logo from "@/Components/Logo.vue";
import CardBox from "@/Components/Layout/CardBox.vue";

const {t} = useI18n({});
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})
</script>

<script>
import GuestLayout from '@/Layouts/Guest.vue';

export default {layout: GuestLayout}
</script>

<template>
    <Head title="Laravel Easy Dash"/>

    <div
        class="bg-login relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <CardBox class="w-1/5 h-50 py-6 h-25 my-auto mx-auto sm:px-6 lg:px-8">
            <div class="w-full p-5 max-w-[15rem] m-auto">
                <Logo/>
            </div>
            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <div v-if="canLogin" class="flex justify-center mx-auto">
                    <BaseButton
                        color="success"
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        :label="t('app.nav.side.dashboard')">

                    </BaseButton>

                    <template v-else>
                        <BaseButton
                            color="info"
                            :href="route('login')"
                            :label="t('auth.log_in') ">
                        </BaseButton>

                    </template>
                </div>
            </div>

            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">

                </div>
            </div>

            <div class="flex justify-center mt-4 sm:items-center">
                <div class="text-center text-sm text-gray-500 sm:text-left">

                </div>

                <div class="ml-4 text-center text-sm text-gray-700 sm:text-right sm:ml-0">
                    {{ t('app.version', {'version': $page.props.version}) }}
                </div>
            </div>
        </CardBox>
    </div>
</template>

<style scoped>

</style>
