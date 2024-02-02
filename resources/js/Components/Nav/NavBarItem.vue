<script setup>
import {mdiBell, mdiBellAlert, mdiChevronDown, mdiChevronUp} from "@mdi/js";
import {Link, usePage} from '@inertiajs/vue3';
import {computed, onBeforeUnmount, onMounted, ref} from "vue";
import {useStyleStore} from "@/admin/style.js";
import BaseIcon from "@/Components/Layout/BaseIcon.vue";
import UserAvatarCurrentUser from "@/Components/Layout/UserAvatarCurrentUser.vue";
import NavBarMenuList from "@/Components/Nav/NavBarMenuList.vue";
import BaseDivider from "@/Components/Layout/BaseDivider.vue";
import {useI18n} from 'vue-i18n';
import {can} from "@/utils/permission";
import NotificationCard from "@/Components/ui/NotificationCard.vue";

const {t} = useI18n({});
const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(["menu-click"]);

const is = computed(() => {
  if (props.item.href) {
    return 'a'
  }

  if (props.item.route) {
    return Link
  }

  return 'div'
});

const styleStore = useStyleStore();

const itemHref = computed(() => props.item.route
    ? props.item.parameter ? route(props.item.route, props.item.parameter) : route(props.item.route)
    : props.item.href)

const componentClass = computed(() => {
  const base = [
    isDropdownActive.value
        ? `${styleStore.navBarItemLabelActiveColorStyle} dark:text-slate-400`
        : `${styleStore.navBarItemLabelStyle} dark:text-white dark:hover:text-slate-400 ${styleStore.navBarItemLabelHoverStyle}`,
    props.item.menu ? "lg:py-2 lg:px-3" : "py-2 px-3",
  ];

  if (props.item.isDesktopNoLabel) {
    base.push("lg:w-16", "lg:justify-center");
  }

  return base;
});

const itemLabel = computed(() => {
  if (props.item.isCurrentUser) {
    return (usePage().props.auth.user.first_name ? usePage().props.auth.user.first_name : usePage().props.auth.user.phone);
  }
  if (props.item.label) {
    return t(props.item.label)
  }

  return null;

})

const isDropdownActive = ref(false);

const menuClick = (event) => {
  emit("menu-click", event, props.item);

  if (props.item.menu || props.item.isNotification) {
    isDropdownActive.value = !isDropdownActive.value;
  }
};

const menuClickDropdown = (event, item) => {
  emit("menu-click", event, item);
};

const root = ref(null);
const notification = ref(null);

const forceClose = (event) => {
  if (root.value && !root.value.contains(event.target)) {
    isDropdownActive.value = false;
  }
};

onMounted(() => {
  if (props.item.menu || props.item.isNotification) {
    window.addEventListener("click", forceClose);
  }
});

onBeforeUnmount(() => {
  if (props.item.menu || props.item.isNotification) {
    window.removeEventListener("click", forceClose);
  }
});
</script>

<template>
  <BaseDivider v-if="item.isDivider" nav-bar/>
  <component
      :is="is"
      v-else-if="item.permission ? can(item.permission) : true"
      ref="root"
      class="block lg:flex items-center relative cursor-pointer"
      :class="componentClass"
      :href="itemHref"
      @click="menuClick"
  >
    <div
        class="flex items-center"
        :class="{
        'bg-gray-100 dark:bg-slate-800 lg:bg-transparent lg:dark:bg-transparent p-3 lg:p-0':
          item.menu,
      }"
    >

      <UserAvatarCurrentUser
          v-if="item.isCurrentUser"
          class="w-6 h-6 mr-3 inline-flex"
      />
      <div v-if="item.isNotification" class="relative">
        <BaseIcon

            :path="mdiBell"
            class="w-6 h-6 mr-3 inline-flex"

        />
        <div
            class="w-4 h-4 rounded-full flex justify-center items-center absolute -top-1 -left-1 text-2xs text-white font-mono"
            :class="{
          'bg-gray-500': usePage().props.auth.user.unread_notifications_count === 0,
          'bg-red-500' : usePage().props.auth.user.unread_notifications_count > 0
          }"
        >
          <p>
            {{ usePage().props.auth.user.unread_notifications_count }}
          </p>
        </div>

      </div>

      <BaseIcon v-if="item.icon" :path="item.icon" class="transition-colors"/>

      <span
          v-if="itemLabel"
          class="px-2 transition-colors"
          :class="{ 'lg:hidden': item.isDesktopNoLabel && item.icon }"
      >{{ itemLabel }}</span
      >

      <BaseIcon
          v-if="item.menu"
          :path="isDropdownActive ? mdiChevronUp : mdiChevronDown"
          class="hidden lg:inline-flex transition-colors"
      />
    </div>
    <div
        v-if="item.isNotification"
        ref="notification"
        class="text-sm border-b border-gray-100 lg:border lg:bg-white lg:absolute lg:top-full lg:left-0 lg:min-w-full lg:z-20 lg:rounded-lg lg:shadow-lg lg:dark:bg-slate-800 dark:border-slate-700"
        :class="{'lg:hidden': !isDropdownActive}"
    >
      <NotificationCard></NotificationCard>
    </div>
    <div
        v-if="item.menu"
        class="text-sm border-b border-gray-100 lg:border lg:bg-white lg:absolute lg:top-full lg:left-0 lg:min-w-full lg:z-20 lg:rounded-lg lg:shadow-lg lg:dark:bg-slate-800 dark:border-slate-700"
        :class="{ 'lg:hidden': !isDropdownActive }"
    >
      <NavBarMenuList :menu="item.menu" @menu-click="menuClickDropdown"/>
    </div>
  </component>
</template>
