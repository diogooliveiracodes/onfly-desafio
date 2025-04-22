<template>
  <v-app>
    <!-- Barra de Navegação -->
    <v-app-bar app color="primary" dark>
      <v-toolbar-title>Desafio Onfly</v-toolbar-title>
    </v-app-bar>

    <!-- Conteúdo Principal -->
    <v-main>
      <v-container class="d-flex justify-center align-center" style="height: 100vh;">
        <v-card width="40%" min-width="400px" outlined>
          <v-card-title class="text-h5 justify-center">Login</v-card-title>
          <v-card-text>
            <v-form ref="loginForm" v-model="formIsValid" @submit.prevent="handleLogin">
              <v-text-field v-model="email" label="Email" type="email" outlined dense :rules="emailRules" required
                class="mb-4" />
              <v-text-field v-model="password" label="Senha" type="password" outlined dense :rules="passwordRules"
                required class="mb-4" />
              <v-btn type="submit" color="primary" block class="mt-4">Entrar</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const email = ref<string>('')
const password = ref<string>('')
const deviceName = ref<string>('')

const loginForm = ref()
const formIsValid = ref(false)

const auth = useAuthStore()
const router = useRouter()

// Regras de validação
const emailRules = [
  (v: string) => !!v || 'E-mail é obrigatório',
  (v: string) => /.+@.+\..+/.test(v) || 'E-mail inválido'
]

const passwordRules = [
  (v: string) => !!v || 'Senha é obrigatória'
]

onMounted(() => {
  const userAgent = navigator.userAgent
  const platform = navigator.platform
  deviceName.value = `${platform} - ${userAgent}`
})

const handleLogin = async () => {
  const { valid } = await loginForm.value.validate()
  if (!valid) return

  try {
    await auth.login(email.value, password.value, deviceName.value)
    router.push('/dashboard')
  } catch (error) {
    alert('Erro ao fazer login.')
  }
}
</script>

<style scoped>
.v-card {
  padding: 20px;
}

.v-card-title {
  text-align: center;
  font-weight: bold;
}

.v-btn {
  margin-top: 20px;
}

.mb-4 {
  margin-bottom: 16px;
}

.mt-4 {
  margin-top: 16px;
}
</style>
