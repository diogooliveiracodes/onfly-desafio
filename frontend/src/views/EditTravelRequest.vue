<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTravelRequestStore } from '@/stores/travelRequestStore'

const route = useRoute()
const router = useRouter()

const travelRequestStore = useTravelRequestStore()
const travelRequest = ref<any>(null)

const fetchTravelRequest = async (id: number) => {
  await travelRequestStore.fetchRequests()
  const request = travelRequestStore.travelRequests.find(req => req.id === id)
  travelRequest.value = request
}

onMounted(() => {
  const id = Number(route.params.id)
  fetchTravelRequest(id)
})

const handleSubmit = async () => {
  try {
    await travelRequestStore.updateRequest(travelRequest.value.id, travelRequest.value)
    router.push({ name: 'dashboard', query: { success: 'Pedido de viagem atualizado com sucesso!' } })
  } catch (error) {
    console.error('Erro ao atualizar pedido:', error)
    router.push({ name: 'dashboard', query: { error: 'Erro ao atualizar o pedido de viagem' } })
  }
}
</script>

<template>
  <v-app>
    <v-app-bar app color="primary" dark>
      <v-toolbar-title>Editar Pedido de Viagem</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <v-form v-if="travelRequest">
          <v-text-field v-model="travelRequest.destination" label="Destino" required />
          <v-text-field v-model="travelRequest.departure_date" label="Data de Partida" type="date" required />
          <v-text-field v-model="travelRequest.return_date" label="Data de Retorno" type="date" required />

          <v-btn color="primary" @click="handleSubmit">Salvar</v-btn>
          <v-btn @click="$router.push('/dashboard')">Cancelar</v-btn>
        </v-form>
      </v-container>
    </v-main>
  </v-app>
</template>
