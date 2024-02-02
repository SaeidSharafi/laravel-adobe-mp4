<template>
    <transition name="alert">
        <!--Alert Container-->
        <div
            v-if="showAlertBox"
            class="flex flex-shrink-0 items-center justify-start relative w-full flex-grow-0"
            :class="[
                `alert-${temporaryDesign}`,
                !type ? `alert-${temporaryDesign}-${temporaryColor}` :
                    [{ 'alert-success': type === 'success' },
                    { 'alert-warning': type === 'warning' },
                    { 'alert-danger': type === 'error' },
                    { 'alert-info': type === 'info' }],
                `radius-${temporaryRadius}`,
            ]"
        >
            <!--Alert Line-->
            <div v-if="temporaryDesign.includes('line')" class="alert-line" />

            <!--Alert Icon-->
            <div v-if="hasSlot('icon')" class="flex flex-shrink-0 justify-center items-center">
                <slot name="icon"></slot>
            </div>

            <!--Alert Content-->
            <div class="flex flex-col justify-center items-start flex-grow py-2 px-4 whitespace-pre-line">
                <!--Title-->
                <span v-if="title" class="font-bold text-lg" v-text="title"></span>
                <!--Content-->
                <span>
                    <slot></slot>
                </span>
            </div>

            <!--Close Icon-->
          <BaseIcon v-if="temporaryCloseable" @click="close" :path="mdiClose" :size="64" w="w-9" h="w-9"
                    class="hover:text-red-500 cursor-pointer mr-2"></BaseIcon>

            <!--Countdown Line-->
            <div v-if="temporaryTimer" class="absolute justify-center items-center bottom-0 left-0 h-[.3rem] px-2 w-full">
                <div
                    class="h-[.3rem] mx-auto"
                    :class="`radius-${temporaryRadius}`"
                    :style="{ width: countDownCounter + '%' }"
                ></div>
            </div>
        </div>
    </transition>
</template>
<script>
/* Main Functions */
import {defineComponent, onMounted, ref, toRefs} from "vue";

/*Sources*/
import {alertConf} from "@/config.js";

import BaseIcon from "@/Components/Layout/BaseIcon.vue";

export default defineComponent({
    name: "Alert",
  components: {BaseIcon},
    props: {
        id: {
            type: [Number, String, Array, Object, Date, Boolean],
            default: function () {
                return "alert-" + Number(new Date());
            }
        },
        closeable: {
            type: Boolean,
            default: false
        },
        timerStatus: {
            type: Boolean,
            default: false,
        },
        timer: {
            type: Number,
            default: null,
        },
        title: {
            type: String,
            default: null
        },
        type: {
            type: String,
            default: null
        },
        radius: {
            type: Number,
            default: null
        },
        design: {
            type: String,
            default: "filled"
        },
        color: {
            type: String,
            default: "light"
        }
    },


    setup(props, { slots, emit }) {

        /*Definitions*/
        const {design, color, radius, closeable, timerStatus, timer, id } = toRefs(props);
        const showAlertBox =ref(true);

        /*Temporary Definations*/
        const temporaryDesign = ref(design.value ? design.value : alertConf.design )
        const temporaryColor = ref(color.value ? color.value : alertConf.color)
        const temporaryRadius = ref(radius.value ? radius.value : alertConf.radius)
        const temporaryCloseable = ref(closeable.value ? closeable.value : alertConf.closeable)
        const temporaryTimer = ref(timer.value ? timer.value : alertConf.timer)


        /*Close Function*/
        const close = () => {
            showAlertBox.value = false;
            emit("destroy", id.value);
        };

        /*Timer*/
        const timerCounter = ref(0);
        const countDownCounter = ref(0);

        onMounted(() =>{
            setTimeout(() => {
                showAlertBox.value = false;
                emit("destroy", id.value);
            }, temporaryTimer.value);

            /*Count Down Function*/
            let countDownFn = setInterval(() => {
                if (temporaryTimer.value >= timerCounter.value) {
                    countDownCounter.value = 100 - (timerCounter.value / temporaryTimer.value) * 100;
                    timerCounter.value += 4;
                } else {
                    clearInterval(countDownFn);
                }
            }, 1);
        })
        /*Slot Check*/
        const hasSlot = name => !!slots[name];

        return {
            alertConf,
            temporaryDesign,
            temporaryColor,
            temporaryRadius,
            temporaryCloseable,
            temporaryTimer,
            showAlertBox,
            countDownCounter,
            timerCounter,
            hasSlot,
            close
        };
    }
});
</script>

<style scoped>
/* eslint-disable no-alert */
.alert-enter-active,
.alert-leave-active {
    transition: all ease-in-out 1000ms;
}

.alert-enter-from,
.alert-leave-to {
    opacity: 0;
}

.alert-enter-to,
.alert-leave-from {
    opacity: 1;
}

/* eslint-disable no-alert */
</style>
