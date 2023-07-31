<template>
  <Container>
    <h1 class="my-10  font-bold tracking-tight text-gray-900 text-3xl sm:text-4xl text-center">Task</h1>

    <div>
      <TaskForm/>
      <TaskList/>
    </div>
  </Container>
</template>

<script setup>
import Container from "@/components/common/Container.vue";
import TaskForm from "@/components/taskManager/TaskForm.vue";
import TaskList from "@/components/taskManager/TaskList.vue";
</script>
