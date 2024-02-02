<script setup>
import {computed} from "vue";

const test = "سعید شرفی";
const props = defineProps({
    username: {
        type: String,
        required: true,
    },
    avatar: {
        type: String,
        default: null,
    },
    api: {
        type: String,
        default: "avataaars",
    },
});

const avatar = computed(
    () =>
        props.avatar ??
        `https://ui-avatars.com/api/?name=${(props.username.replace(' ','+'))}
        &font-size=0.40&color=random&background=random&bold=true`
);

const username = computed(() => props.username);
</script>

<template>
    <div>
        <img
            :src="avatar"
            :alt="username"
            class="rounded-full block h-auto w-full max-w-full bg-gray-100 dark:bg-slate-800"
        />
        <slot />
    </div>
</template>
