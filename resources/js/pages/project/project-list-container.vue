<template>
  <div>
    <h1 class="heading-1">
      プロジェクト一覧
    </h1>

    <WlFrame size="xl" @keyup.enter="loadProjectList">
      <template slot="body">
        <section>
          <section class="action-area">
            <button class="button" @click="openNewForm">
              <i class="icon fas fa-plus-circle" />新規登録
            </button>
          </section>

          <WlLoadingProxy :loading-function="loadProjectList">
            <template slot="done">
              <table class="list-table">
                <tr>
                  <th>ID</th>
                  <th class="col-task-name">
                    プロジェクト名
                  </th>
                  <th>総タスク数</th>
                  <th>総作業時間(h)</th>
                  <th>総見積時間(h)</th>
                  <th>最終更新日時</th>
                </tr>
                <tr v-for="project in projectList" :key="project.id">
                  <td class="col-number col-id">
                    {{ project.id }}
                  </td>
                  <td class="col-project-name">
                    <router-link :to="`/project/${project.id}`">
                      {{
                        project.project_name
                      }}
                    </router-link>
                  </td>
                  <td class="col-task-count">
                    {{ project.task_count }}
                  </td>
                  <td class="col-total-result-hours">
                    {{
                      project.total_result_hours
                    }}h
                  </td>
                  <td class="col-total-estimate-hours">
                    {{ project.total_estimate_hours }}
                  </td>
                  <td class="col-date-time">
                    {{ project.updated_at | format_date('YYYY-MM-DD HH:mm:ss') }}
                  </td>
                </tr>
              </table>
            </template>
          </WlLoadingProxy>
        </section>
      </template>
    </WlFrame>
    <ProjectFormContainer
      ref="projectForm"
    />
  </div>
</template>

<script>
import ProjectFormContainer from './project-form-container'
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    ProjectFormContainer,
    WlFrame,
    WlLoadingProxy
  },

  data () {
    return {
      projectList: {},
    }
  },
  computed: {
  },

  mounted () {
    this.handleMounted()
  },

  methods: {
    handleProjectSaved () {
      this.loadProjectList()
    },
    async loadProjectList () {
      const projectAdapter = adapterFactory.get('ProjectAdapter')
      this.projectList = await projectAdapter.search({})
    },

    openNewForm () {
      this.$refs.projectForm.open(0)
    },
    openEditForm (id) {
      this.$refs.projectForm.open(id)
    },
    handleMounted () {
      this.loadProjectList()
    },
  },
  beforeRouteUpdate (to, from, next) {
    // if (to !== from) {
    this.handleMounted()
    // }
    next()
  }
}
</script>

<style lang="scss" scoped>

  .form {
    .col-label {
      width: 120px;
      margin-right: 5px;
    }
    .col {
      margin-right: 10px;
    }
  }

  .button-area {
    margin-top: 10px;
  }

  .action-area {
    margin-bottom: 10px;
  }

  .col-id {
    width: 60px;
  }

  .col-project-name {
  }

  .col-task-count {
    text-align: right;
  }
  .col-total-result-hours {
    text-align: right;
  }
  .col-total-estimate-hours {
    text-align: right;
  }

  .col-date-time {
    text-align: right;
    width: 200px;
  }
</style>
