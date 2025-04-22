import { defineStore } from 'pinia'
import axios from 'axios'
import { UserRoles } from '@/enums/UserRoles'
import { getCookieValue } from '@/utils/getCookieValue'

interface AuthState {
    token: string | null
    role: string | null
}

axios.defaults.baseURL = 'http://localhost:8000' // ou o IP do container backend
axios.defaults.withCredentials = true // necessário pro Laravel Sanctum

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => {
        const storedToken = localStorage.getItem('token')
        const storedRole = localStorage.getItem('userRole')
        return {
            token: storedToken ? storedToken : null,
            role: storedRole ? storedRole : null
        }
    },

    persist: true,

    getters: {
        isAdmin: (state) => state.role == UserRoles.ADMIN
    },

    actions: {
        initialize() {
            if (this.token) {
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            } else {
                this.token = null
            }
        },

        async login(email: string, password: string, device_name: string) {
            try {

                await axios.get('/sanctum/csrf-cookie')

                const xsrfToken = getCookieValue('XSRF-TOKEN')

                axios.defaults.headers.common['X-XSRF-TOKEN'] = xsrfToken

                const response = await axios.post('/api/login', {
                    email,
                    password,
                    device_name
                })
                this.token = response.data.token
                localStorage.setItem('token', this.token)

                this.role = response.data.userRole
                localStorage.setItem('userRole', this.role)

                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            } catch (error) {
                console.error(error)
                throw new Error('Login inválido')
            }
        },

        async logout() {
            await axios.post('/api/logout')
            this.role = null
            localStorage.removeItem('userRole')
        },
    }
})
