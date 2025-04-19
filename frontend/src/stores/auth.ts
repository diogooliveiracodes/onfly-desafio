import { defineStore } from 'pinia'
import axios from 'axios'

interface AuthState {
    token: string | null
}

export const useAuthStore = defineStore('auth', {
    state: (): AuthState => {
        const storedToken = localStorage.getItem('token')
        return {
            token: storedToken ? storedToken : null
        }
    },

    persist: true,

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
                const response = await axios.post('http://localhost:8000/api/login', {
                    email,
                    password,
                    device_name
                })

                this.token = response.data.token
                localStorage.setItem('token', this.token)
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            } catch (error) {
                throw new Error('Login inválido')
            }
        },

        logout() {
            this.token = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            delete axios.defaults.headers.common['Authorization']
        },
    }
})
