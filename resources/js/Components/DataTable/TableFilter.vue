<template>
    <div class="py-2 mb-2"
         :class="{'border-b-black border-b' : isShowing}">
        <div class="w-full flex items-baseline">
            <div class="flex pl-2 cursor-pointer" @click="isShowing = !isShowing"
                 :class="{
                'text-gray-400': !hasEnabledFilters,
                [activeClasses.text]: hasEnabledFilters,
                }">
                <BaseIcon :path="computedChevron"></BaseIcon>
                {{ t('general.filters') }}
            </div>
            <div class="border-2 h-0.5 w-full"
                 :class="{
                'border-gray-200': !hasEnabledFilters,
                [activeClasses.border]: hasEnabledFilters,
                }"></div>
        </div>
        <TransitionRoot
            class="overflow-hidden"
            :show="isShowing"
            :appear="isShowing"
            enter="transition-max-height duration-[500ms]"
            enter-from="max-h-0"
            enter-to="max-h-80"
            entered="overflow-visible"
            leave="transition-max-height duration-[500ms] ease-in-out"
            leave-from="max-h-80"
            leave-to="max-h-0"
        >
            <div
                role="menu"
                aria-orientation="vertical"
                aria-labelledby="filter-menu"
                class="min-w-max grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4"
            >


                <div
                    v-for="(filter, key) in filters"
                    :key="key"
                >
                    <h3 class="text-xs uppercase tracking-wide p-3">
                        {{ filter.label }}
                    </h3>
                    <div class="p-2 min-w-[200px]">

                        <Multiselect
                            class="dark:bg-slate-800 dark:text-gray-100 dark:placeholder-gray-200"
                            v-if="filter.type === 'select'"
                            :options="filter.options"
                            @clear="clear(filter)"
                            @change="onFilterChange(filter.key, $event)"
                            :name="filter.key"
                            :placeholder="filter.label"
                            v-model="filter.value"
                            :can-clear="filter.can_clear"
                            :rtl="true"
                        ></Multiselect>
                        <MultiSelectAdvanced
                            v-if="filter.type === 'searchable'"
                            v-model="filter.value"
                            :route-name="filter.route"
                            @change="(value,select) => changeFilter(filter.key,value,select)"
                            @clear="clear(filter)"
                            :filters="filter.depends_on"
                            @fetchedData="onDataFetched(data,onFecthData)"
                        ></MultiSelectAdvanced>
                        <DateTimePicker
                            v-if="filter.type === 'date'"
                            @onDateChange="onFilterChange(filter.key,$event,true)"
                            :name="filter.key"
                            :placeholder="filter.label"
                            v-model="filter.value"
                        ></DateTimePicker>
                    </div>
                </div>
            </div>
        </TransitionRoot>
    </div>
</template>

<script setup>
import Multiselect from "@vueform/multiselect"
import {TransitionRoot} from "@headlessui/vue";
import {computed, inject, ref} from "vue";
import BaseIcon from "@/Components/Layout/BaseIcon.vue";
import {mdiChevronDown, mdiChevronLeft} from "@mdi/js";
import {useI18n} from 'vue-i18n';
import DateTimePicker from "@/Components/Form/DateTimePicker.vue";
import MultiSelectAdvanced from "@/Components/Form/MultiSelectAdvanced.vue";

const {t} = useI18n({});

const props = defineProps({
    hasEnabledFilters: {
        type: Boolean,
        required: true,
    },

    filters: {
        type: Object,
        required: true,
    },
    onDataFetched: {
        type: Function,
        required: true,
    },
    onFilterChange: {
        type: Function,
        required: true,
    },
});

const isShowing = ref(props.hasEnabledFilters)
const computedChevron = computed(() => {
    if (isShowing.value) {
        return mdiChevronDown
    }
    return mdiChevronLeft
})
const clear = (filter) => {
    filter.value = null;
}

const onFecthData = (data, filter) => {
    let selected = data.find((item) => item.value == filter.value);
    if (selected) {
        props.onDataFetched(filter.key, filter.value, selected.label)
    }
}


function changeFilter(key, value, select) {
    props.onFilterChange(key, value, false, select.internalValue?.label)
}

const activeClasses = inject("activeClasses");
</script>

