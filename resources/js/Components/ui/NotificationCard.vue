<script setup>
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'
import {cn} from '@/lib/utils'
import BaseButton from "@/Components/Form/BaseButton.vue";
import {computed} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import forEach from "lodash-es/forEach";

const notifications = computed(() => {
  let notifications = [];
  console.log(usePage().props.auth.user);
  if (usePage().props.auth.user?.latest_notifications) {

    forEach(usePage().props.auth.user?.latest_notifications, (notification) => {
      notifications.push(
          {
            id: notification.id,
            title: notification.data.title,
            description: notification.data.description,
            read_at: notification.read_at,
          }
      )
    })
  }
  return notifications
})
const unreadCount = computed(() => {
  let count = 0;
  console.log(usePage().props.auth.user);
  if (usePage().props.auth.user?.latest_notifications) {
    forEach(usePage().props.auth.user?.latest_notifications, (notification) => {
      if (!notification.read_at) {
        count++;
      }
    })
  }
  return count
})

function markAsRead(notification_id) {
  console.log(notification_id)
  router.post(
      route('users.notification.mark-as-read', {notification: notification_id}), {
        only: ['auth.user'],
        preserveScroll: true,
      })
}
function markAllAsRead() {
  router.post(
      route('users.notification.mark-all-as-read',{}), {
        only: ['auth.user'],
        preserveScroll: true,
      })
}
</script>

<template>

  <Card :class="cn('w-[380px]', $attrs.class ?? '')">
    <CardHeader>
      <CardTitle>Notifications</CardTitle>
      <CardDescription>You have {{ unreadCount }} unread messages.</CardDescription>
    </CardHeader>
    <CardContent class="grid gap-4">
      <div>
        <template v-for="(notification, index) in notifications" :key="index">

          <div
              class="notification-row mb-4 grid grid-cols-[25px_1fr] items-start pb-4 last:mb-0 last:pb-0"
              @click.stop="markAsRead(notification.id)"
          >
            <span class="flex h-2 w-2 translate-y-1 rounded-full"
                  :class="{
              'bg-sky-500' : !notification.read_at,
              'bg-gray-500' : notification.read_at,
            }"/>
            <div class="space-y-1">
              <p class="text-sm font-medium leading-none">
                {{ notification.title }}
              </p>
              <p class="text-sm text-muted-foreground">
                {{ notification.description }}
              </p>
            </div>
          </div>
        </template>
      </div>
    </CardContent>
    <CardFooter>
      <div class="flex justify-between gap-10">
        <BaseButton color="success" type="button" class="w-full" @click.stop="markAllAsRead()" label="Mark all as read">
        </BaseButton>
        <BaseButton color="info" type="button" class="w-full" @click.stop="markAllAsRead()" label="View all">
        </BaseButton>
      </div>

    </CardFooter>
  </Card>
</template>