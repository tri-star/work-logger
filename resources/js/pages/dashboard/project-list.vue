<template>
  <div>
    <a class="icon-button fas fa-plus-circle" title="新規作成" @click="$emit('openNewProjectForm')" />
    <table class="small-table project-list">
      <tr>
        <th>プロジェクト名</th>
        <th>完了タスク数 / 総タスク数</th>
        <th>累計作業時間</th>
        <th>累計見積時間</th>
      </tr>
      <tr v-for="project in projects" :key="project.id" data-test="data-row">
        <td class="col-project-name" data-test="col-project-name">
          <router-link :to="`/project/${project.id}`">
            {{
              project.project_name
            }}
          </router-link>
        </td>
        <td class="col-count-stat" data-test="col-count-stat">
          {{ project.completed_task_count }} / {{ project.task_count }}
        </td>
        <td class="col-total-result-hours" data-test="col-total-result-hours">
          {{ hours(project.total_result_hours) }}h
        </td>
        <td class="col-total-estimate-hours" data-test="col-total-estimate-hours">
          {{ hours(project.total_estimate_hours) }}h
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    projects: {
      type: Object,
      required: true
    }
  },

  methods: {
    hours (hour) {
      const formatter = new Intl.NumberFormat('ja', {
        minimumIntegerDigits: 1,
        minimumFractionDigits: 1,
        maximumFractionDigits: 1,
      })
      return formatter.format(Number(hour))
    }
  }
}
</script>

<style lang="scss" scoped>

  .project-list {
    .col-count-stat {
      text-align: right;
      width: 250px;
    }
    .col-total-result-hours {
      text-align: right;
      width: 120px;
    }
    .col-total-estimate-hours {
      text-align: right;
      width: 120px;
    }
  }

</style>
