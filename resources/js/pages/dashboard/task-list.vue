<template>
  <div>
    <table class="small-table task-list">
      <tr>
        <th>タスク名</th>
        <th class="col-head-due-date">
          完了予定日
        </th>
        <th class="col-head-task-stat">
          実績/見積工数(h)
        </th>
      </tr>
      <tr v-for="task in tasks" :key="task.id">
        <td>
          <router-link :to="`/task/${task.id}`">
            {{
              task.title
            }}
          </router-link>
        </td>
        <td class="col-due-date">
          {{ formatDate(task.end_date) }}
        </td>
        <td class="col-task-stat">
          {{ task.actual_time }} / {{ task.estimate_minutes }}h
        </td>
      </tr>
    </table>
  </div>
</template>

<script>

import dayjs from 'dayjs'

export default {
  props: {
    tasks: {
      type: Array,
      required: true
    }
  },

  methods: {
    formatDate (date) {
      if (!date) {
        return ''
      }
      return dayjs(date).format('YYYY-MM-DD')
    }
  }
}
</script>

<style lang="scss" scoped>

.task-list {
  .col-due-date {
    text-align: right;
    width: 150px;
  }

  .col-head-task-stat {
    display: none;
  }
  .col-task-stat {
    display: none;
    text-align: right;
    width: 150px;
  }
}

@media (min-width: 1400px) {
  .task-list {
    .col-head-task-stat {
      display: table-cell;
    }
    .col-task-stat {
      display: table-cell;
    }
  }
}
</style>
