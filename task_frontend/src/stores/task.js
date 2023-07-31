import {defineStore} from "pinia";
import $api from "@/composables/useRequest";
import accessToken from "@/composables/useToken";

export const useTaskStore = defineStore('task', {
    state: () => ({
        tasks: [],
        isLoading: false
    }),
    getters: {
        isAuthenticated: (state) => {
            return !!(state.token && (state.user && state.user.id))
        },
    },
    actions: {
        async getTasks() {
            const response = await $api.get('/tasks');
            if (response.message === 'success') {
                this.tasks = response.data
            }
        },
        async store(payload) {
            const notifyPayload = {successMessage: 'Task Created Successfully!'}
            const response = await $api.post('/tasks', payload, notifyPayload);
            if (response.message === 'success') {
                this.tasks.push(response.data)
            }
            return response
        },
    }
})
