<script setup>
import AsideMenuItem from "@/Components/Nav/AsideMenuItem.vue";
import {can} from "@/utils/permission";

defineProps({
    isDropdownList: Boolean,
    menu: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(["menu-click"]);

const menuClick = (event, item) => {
    emit("menu-click", event, item);
};
</script>

<template>
    <ul>
        <template v-for="(item, index) in menu">
            <AsideMenuItem
                v-if="can(item.permissions)"
                :key="index"
                :item="item"
                :is-dropdown-list="isDropdownList"
                @menu-click="menuClick"
            />
        </template>

    </ul>
</template>
