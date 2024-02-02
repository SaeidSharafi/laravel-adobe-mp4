<template>
    <Multiselect
        :searchable="true"
        :options="fetchData"
        :filter-results="false"
        :min-chars="0"
        :resolve-on-load="resolveOnLoad"
        :infinite="true"
        :limit="10"
        :clear-on-search="true"
        :delay="500"
        :object="object"
        v-model="computedValue"
        :rtl="true"
        :can-clear="canClear"
        :can-deselect="canDeselect"
        :required="required"
        @input="updateValue"
        @search-change="search_change"
        @paste="paste"
        @open="open"
        @close="close"
        @select="select"
        @deselect="deselect"
        @search_change="search_change"
        @tag="tag"
        @option="option"
        @change="change"
        @clear="clear"
        @keydown="keydown"
        @keyup="keyup"
        :noOptionsText="t('app.data_table.no_results_found')"
        :noResultsText="t('app.data_table.no_results_found')"
        ref="multiselect"
    >
        <template v-slot:option="{ option }">
            <span :class="option.class">{{ option.label }}</span>
        </template>
    </Multiselect>
</template>

<script setup>
import Multiselect from "@vueform/multiselect"
import {computed, defineComponent, ref, watch} from "vue";
import {useI18n} from "vue-i18n";
import _ from "lodash";

const {t} = useI18n({});
const components = defineComponent({
    Multiselect
});

const emit = defineEmits([
    'paste', 'open', 'close', 'select', 'deselect',
    'input', 'search-change', 'tag', 'option', 'update:modelValue',
    'change', 'clear', 'keydown', 'keyup','fetchedData'
]);

const props = defineProps({
    name: {
        type: String,
        required: false,
        default: null
    },
    routeName: {
        type: String,
        required: true,
        default: ''
    },
    options: {
        type: Object,
        required: false,
        default: null
    },
    filters:{
        type: Object,
        required: false,
        default: null
    },
    columns: {
        type: Array,
        required: false,
        default: []
    },
    strict: {
        type: Boolean,
        required: false,
        default: false,
    },
    object: {
        type: Boolean,
        required: false,
        default: false,
    },
    resolveOnLoad: {
        type: Boolean,
        required: false,
        default: true,
    },
    colorClass: {
        type: String,
        required: false,
        default: '',
    },
    canClear: {
        type: Boolean,
        required: false,
        default: true,
    },
    canDeselect: {
        type: Boolean,
        required: false,
        default: true,
    },
    required: {
        type: Boolean,
        required: false,
        default: false,
    },
    modelValue: {
        required: false,
    },

})

const computedValue = computed({
    get: () => props.modelValue,
    set: (value) => {
        emit("update:modelValue", value);
    },
});
const multiselect = ref(null);
const options = ref(null);
const noSearch = ref(props.strict);

let extra_query = null;


const updateValue = (event) => {
    emit('update:modelValue', event)
}

const addExtraQuery = (query) => {
    extra_query = query;
}
const computedParams = computed(() =>{
    let params =  {}
    if (extra_query && typeof extra_query === 'object') {
        Object.assign(params, extra_query)
    }
    if (props.filters && typeof props.filters === 'object') {
        Object.assign(params, props.filters)
    }

    return params;

})
const fetchData = async (query, select$) => {

    let params = {
        'id': props.object ? props.modelValue?.value : props.modelValue,
        'strict': noSearch.value,
        'columns': props.columns.join(','),
        'query': query,
        ...computedParams.value
    }

    const response = await fetch(route(props.routeName, params));
    let data = await response.json();
    emit("fetchedData", data);
    return data;
}

defineExpose({addExtraQuery})
watch(computedParams,(newValue, oldValue) =>{
    if(! _.isEqual(oldValue,newValue)){
        multiselect.value.clear()
        multiselect.value.refreshOptions()
    }
},{deep: true})

/////////////////////////////
////// Bubbling events //////
/////////////////////////////

const paste = (event, select$) => {
    emit('paste', event, select$)
};
const open = (select$) => {
    emit('open', select$)
};
const close = (select$) => {
    emit('close', select$)
};
const select = (option, select$) => {
    emit('select', option, select$)
};
const deselect = (option, select$) => {
    emit('deselect', option, select$)
};
const input = (value) => {
    emit('input', value)
};
const search_change = (query, select$) => {
    noSearch.value = false;
    emit('search-change', query, select$)
};
const tag = (query, select$) => {
    emit('tag', query, select$)
};
const option = (query, select$) => {
    emit('option', query, select$)
};
const change = (value, select$) => {
    emit('change', value, select$)
};
const clear = (select$) => {
    emit('clear', select$)
};
const keydown = (Event, select$) => {
    emit('keydown', Event, select$)
};
const keyup = (Event, select$) => {
    emit('keydown', Event, select$)
};


</script>

<style scoped>

</style>
