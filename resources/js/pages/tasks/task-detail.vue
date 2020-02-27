<template>
  <div>
    <h1 class="heading-1">
      {{ project.project_name }}: {{ task.title }}
    </h1>

    <h2 class="heading-2">
      詳細情報
    </h2>

    <div class="detail-table">
      <div class="detail-row">
        <label>課題番号</label>
        <div class="detail-col">
          {{ task.issue_no }}
        </div>
      </div>
      <div class="detail-row">
        <label>詳細</label>
        <div
          class="detail-col description"
          v-html="task.description"
        />
      </div>
      <div class="detail-row">
        <label>予定期間</label>
        <div class="detail-col">
          {{ task.start_date | format_date("YYYY-MM-DD", "", "") }} ～
          {{ task.end_date | format_date("YYYY-MM-DD", "", "") }}
        </div>
      </div>
      <div class="detail-row">
        <label>実績(予定工数)</label>
        <div class="detail-col">
          {{ task.actual_time }}h(予定: {{ task.estimate_minutes }}h)
        </div>
      </div>
      <div class="detail-row">
        <label>ステータス</label>
        <div class="detail-col">
          {{ getStatusName(task.status) }}
        </div>
      </div>
    </div>

    <div class="action-area">
      <button class="command-button" @click="$emit('openEditForm')">
        <i class="icon fas fa-pen" />編集
      </button>
      <button class="command-button" @click="$emit('openTaskLogForm')">
        <i class="icon fas fa-plus" />作業履歴
      </button>
    </div>

    <h2 class="heading-2">
      作業履歴
    </h2>

    <table class="list-table">
      <tr>
        <th class="col-checkbox">
          <input type="checkbox" @click="toggleChecks">
        </th>
        <th>ID</th>
        <th>作業概要</th>
        <th>ステータス</th>
        <th>作業時間</th>
        <th>更新日</th>
      </tr>
      <tr v-for="taskLog in taskLogs" :key="taskLog.id">
        <td class="col-checkbox">
          <input v-model="checks[taskLog.id]" type="checkbox">
        </td>
        <td>{{ taskLog.id }}</td>
        <td>{{ taskLog.memo }}</td>
        <td>{{ getStatusName(taskLog.status) }}</td>
        <td class="col-nnumber">
          {{ taskLog.hours }}h
        </td>
        <td>{{ taskLog.updated_at }}</td>
      </tr>
    </table>
  </div>
</template>

<script>
import Task from '../../domain/task'

export default {

  components: {},
  props: {
    task: {
      type: Object,
      required: true
    },
    project: {
      type: Object,
      required: true
    },
    taskLogs: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      checks: {},
      statuses: Task.getStatusNames()
    }
  },

  methods: {
    getStatusName (status) {
      return this.statuses[status]
    },
    toggleChecks (event) {
      this.taskLogs.forEach((log) => {
        this.$set(this.checks, log.id, event.target.checked)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .detail-table {
    display: table;
    width: 800px;
    margin-bottom: 20px;
    border-spacing: 5px;

    .detail-row {
      display: table-row;

      label {
        display: table-cell;
        width: 150px;
      }

      .detail-col {
        display: table-cell;
      }

      .detail-col-right {
        display: table-cell;
        text-align: right;
      }

      .description {
        white-space: pre-line;
      }
    }
  }

  .action-area {
    margin-bottom: 20px;
  }
</style>
