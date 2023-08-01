<template>
  <div class="bg-gray-100 p-4 min-w-fit max-w-2xl mx-auto h-full">
    <h1 class="text-lg font-bold mb-2 sticky">{{heading}}</h1>
    <div class="max-h-[600px] overflow-auto">
      <div v-if="tasks && tasks.length">
        <template v-for="(task, i) in tasks">
          <Task :data="task" :index="i"/>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import Task from "@/components/taskManager/Task.vue";

defineProps({
  heading: {type: String, default: 'Heading',},
  tasks: {type: Array, default: () => [],}
})
</script>