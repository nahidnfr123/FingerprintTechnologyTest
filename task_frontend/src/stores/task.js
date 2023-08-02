import {defineStore} from "pinia";
import $api from "@/composables/useRequest";
import accessToken from "@/composables/useToken";

export const useTaskStore = defineStore('task', {
    state: () => ({
        tasks: [],
        myTodos: [],
        editIndex: -1,
        editItem: {},
        isLoading: false
    }),
    getters: {
        isAuthenticated: (state) => {
            return !!(state.token && (state.user && Object.keys(state.user).length))
        },
    },
    actions: {
        async getTasks() {
            const response = await $api.get('/tasks', {showSuccess: false,});
            if (response.message === 'success') {
                this.tasks = response.data
            }
        },
        async getTodos() {
            const response = await $api.get('/todos', {showSuccess: false,});
            if (response.message === 'success') {
                this.myTodos = response.data
            }
        },
        async store(payload) {
            const notifyPayload = {successMessage: 'Task Created Successfully!'}
            const response = await $api.post('/tasks', payload, notifyPayload);
            if (response.message === 'success') {
                this.tasks.unshift(response.data)
            }
            return response
        },
        async update(payload, id) {
            const notifyPayload = {successMessage: 'Task Updated Successfully!'}
            const response = await $api.post('/tasks/' + id, payload, notifyPayload);
            if (response.message === 'success') {
                this.tasks[this.editIndex] = response.data
                this.editIndex = -1
                this.editItem = {}

                const index = this.myTodos.findIndex((task) => task.id === id)
                this.myTodos[index] = response.data

            }
            return response
        },
        async updateTaskStatus(payload, id) {
            const notifyPayload = {successMessage: 'Task Updated Successfully!'}
            const response = await $api.post('/task/update-status/' + id, payload, notifyPayload);
            if (response.message === 'success') {
                const index = this.tasks.findIndex((task) => task.id === id)
                this.tasks[index] = response.data

                const index2 = this.myTodos.findIndex((task) => task.id === id)
                this.myTodos[index2] = response.data
            }
            return response
        },
        async clearEdit() {
            this.editIndex = -1
            this.editItem = {}
        }
    }
})
