<template>
  <div>
    <table class="small-table project-list">
      <tr>
        <th>タスク名</th>
      </tr>
      <tr v-for="task in tasks" :key="task.id">
        <td>
          <router-link :to="`/task/${task.id}`">
            {{
              task.title
            }}
          </router-link>
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

<style></style>
