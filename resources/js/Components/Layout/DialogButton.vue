<template>
    <BaseButton
        @click="reveal()"
        :title="title"
        type="button"
    />
</template>

<script setup>
import SimpleDialog from "@/Components/Layout/SimpleDialog.vue"
import {createConfirmDialog} from 'vuejs-confirm-dialog'
import BaseButton from "@/Components/Form/BaseButton.vue";
import {router} from '@inertiajs/vue3'
import {useI18n} from "vue-i18n";

const {t} = useI18n({})

const props = defineProps({
    routeName: {
        type: String,
        default: null,
    },
    routeParams: {
        type: [Number, String, Array, Object],
        default: null,
    },
    cancelRouteName: {
        type: String,
        default: null,
    },
    cancelRouteParams: {
        type: [Number, String, Array, Object],
        default: null,
    },
    href: {
        type: String,
        default: null,
    },
    method: {
        type: String,
        default: 'get',
    },
    title: {
        type: String,
        default: null,
    },
    content: {
        type: String,
        default: null,
    },
    cancelText: {
        type: String,
        default: '',
    },
    confirmText: {
        type: String,
        default: '',
    }

})

const reveal = function () {
    const dialog = createConfirmDialog(SimpleDialog,
        {
            content: getMsg(), cancelText: props.cancelText, confirmText: props.confirmText
        })

    dialog.onConfirm(() => {
        switch (props.method) {
            case 'get':
                router.visit(route(props.routeName, props.routeParams));
                break;
            case 'post':
                router.post(route(props.routeName, props.routeParams));
                break;
            case 'put':
                router.put(route(props.routeName, props.routeParams));
                break;
            case 'patch':
                router.patch(route(props.routeName, props.routeParams));
                break;
            case 'delete':
                router.delete(route(props.routeName, props.routeParams));
                break;
            case 'href':
                window.open(route(props.routeName, props.routeParams), '_blank');
                break;
        }
    });
    if (props.cancelRouteName) {
        dialog.onCancel(() => {
            window.open(route(props.cancelRouteName, props.cancelRouteParams));
        });
    }


    dialog.reveal()

}
const getMsg = () => {
    if (props.content) {
        return props.content
    }
    switch (props.method) {
        case 'delete':
            return t('general.dialog.delete')
        default:
            return t('general.dialog.default')
    }
}
</script>
