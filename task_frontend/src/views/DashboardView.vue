<template>
  <Container>
    <h1 class="my-10  font-bold tracking-tight text-gray-900 text-3xl sm:text-4xl text-center">Task</h1>

    <div>
      <div class="flex flex-wrap gap-3">
        <div class="flex-1">
          <TaskForm :task="editItem" :editIndex="editIndex"/>
          <!--          <template v-if="editIndex >= 0">-->
          <!--          </template>-->
          <!--          <template v-else>-->
          <!--            <TaskForm/>-->
          <!--          </template>-->
        </div>
        <div class="flex-1">
          <TaskList :tasks="tasks" heading="Owned Task List"/>
        </div>
        <div class="flex-1">
          <TaskList :tasks="myTodos" heading="Tasks Assigned To You"/>
        </div>
      </div>
    </div>
  </Container>
</template>

<script setup>
import Container from "@/components/common/Container.vue";
import TaskList from "@/components/taskManager/TaskList.vue";
import {useTaskStore} from "@/stores/task";
import {storeToRefs} from "pinia";
import TaskForm from "@/components/taskManager/TaskForm.vue";
import {onMounted} from "vue";

const taskStore = useTaskStore();
const {editItem, editIndex, tasks, myTodos} = storeToRefs(taskStore);

onMounted(async () => {
  await taskStore.getTasks()
  await taskStore.getTodos()
})
</script>
