<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { useTravelRequestStore } from '@/stores/travelRequestStore'
import { TravelRequestStatus } from '@/enums/TravelRequestStatus'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
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

onMounted(() => {
  travelRequestStore.fetchRequests()
})
</script>

<template>
  <div>
    <v-select v-model="statusFilter" :items="['', 'SOLICITADO', 'APROVADO', 'CANCELADO']" label="Filtrar por status"
      clearable density="compact" hide-details class="mb-4" />
    <v-data-table :items="requests" :loading="loading" loading-text="Carregando...">
      <template #headers>
        <tr>
          <th>#</th>
          <th v-if="auth.isAdmin">Requerente</th>
          <th>Destino</th>
          <th>Data Partida</th>
          <th>Data Retorno</th>
          <th>Status</th>
          <th v-if="auth.isAdmin">Ações</th>
        </tr>
      </template>
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
        </tr>
      </template>
    </v-data-table>
  </div>
</template>
