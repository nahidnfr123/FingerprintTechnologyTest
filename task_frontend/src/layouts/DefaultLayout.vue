<template>
  <main class="min-h-screen">
    <TheHeader :hideLoginButton="false"/>
    <main class="min-h-[70vh] mb-10">
      <slot/>
    </main>
    <TheFooter/>

    <notification-list/>
  </main>
</template>

<script setup>
import TheHeader from "@/components/common/TheHeader.vue";
import TheFooter from "@/components/common/TheFooter.vue";
import {defineComponent} from "vue";

defineComponent({
  name: 'DefaultLayout'
})
</script>
