<template>
    <div class="datapicker-wrapper" :class="simple ? 'small' : ''">
        <PersianDatePicker

            :disabled="disabled"
            :format="computedAltFormat"
            compact-time
            :editable="computedEditable"
            :auto-submit="autoSubmit"
            :popover="popover"
            :type="type"
            :clearable="!notClearable"
            class="h-10"
            :placeholder="computedPlaceHolder"
            :locale="computedLocales"
            :alt-name="name"
            :alt-format="computedAltFormat"
            :min="min"
            :max="max"
            :simple="simple"
            :initial-value="initialValue"
            v-model="computedValue"
            :display-format="computedDateFormat"
            :jump-minute="jumpMinute"
            :round-minute="roundMinute"
            :input-attrs="{ class: computedInputClass }"
        ></PersianDatePicker>

    </div>
</template>

<script setup>

import PersianDatePicker from 'vue3-persian-datetime-picker';
import {useI18n} from 'vue-i18n';
import {computed, ref} from "vue";

const emit = defineEmits(["update:modelValue", "onDateChange"]);

const {t, locale} = useI18n({});

const props = defineProps({
    clearable: {
        type: Boolean,
        default: false
    },
    notClearable: {
        type: Boolean,
        default: false
    },
    autoSubmit: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    simple: {
        type: Boolean,
        default: false
    },
    popover: {
        type: [Object, Boolean, String],
        default: false
    },
    type: {
        type: String,
        default: 'date'
    },
    inputFormat: {
        type: String,
        default: 'YYYY-MM-DD'
    },
    inputClass: {
        type: String,
        default: ''
    },
    displayFormat: {
        type: String,
        default: 'short'
    },
    altFormat: {
        type: String,
        default: 'short'
    },
    name: {
        type: String,
        default: 'datepicker'
    },
    id: {
        type: String,
        default: 'datepicker'
    },
    placeholder: {
        type: String,
        default: ''
    },
    format: {
        type: String,
        default: 'YYYY-MM-DD'
    },
    modelValue: {
        type: [Object, Array, String, Number, Boolean],
        default: null,
    },
    initialValue: {
        type: String,
        default: null
    },
    min: {
        type: String,
        default: null
    },
    max: {
        type: String,
        default: null
    },
    jumpMinute: {
        type: Number,
        default: 1
    },
    roundMinute: {
        type: Boolean,
        default: false
    },
})


const date = props.initialValue ? ref(props.initialValue) : ref(null);
const computedInputClass = computed(() =>{
    return 'h-10 ' + props.inputClass;
});
const computedValue = computed({
    get: () => {
        return props.modelValue
    },
    set: (value) => {
        emit("update:modelValue", value)
        emit('onDateChange', value)
    },
});

const computedPlaceHolder = computed(() => {
    if (props.placeholder) {
        return props.placeholder
    }
    switch (props.type) {
        case 'date':
            return '____/__/__'
        case 'date_time':
            return 'YYYY/MM/DD HH:mm'
        case 'time':
            return '__:__'
    }
    return 'YYYY-MM-DD'
});
const computedLocales = computed(() => {
    return locale.value;
})

const computedAltFormat = computed(() => {
    switch (props.type) {
        case 'date':
            return 'YYYY-MM-DD'
        case 'date_time':
            return 'YYYY-MM-DD HH:mm'
        case 'time':
            return 'HH:mm'
    }
    return 'YYYY-MM-DD'
});
const computedDateFormat = computed(() => {

    switch (props.type) {
        case 'date':
            if (props.displayFormat === 'long') {
                return t('app.format.date_long')
            }
            return t('app.format.date_short')
        case 'date_time':
            if (props.displayFormat === 'long') {
                return t('app.format.date_time_long')
            }
            return t('app.format.date_time_short')
        case 'time':
            return t('app.format.time')
    }
    return t('app.format.date_long')

});
const computedEditable = computed(() => {
    return props.type !== 'time';
})

</script>
<style>
.vpd-input-group input {
    direction: ltr;
    text-align: right;
}

.small .vpd-time .vpd-time-h .vpd-counter,
.small .vpd-time .vpd-time-m .vpd-counter {
    font-size: 20px;
    height: 40px;
    line-height: 40px;
}

.small .vpd-time .vpd-time-h:after {
    font-size: 30px;
}

.small [data-type=time] .vpd-time .vpd-time-h, [data-type=time] .vpd-time .vpd-time-m {
    margin-top: 0 !important;
}

.vpd-time .vpd-down-arrow-btn, .vpd-time .vpd-up-arrow-btn {
    display: flex;
    justify-content: center;
}

.small .vpd-simple-content {
    height: 110px;
}

.small .vpd-time .vpd-time-h .vpd-counter-item,
.small .vpd-time .vpd-time-m .vpd-counter-item {
    width: 19px;
}

.small .vpd-content {
    width: 220px;
}

.small .vpd-actions button:nth-child(3) {
    display: none;
}

.small .vpd-input-group input {
    width: 100%;
}
</style>
