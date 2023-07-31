import {useAuthStore} from "~/stores/auth";
import {storeToRefs} from "pinia";

export default defineNuxtRouteMiddleware((to, from) => {
    const authStore = useAuthStore()
    const {isAuthenticated, token} = storeToRefs(authStore)
    if (process.server && !token.value) { /// IN server only check for token ....
        return navigateTo('/auth/login?next=' + to.fullPath)
    } else if (process.client && !isAuthenticated.value) { // while in client check for both token and user data ...
        return navigateTo('/auth/login?next=' + to.fullPath)
    }
})
