<script setup lang="ts">
import { onMounted, onUnmounted, computed } from 'vue'
import { useNotificationStore } from '@/stores/notificationStore'
import { useTravelRequestStore } from '@/stores/travelRequestStore'

const notificationStore = useNotificationStore()
const travelRequestStore = useTravelRequestStore()

const unreadCount = computed(() =>
  notificationStore.notifications.filter(n => !n.read_at).length
)

const getStatusLabel = (status: number) => {
  const map: Record<number, string> = {
    1: 'SOLICITADO',
    2: 'APROVADO',
    3: 'CANCELADO'
  }
  return map[status] || `DESCONHECIDO (${status})`
}

const select = async (notification: any) => {
  await notificationStore.markAsRead(notification.id)
  await travelRequestStore.fetchRequests()
}

let intervalId: ReturnType<typeof setInterval>

onMounted(() => {
  notificationStore.fetchNotifications()
  intervalId = setInterval(() => {
    notificationStore.fetchNotifications()
  }, 5000)
})

</script>

<template>
  <v-menu offset-y>
    <template #activator="{ props }">
      <v-btn icon v-bind="props">
        <template v-if="unreadCount > 0">
          <v-badge :content="unreadCount" color="red" overlap>
            <v-icon>mdi-bell</v-icon>
          </v-badge>
        </template>
        <template v-else>
          <v-icon>mdi-bell</v-icon>
        </template>
      </v-btn>
    </template>

    <v-list style="min-width: 300px">
      <v-list-item-group v-if="notificationStore.notifications.length">
        <v-list-item v-for="n in notificationStore.notifications" :key="n.id"
          :class="{ 'font-weight-bold': !n.read_at }" @click="select(n)">
          <v-list-item-content>
            <v-list-item-title style="font-size: 0.8rem">
              <v-icon v-if="!n.read_at" color="red" size="small" class="mr-1">
                mdi-alert-circle
              </v-icon>
              O Pedido #{{ n.travel_request_id }} teve o status atualizado para {{ getStatusLabel(n.new_status) }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list-item-group>
      <v-list-item v-else>
        <v-list-item-content>Nenhuma notificação</v-list-item-content>
      </v-list-item>
    </v-list>
  </v-menu>
</template>
