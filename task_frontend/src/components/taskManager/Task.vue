<template>
  <div v-if="props.data && Object.keys(props.data).length" class="bg-white p-2 mb-2 rounded-lg flex flex-wrap justify-between duration-200"
       :class="editIndex === index ? '!bg-gray-200 border-blue-600 border-2' : ''">
    <div>
      <h3 class="font-bold text-sky-500">{{ props.data.title }}</h3>
      <p><small>{{ props.data.description }}</small></p>
      <p><small>Due Date: <strong>{{ props.data.due_date }}</strong></small></p>

      <div>
        <button
            v-if="props.data.created_by == user.id"
            class="rounded bg-blue-600 text-white px-2"
            @click="editItem(index, props.data)"
        >
          Edit
        </button>
      </div>
    </div>
    <div>
      <div v-if="props.data.users && props.data.users.length">
        <p><small>Assigned To: <strong>{{ props.data.users.length }}</strong></small></p>
        <div class="flex -space-x-4">
          <template v-for="user in props.data.users.slice(0, 3)">
            <img
                class="w-10 h-10 border-2 border-white rounded-full dark:border-gray-800"
                :src="user.avatar || 'https://cdn3.iconfinder.com/data/icons/web-design-and-development-2-6/512/87-1024.png'"
                :alt="user.name"
                :title="user.name"
            >
          </template>
          <template v-if="props.data.users.length - 3 > 0">
            <a class="flex items-center justify-center w-10 h-10 text-xs font-medium text-white bg-gray-700 border-2 border-white rounded-full hover:bg-gray-600 dark:border-gray-800" href="#">
              + {{ props.data.users.length - 3 }}
            </a>
          </template>
        </div>
        <div>
          <select v-model="status" name="status" id="" class="mt-2 bg-gray-200 rounded-lg py-1 px-2">
            <option value="open">Open</option>
            <option value="in-progress">In Progress</option>
            <option value="done">Done</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {useAuthStore} from "@/stores/auth";
import {storeToRefs} from "pinia";
import {useTaskStore} from "@/stores/task";
import {ref, watch} from "vue";

const authStore = useAuthStore();
const {user} = storeToRefs(authStore);

const taskStore = useTaskStore();
const {editIndex} = storeToRefs(taskStore)

const status = ref(props.data.status || 'open')

const props = defineProps({
  data: {type: Object, required: true,},
  index: {type: Number, required: true,}
})

watch(() => status.value, (newVal) => {
  const formData = new FormData()
  formData.append('status', newVal || 'open')
  if (newVal !== props.data.status) taskStore.updateTaskStatus(formData, props.data.id,)
})
watch(() => props.data.status, (newVal) => {
  status.value = newVal
})

const editItem = (index, task) => {
  taskStore.editIndex = index
  taskStore.editItem = task
}
</script>