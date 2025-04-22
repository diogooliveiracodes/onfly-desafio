import { defineStore } from 'pinia'
import { ref } from 'vue'
import { travelRequestService } from '@/services/travelRequestService'

export const useTravelRequestStore = defineStore('travelRequest', () => {
  const travelRequests = ref<any[]>([])
  const loading = ref(false)

  const fetchRequests = async () => {
    loading.value = true
    try {
      const { data } = await travelRequestService.getAll()
      travelRequests.value = data.travelRequests
    } finally {
      loading.value = false
    }
  }

  const createRequest = async (requestData: any) => {
    await travelRequestService.create(requestData)
    await fetchRequests()
  }

  const updateStatus = async (id: number, status: number) => {
    await travelRequestService.updateStatus(id, status)
    await fetchRequests()
  }

  const updateRequest = async (id: number, updatedData: any) => {
    await travelRequestService.update(id, updatedData)
    await fetchRequests()
  }

  return { travelRequests, loading, fetchRequests, createRequest, updateStatus, updateRequest }
})
