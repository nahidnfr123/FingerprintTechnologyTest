<template>
  <div class="bg-gray-50 p-4 min-w-fit max-w-2xl mx-auto h-full">
    <h1 class="text-lg font-bold mb-2">{{ editIndex >= 0 ? 'Edit ' : 'Create New' }} Task</h1>
    <hr>
    <br>
    <FormKit
        type="form"
        @submit="submitHandler"
        :actions="false"
        v-model="values"
        #default="{ value, state: { valid } }"
        #error="{error}"
        incomplete-message="Please fill in the form correctly."
    >
      <div class="grid grid-cols-2 gap-x-4">
        <FormKit
            type="text"
            name="title"
            label="Title"
            placeholder="Title"
            validation="required"
        />
        <FormKit
            type="datetime-local"
            name="due_date"
            label="Due Date"
            validation="required"
        />
      </div>
      <FormKit
          type="textarea"
          name="description"
          rows="2"
          label="Description"
          placeholder="Description"
          validation=""
      />

      <FormKit
          type="checkbox"
          label="Users"
          help="Select User to assign task"
          name="assigned_to"
          :options="usersOptions"
          validation-visibility="dirty"
          style="max-height: 100px; overflow-y: auto;"
      />

      <div class="flex gap-3">
        <FormKit
            type="submit"
            input-class="$reset w-full flex-1 !mb-0"
            :disabled="!valid || isLoading"
        >
          <!-- Custom Auth Button -->
          <AuthButton
              class-name="w-full py-4 rounded-lg !mb-0"
              :disabled="!valid || isLoading"
              :isLoading="!!isLoading"
              :text="(editIndex >= 0 ? 'Update ': 'Save ') + ' Task'"
          />
        </FormKit>
        <div v-if="editIndex >= -0">
          <button
              type="button"
              class=" py-4 px-6 overflow-hidden font-semibold text-white transition-all duration-150
       ease-in-out rounded-lg hover:pl-10 hover:pr-6 hover:shadow-lg bg-yellow-500 group shadow-lg
       disabled:pointer-events-none text-center"
              @click="clearEdit()">
            Clear
          </button>
        </div>
      </div>
    </FormKit>
  </div>
</template>


<script setup>
import {onMounted, ref, watch} from "vue";
import {redirectTo, throwFormError} from "@/composables/useCommon";
import {useTaskStore} from "@/stores/task";
import $api from "@/composables/useRequest";
import AuthButton from "@/components/common/buttons/AuthButton.vue";

const values = ref({
  title: '',
  description: '',
  due_date: '',
  assigned_to: '',
})
const isLoading = ref(false)
const taskStore = useTaskStore()
const usersOptions = ref([])

const props = defineProps({
  editIndex: {
    type: Number,
    default: -1,
  },
  task: {
    type: Object,
    default: () => {
      return {}
    },
  },
})


// watch task
watch(() => props.task, (newVal) => {
  console.log('newVal', newVal)
  if (Object.keys(newVal).length) {
    values.value = {
      id: newVal.id,
      title: newVal.title,
      description: newVal.description,
      due_date: newVal.due,
      assigned_to: newVal.users.map((item) => item.id),
    }
  } else {
    values.value = {
      title: '',
      description: '',
      due_date: '',
      assigned_to: '',
    }
  }
})

const loadUsers = async () => {
  const response = await $api.get('/users-options', {showSuccess: false,});
  if (response.message === 'success') {
    usersOptions.value = response.data
  }
}

const clearEdit = async () => {
  taskStore.clearEdit()
}
const submitHandler = async (payload, node) => {
  if (isLoading.value) return
  node.clearErrors() // clear Previous form errors ...
  isLoading.value = true
  const formData = new FormData()
  if (payload.id) formData.append('_method', 'PUT')
  formData.append('title', payload.title || '')
  formData.append('due_date', payload.due_date || '')
  formData.append('description', payload.description || '')
  if (payload.assigned_to.length) {
    payload.assigned_to.forEach((item) => {
      formData.append('assigned_to[]', item)
    })
  }
  // formData.append('assigned_to[]', payload.assigned_to)
  let response = {}
  if (props.editIndex >= 0) response = await taskStore.update(formData, payload.id)
  else response = await taskStore.store(formData)

  if (response.message === 'error') {
    throwFormError(response.data, node)
  } else {
    node.reset()
  }

  isLoading.value = false
}

onMounted(() => {
  loadUsers()
})
</script>