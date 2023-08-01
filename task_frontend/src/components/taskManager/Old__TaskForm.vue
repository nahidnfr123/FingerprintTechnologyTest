<template>
  <div class="bg-gray-50 p-4 min-w-fit max-w-2xl mx-auto h-full">
    <h1 class="text-lg font-bold mb-2">{{ editIndex >= 0 ? 'Edit ' : 'Create New' }} Task</h1>
    <hr>
    <br>
    <FormKit
        type="form"
        id="formkitForm"
        @submit="submitHandler"
        :actions="false"
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
            :model-value="task.title || ''"
        />
        <FormKit
            type="datetime-local"
            name="due_date"
            label="Due Date"
            validation="required"
            :model-value="task.due || ''"
        />
      </div>
      <FormKit
          type="textarea"
          name="description"
          rows="2"
          label="Description"
          placeholder="Description"
          validation=""
          :model-value="task.description || ''"
      />

      <FormKit
          type="checkbox"
          label="Users"
          help="Select User to assign task"
          name="assigned_to"
          :options="usersOptions"
          validation-visibility="dirty"
          style="max-height: 100px; overflow-y: auto;"
          :model-value="['1','2','3']"
      />

      <FormKit
          type="submit"
          input-class="$reset w-full"
          :disabled="!valid || isLoading"
      >
        <!-- Custom Auth Button -->
        <AuthButton
            class-name="w-full py-4 rounded-lg"
            :disabled="!valid || isLoading"
            :isLoading="!!isLoading"
            :text="(editIndex >= 0 ? 'Edit ': 'Create ') + ' Task'"
        />
      </FormKit>
    </FormKit>
  </div>
</template>


<script setup>
import {onMounted, ref} from "vue";
import {redirectTo, throwFormError} from "@/composables/useCommon";
// import {useRoute, useRouter} from "vue-router";
import {useTaskStore} from "@/stores/task";
import $api from "@/composables/useRequest";
import AuthButton from "@/components/common/buttons/AuthButton.vue";


// const route = useRoute()
// const router = useRouter()
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

const loadUsers = async () => {
  const response = await $api.get('/users-options', {showSuccess: false,});
  if (response.message === 'success') {
    usersOptions.value = response.data
  }
}

const submitHandler = async (payload, node) => {
  if (isLoading.value) return
  node.clearErrors() // clear Previous form errors ...
  isLoading.value = true
  const formData = new FormData()
  formData.append('title', payload.title || '')
  formData.append('due_date', payload.due_date || '')
  formData.append('description', payload.description || '')
  if (payload.assigned_to.length) {
    payload.assigned_to.forEach((item) => {
      formData.append('assigned_to[]', item)
    })
  }
  // formData.append('assigned_to[]', payload.assigned_to)
  const response = await taskStore.store(formData)

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