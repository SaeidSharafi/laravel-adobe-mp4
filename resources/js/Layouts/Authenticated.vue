<script setup>
import {computed, reactive, ref} from 'vue';
import Alert from "@/Components/Alert/Alert.vue";
import {router, usePage} from '@inertiajs/vue3'
import {
  mdiBackburger,
  mdiCheckCircle,
  mdiCloseCircle,
  mdiForwardburger,
  mdiInformation,
  mdiMenu,
  mdiTrashCan
} from "@mdi/js";
import menuAside from "@/menuAside.js";
import menuNavBar from "@/menuNavBar.js";
import {useLayoutStore} from "@/admin/layout.js";
import {useStyleStore} from "@/admin/style.js";
import BaseIcon from "@/Components/Layout/BaseIcon.vue";
import FormControl from "@/Components/Form/FormControl.vue";
import NavBar from "@/Components/Nav/NavBar.vue";
import NavBarItemPlain from "@/Components/Nav/NavBarItemPlain.vue";
import AsideMenu from "@/Components/Nav/AsideMenu.vue";
import {Modal} from "momentum-modal";

import {DialogsWrapper} from "vuejs-confirm-dialog";

const alert_key = ref(1);
const alertConfig = reactive({
    design: "filled",
    content: "filledContent",
    closeable: true,
    radius: 1,
    icon: "check-circle",
    title: "filledTitle",
});
const computedIcon = computed(() =>{
  switch (usePage().props.flash.status?.icon) {
    case "icon_deleted":
      return  mdiTrashCan;
    case "icon_failed":
      return mdiCloseCircle;
    case "icon_success":
      return mdiCheckCircle;
    case "icon_info":
      return mdiInformation;

  }
    return mdiInformation;
})

router.on('finish', () => {
    alert_key.value = Math.ceil(Math.random() * 10);
})
const mainClass = computed(() => {
    return {
        503: 'error-page',
        500: 'error-page',
        404: 'error-page',
        403: 'error-page',
    }[usePage().props.status]

});
router.on('navigate', () => {
    layoutStore.isAsideMobileExpanded = false
    layoutStore.isAsideLgActive = false
})

const layoutAsidePadding = "xl:pr-60";
const styleStore = useStyleStore();
const layoutStore = useLayoutStore();
const asideMenuComputed = computed(() => {
    switch (usePage().props.dashboard) {
        case "manager":
            return menuAside.manager;
        case "teacher":
            return menuAside.teacher;
        case "student":
            return menuAside.student;
    }
    return [];
})
const menuClick = (event, item) => {
    if (item.isToggleLightDark) {
        styleStore.setDarkMode();
    }
    if (item.isLogout) {
        router.post(route('logout'))
    }
};
</script>

<template>
    <div>

        <Modal/>
        <div
            :class="{
      dark: styleStore.darkMode,
      'overflow-hidden lg:overflow-visible': layoutStore.isAsideMobileExpanded
    }"
        >
            <div
                :class="[
        layoutAsidePadding,
        { 'mr-60 lg:mr-0': layoutStore.isAsideMobileExpanded },
      ]"
                class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
            >
                <NavBar
                    :menu="menuNavBar"
                    :class="[
          layoutAsidePadding,
          { 'mr-60 lg:mr-0': layoutStore.isAsideMobileExpanded },
        ]"
                    @menu-click="menuClick"
                >
                    <NavBarItemPlain
                        display="flex lg:hidden"
                        @click.prevent="layoutStore.asideMobileToggle()"
                    >
                        <BaseIcon
                            :path="
              layoutStore.isAsideMobileExpanded
                ? mdiBackburger
                : mdiForwardburger
            "
                            size="24"
                        />
                    </NavBarItemPlain>
                    <NavBarItemPlain
                        display="hidden lg:flex xl:hidden"
                        @click.prevent="layoutStore.isAsideLgActive = true"
                    >
                        <BaseIcon :path="mdiMenu" size="24"/>
                    </NavBarItemPlain>
                    <NavBarItemPlain use-margin>
                        <FormControl
                            placeholder="Search (ctrl+k)"
                            ctrl-k-focus
                            transparent
                            borderless
                        />
                    </NavBarItemPlain>
                </NavBar>
                <AsideMenu :menu="asideMenuComputed" @menu-click="menuClick"/>

                <main :class="mainClass">
                    <div v-if="usePage().props.flash.status">
                        <Alert
                            :key="alert_key"
                            :design="alertConfig.design"
                            :color="$page.props.flash.status.color"
                            :radius=1
                            :closeable="true"
                        >
                            <template #icon>
                                <BaseIcon :path="computedIcon" size="24" h="12" w="12"/>
                            </template>
                            {{ $page.props.flash.status.msg }}
                        </Alert>
                    </div>

                    <slot/>
                </main>

            </div>
        </div>
    </div>
    <DialogsWrapper/>
</template>
