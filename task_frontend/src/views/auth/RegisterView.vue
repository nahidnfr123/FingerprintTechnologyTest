<template>
  <AuthFormContainer title="Register">
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
      <FormKit
          type="text"
          name="name"
          placeholder="Full Name"
          validation="required|matches:/[a-zA-Z]/"
          :validation-messages="{ matches: 'Name must not include a number.' }"
          help="Your full name."
      />
      <FormKit
          type="text"
          name="email"
          placeholder="Email Address"
          validation="required|email"
          help=""
      />
      <FormKit
          type="password"
          name="password"
          validation="required|?length:6"
          :validation-messages="{
              matches: 'Please include at least one symbol',
              length: 'Try to make your password longer!',
            }"
          placeholder="Password"
          help="At-least 6 characters."
      />
      <FormKit
          type="password"
          name="password_confirm"
          placeholder="Confirm password"
          validation="required|confirm"
          help=""
      />

      <div class="mt-6">
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
              text="Register"
          />
        </FormKit>
      </div>
      <!--      <pre wrap>{{ value }}</pre>-->
    </FormKit>
    <p class="mt-4 text-center">Already have a account?
      <RouterLink to="/auth/login" class="text-center underline text-primary-color">Login</RouterLink>
    </p>
  </AuthFormContainer>
</template>

<script setup>
import {useAuthStore} from "@/stores/auth";
import AuthButton from "@/components/common/buttons/AuthButton.vue";
import {redirectTo, throwFormError} from "@/composables/useCommon";
import {ref} from "vue";
import AuthFormContainer from "@/components/AuthFormContainer.vue";
import {useRoute, useRouter} from "vue-router";

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)

// Handel Registration Form Submit ...
const submitHandler = async (payload, node) => {
  if (isLoading.value) return
  node.clearErrors() // clear Previous form errors ...
  isLoading.value = true

  // Prepare data for Upload ..
  const formData = new FormData()
  formData.append('name', payload.name)
  formData.append('email', payload.email)
  formData.append('password', payload.password)
  formData.append('password_confirmation', payload.password_confirm)

  // Send data to Pinia Store ...
  const response = await authStore.register(formData) // call to register action in the auth store ...

  if (response.message === 'error') {
    throwFormError(response.data, node) // Show Server side errors in form ...
  } else {
    node.reset()
    redirectTo(router, route, '/profile') /// Redirect to ?next or to given path ...
  }

  isLoading.value = false
}
</script>
