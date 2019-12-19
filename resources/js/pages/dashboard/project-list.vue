<template>
  <div>
    <a class="icon-button fas fa-plus-circle" title="新規作成" @click="$emit('openNewProjectForm')" />
    <table class="small-table project-list">
      <tr>
        <th>プロジェクト名</th>
        <th>テスト</th>
      </tr>
      <tr v-for="project in projects" :key="project.id">
        <td>
          <router-link :to="`/project/${project.id}`">
            {{
              project.project_name
            }}
          </router-link>
        </td>
        <td>aaaaaaaaaaa</td>
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
  }
}
</script>

<style></style>
