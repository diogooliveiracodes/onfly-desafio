import { defineStore } from 'pinia'
import axios from 'axios'

interface AuthState {
    token: string | null
    role: string | null
}

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
                
                this.role = response.data.userRole
                localStorage.setItem('userRole', this.role) 

                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
            } catch (error) {
                throw new Error('Login inválido')
            }
        },

        logout() {
            this.token = null
            this.role = null
            localStorage.removeItem('token')
            localStorage.removeItem('userRole')
            localStorage.removeItem('user')
            delete axios.defaults.headers.common['Authorization']
        },
    }
})
