<template>
  <div>
    <h1 class="heading-1">
      プロジェクト一覧
    </h1>

    <WlFrame size="xl" @keyup.enter="loadProjectList">
      <template slot="title">
        検索条件
      </template>
      <template slot="body">
        <section class="form form-align-left">
          <div class="row row-align-left">
            <label class="col-label">キーワード</label>
            <div class="col">
              <input
                v-model="conditions.keyword"
                type="text"
                class="text-box"
              >
            </div>
          </div>
          <div class="row row-align-left">
            <label class="col-label">ソート順</label>
            <div class="col">
              <WlDropDown
                v-model="conditions.sortOrder"
                name="sort-order"
                :menues="sortOrderList"
              />
            </div>
          </div>
          <div class="action-area">
            <button class="button" @click="loadProjectList">
              <i class="icon fas fa-search" />検索
            </button>
          </div>
        </section>
      </template>
    </WlFrame>

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
                    {{ project.total_estimated_hours }}
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
    </projectformcontainer>
  </div>
</template>

<script>
import ProjectFormContainer from './project-form-container'
import WlDropDown from '../../components/form/wl-dropdown'
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    ProjectFormContainer,
    WlDropDown,
    WlFrame,
    WlLoadingProxy
  },

  data () {
    return {
      checks: [],
      projectList: {},
      sortOrderList: {
        updated_at_desc: '更新日 降順',
        updated_at_asc: '更新日 昇順',
      },
      conditions: {
        keyword: '',
        sortOrder: 'updated_at_desc'
      }
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
      this.projectList = await projectAdapter.search(this.conditions)
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
