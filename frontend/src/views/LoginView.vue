<template>
  <div class="login-container">
    <form class="login-form" @submit.prevent="handleLogin">
      <input v-model="email" type="email" placeholder="Email" />
      <input v-model="password" type="password" placeholder="Senha" />
      <input type="hidden" :value="deviceName" name="device_name" />
      <button type="submit">Entrar</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const email = ref<string>('')
const password = ref<string>('')
const deviceName = ref<string>('')

const auth = useAuthStore()
const router = useRouter()

onMounted(() => {
  const userAgent = navigator.userAgent
  const platform = navigator.platform
  deviceName.value = `${platform} - ${userAgent}`
})

const handleLogin = async () => {
  try {
    await auth.login(email.value, password.value, deviceName.value)
    router.push('/dashboard')
  } catch (error) {
    alert('Erro ao fazer login.')
  }
}
</script>
