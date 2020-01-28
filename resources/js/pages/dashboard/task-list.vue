<template>
  <div>
    <table class="small-table task-list">
      <tr>
        <th>タスク名</th>
        <th>実績/見積工数(h)</th>
      </tr>
      <tr v-for="task in tasks" :key="task.id">
        <td>
          <router-link :to="`/task/${task.id}`">
            {{
              task.title
            }}
          </router-link>
        </td>
        <td class="col-task-stat">
          {{ task.actual_time }} / {{ task.estimate_minutes }}h
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    tasks: {
      type: Array,
      required: true
    }
  }
}
</script>

<style lang="scss" scoped>

.task-list {
  .col-task-stat {
    text-align: right;
    width: 150px;
  }
}

</style>
