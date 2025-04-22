<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useTravelRequestStore } from '@/stores/travelRequestStore'
import { TravelRequestStatus } from '@/enums/TravelRequestStatus'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()
const travelRequestStore = useTravelRequestStore()
const statusFilter = ref('')
const loading = computed(() => travelRequestStore.loading)

const requests = computed(() => {
  if (!statusFilter.value) return travelRequestStore.travelRequests
  const statusMap: Record<string, number> = {
    'SOLICITADO': TravelRequestStatus.SOLICITADO,
    'APROVADO': TravelRequestStatus.APROVADO,
    'CANCELADO': TravelRequestStatus.CANCELADO
  }
  return travelRequestStore.travelRequests.filter(
    req => req.status === statusMap[statusFilter.value]
  )
})
const getStatusLabel = (status: number) => {
  const map: Record<number, string> = {
    [TravelRequestStatus.SOLICITADO]: 'SOLICITADO',
    [TravelRequestStatus.APROVADO]: 'APROVADO',
    [TravelRequestStatus.CANCELADO]: 'CANCELADO'
  }
  return map[status] || `DESCONHECIDO (${status})`
}
const emit = defineEmits(['update-status'])

const handleUpdate = (id: number, status: number) => {
  emit('update-status', id, status)
}

const handleEdit = (item: any) => {
  router.push({ name: 'edit-travel-request', params: { id: item.id } })
}

const computedHeaders = computed(() => {
  const base = [
    { title: '#', key: 'id' },
    ...(auth.isAdmin ? [{ title: 'Requerente', key: 'requester_name' }] : []),
    { title: 'Destino', key: 'destination' },
    { title: 'Data Partida', key: 'departure_date' },
    { title: 'Data Retorno', key: 'return_date' },
    { title: 'Status', key: 'status' },
    { title: 'Ações', key: 'actions', sortable: false },
  ]
  return base
})

onMounted(() => {
  travelRequestStore.fetchRequests()
})
</script>
<template>
  <div>
    <div v-if="loading" class="loading-overlay">
      <v-progress-circular indeterminate color="primary" size="50" />
      <div class="mt-2 text-subtitle-1">Carregando...</div>
    </div>

    <v-select v-model="statusFilter" :items="['', 'SOLICITADO', 'APROVADO', 'CANCELADO']" label="Filtrar por status"
      clearable density="compact" hide-details class="mb-4" />

    <v-data-table :items="requests" :loading="loading" :loading-text="' '" class="elevation-1"
      :headers="computedHeaders">
      <template #item="{ item }">
        <tr>
          <td>{{ item.id }}</td>
          <td v-if="auth.isAdmin">{{ item.requester_name }}</td>
          <td>{{ item.destination }}</td>
          <td>{{ item.departure_date }}</td>
          <td>{{ item.return_date }}</td>
          <td>{{ getStatusLabel(item.status) }}</td>
          <td v-if="auth.isAdmin">
            <v-menu>
              <template #activator="{ props }">
                <v-btn v-bind="props" icon>
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="handleUpdate(item.id, TravelRequestStatus.APROVADO)">
                  Aprovar
                </v-list-item>
                <v-list-item @click="handleUpdate(item.id, TravelRequestStatus.CANCELADO)">
                  Cancelar
                </v-list-item>
              </v-list>
            </v-menu>
          </td>
          <td v-if="!auth.isAdmin">
            <v-btn color="primary" @click="handleEdit(item)" dense
              :disabled="item.status !== TravelRequestStatus.SOLICITADO">
              Editar
            </v-btn>
          </td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 9999;
  background-color: rgba(255, 255, 255, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
</style>
