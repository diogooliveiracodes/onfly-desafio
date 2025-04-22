import axios from 'axios'

export const notificationService = {
    getAll: () => axios.get('http://localhost:8000/api/notifications'),
    markAsRead: (id: number) => axios.post(`http://localhost:8000/api/notifications/read/${id}`)
  }