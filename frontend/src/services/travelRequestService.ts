import axios from 'axios'

export const travelRequestService = {
  getAll: () => axios.get('http://localhost:8000/api/travel-requests'),
  create: (data: any) => axios.post('http://localhost:8000/api/travel-requests', data),
  updateStatus: (id: number, status: number) =>
    axios.put(`http://localhost:8000/api/travel-requests/change-status/${id}`, { status }),
}
