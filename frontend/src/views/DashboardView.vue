<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useTravelRequestStore } from '@/stores/travelRequestStore'
import TravelRequestTable from '@/components/TravelRequestTable.vue'
import TravelRequestForm from '@/components/TravelRequestForm.vue'
import NotificationMenu from '@/components/NotificationMenu.vue'

const auth = useAuthStore()
const router = useRouter()
const travelRequestStore = useTravelRequestStore()
const showModal = ref(false)
const snackbar = ref(false)
const snackbarMessage = ref('')
const snackbarColor = ref<'success' | 'error'>('success')

const showSnackbar = (message: string, color: 'success' | 'error' = 'success') => {
  snackbarMessage.value = message
  snackbarColor.value = color
  snackbar.value = true
}

const handleSubmit = async (data: any) => {
  try {
    await travelRequestStore.createRequest(data)
    showSnackbar('Pedido criado com sucesso!', 'success')
  } catch {
    showSnackbar('Erro ao criar pedido', 'error')
  }
}

const handleStatusUpdate = async (id: number, status: number) => {
  try {
    await travelRequestStore.updateStatus(id, status)
    showSnackbar('Status atualizado com sucesso!', 'success')
  } catch {
    showSnackbar('Erro ao atualizar status', 'error')
  }
}

const handleLogout = async () => {
  try {
    auth.logout()
    router.push('/')
  } catch (error) {
    showSnackbar('Erro ao fazer logout', 'error')
  }
}

</script>

<template>
  <v-app>
    <v-app-bar app color="primary" dark>
      <v-toolbar-title>Desafio Onfly</v-toolbar-title>
      <v-spacer />
      <NotificationMenu />
      <v-btn height="72" min-width="164" @click="handleLogout">
        <v-icon left>mdi-exit-to-app</v-icon>
        Sair
      </v-btn>
    </v-app-bar>

    <v-main>
      <v-container class="mt-5">
        <v-row class="d-flex justify-end mb-4" v-if="!auth.isAdmin">
          <v-col cols="auto">
            <v-btn color="primary" @click="showModal = true" dense> Novo Pedido </v-btn>
          </v-col>
        </v-row>

        <TravelRequestTable @update-status="handleStatusUpdate" />

        <TravelRequestForm v-model="showModal" @submit="handleSubmit" />

        <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
          {{ snackbarMessage }}
        </v-snackbar>
      </v-container>
    </v-main>
  </v-app>
</template>
