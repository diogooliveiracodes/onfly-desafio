import axios from 'axios'
import { getCookieValue } from '@/utils/getCookieValue'

axios.defaults.withCredentials = true 
const xsrfToken = getCookieValue('XSRF-TOKEN')
axios.defaults.headers.common['X-XSRF-TOKEN'] = xsrfToken

export const travelRequestService = {
  getAll: () => axios.get('http://localhost:8000/api/travel-requests'),
  
  create: (data: any) => axios.post('http://localhost:8000/api/travel-requests', data),
  
  updateStatus: (id: number, status: number) =>
    axios.put(`http://localhost:8000/api/travel-requests/change-status/${id}`, { status }),

  update: (id: number, data: any) => 
    axios.put(`http://localhost:8000/api/travel-requests/${id}`, data),
}
