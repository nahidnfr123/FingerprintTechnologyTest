<template>
  <div class="bg-amber-50 p-4 max-w-2xl mx-auto">
    <h3><strong>Create A Task</strong></h3>
    <hr>
    <br>
    <FormKit
        type="form"
        id="formkitForm"
        submit-label="Register"
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
        />
        <FormKit
            type="date"
            name="due_date"
            label="Due Date"
            placeholder="Description"
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
            text="Create Task"
        />
      </FormKit>
    </FormKit>
  </div>
</template>


<script setup>
import {onMounted, ref} from "vue";
import {redirectTo, throwFormError} from "@/composables/useCommon";
import {useRoute, useRouter} from "vue-router";
import {useTaskStore} from "@/stores/task";
import $api from "@/composables/useRequest";
import AuthButton from "@/components/common/buttons/AuthButton.vue";


const route = useRoute()
const router = useRouter()
const isLoading = ref(false)
const taskStore = useTaskStore()
let usersOptions = ref([])

const loadUsers = async () => {
  const response = await $api.get('/users-options');
  if (response.message === 'success') {
    usersOptions.value = response.data
  }
}

const submitHandler = async (payload, node) => {
  if (isLoading.value) return
  node.clearErrors() // clear Previous form errors ...
  isLoading.value = true
  const formData = new FormData()
  formData.append('title', payload.title)
  formData.append('due_date', payload.due_date)
  formData.append('description', payload.description)
  payload.assigned_to.forEach((item) => {
    formData.append('assigned_to[]', item)
  })
  // formData.append('assigned_to[]', payload.assigned_to)
  const response = await taskStore.store(formData)

  if (response.message === 'error') {
    throwFormError(response.data, node)
  } else {
    node.reset()
    // redirectTo(router, route, '/profile')
  }

  isLoading.value = false
}

onMounted(() => {
  loadUsers()
})
</script>