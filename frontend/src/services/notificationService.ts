import axios from 'axios'
import { getCookieValue } from '@/utils/getCookieValue'

axios.defaults.withCredentials = true
const xsrfToken = getCookieValue('XSRF-TOKEN')
axios.defaults.headers.common['X-XSRF-TOKEN'] = xsrfToken

export const notificationService = {
  getAll: () => axios.get('http://localhost:8000/api/notifications'),
  markAsRead: (id: number) => axios.post(`http://localhost:8000/api/notifications/read/${id}`)
}
