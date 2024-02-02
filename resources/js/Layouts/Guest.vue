<script setup>
import {useStyleStore} from "@/admin/style.js";

const styleStore = useStyleStore();
</script>

<template>
    <div :class="{ dark: styleStore.darkMode }"  class="bg-login">
        <div class="dark:text-slate-100">
          <div>
            <slot />
          </div>
        </div>
    </div>
</template>
