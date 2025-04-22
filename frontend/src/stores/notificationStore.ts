import { defineStore } from 'pinia'
import { ref } from 'vue'
import { notificationService } from '@/services/notificationService'

export const useNotificationStore = defineStore('notification', () => {
  const notifications = ref<any[]>([])

  const fetchNotifications = async () => {
    const { data } = await notificationService.getAll()
    notifications.value = data.notifications
  }

  const markAsRead = async (id: number) => {
    await notificationService.markAsRead(id)
    await fetchNotifications()
  }

  return { notifications, fetchNotifications, markAsRead }
})
