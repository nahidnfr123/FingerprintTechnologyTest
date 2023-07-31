import {useAuthStore} from "~/stores/auth";
import {storeToRefs} from "pinia";

export default defineNuxtRouteMiddleware((to, from) => {
  // if (process.server) return
  const authStore = useAuthStore()
  const {isAuthenticated} = storeToRefs(authStore)
  // if (authStore.isLoggedIn) return abortNavigation() //Abort page navigation 404
  if (isAuthenticated.value) return navigateTo('/') // redirect to home page ...
})
