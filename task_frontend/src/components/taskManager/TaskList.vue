<template>
  <div>
    {{ tasks }}
  </div>
</template>

<script setup>
import {useTaskStore} from "@/stores/task";
import {storeToRefs} from "pinia";
import {onMounted} from "vue";


const taskStore = useTaskStore();
const {tasks} = storeToRefs(taskStore);

onMounted(async () => {
  await taskStore.getTasks()
})
</script>