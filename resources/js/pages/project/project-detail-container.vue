<template>
  <div>
    <div class="heading">
      <h1 class="heading-1 heading-item">
        {{ project.project_name }}
      </h1>
      <div class="heading-item">
        <button
          class="button edit-button"
          @click="openProjectFormHandler"
        >
          <i class="icon fas fa-pen" />編集
        </button>
      </div>
    </div>

    <WlFrame size="xl" no-min-height:="true" no-title:="true">
      <template slot="body">
        <div class="description">
          {{ project.description }}
        </div>
      </template>
    </WlFrame>

    <WlTabBar :items="tabItems" :initial-selected="selectedTab" />

    <div class="clear-fix">
      <WlFrame class="frame-item" :width="'400px;'">
        <template slot="title">
          タスク完了件数
        </template>
        <template slot="body">
          <div class="stat-number-box">
            <strong class="stat-number">
              {{ taskStat.weekly_done_count }}
            </strong>
          </div>
        </template>
      </WlFrame>
      <WlFrame class="frame-item" :width="'400px;'">
        <template slot="title">
          タスク完了件数(日別)
        </template>
        <template slot="body">
          <WlLoadingProxy :loading-function="loadTaskStat">
            <template slot="done">
              <table class="small-table">
                <tr
                  v-for="(dailyCount,
                          date) in taskStat.daily_done_list"
                  :key="date"
                >
                  <td>{{ date }}</td>
                  <td class="col-number">
                    {{ dailyCount }}
                  </td>
                </tr>
              </table>
            </template>
          </WlLoadingProxy>
        </template>
      </WlFrame>
    </div>
    <h2 class="heading-2">
      直近の状況
    </h2>
    <div class="clear-fix">
      <ScheduledTaskList
        class="frame-item"
        :width="'400px;'"
        :task-list="scheduledTasks"
        :loading-function="loadScheduledTasks"
        @addTaskLog="addTaskLogHandler"
      />
      <WlFrame class="frame-item" :width="'400px;'">
        <template slot="title">
          期限の近いタスク
        </template>
        <template slot="body" />
      </WlFrame>
      <InProgressTaskList
        class="frame-item"
        :width="'400px;'"
        :task-list="inProgressTasks"
        :loading-function="loadInProgressTasks"
        @addTaskLog="addTaskLogHandler"
      />
      <TaskLogFormContainer ref="taskLogForm" />
      <ProjectFormContainer
        ref="projectForm"
        @projectSaved="projectSavedHandler"
      />
    </div>
  </div>
</template>

<script>
import InProgressTaskList from './in-progress-task-list'
import ProjectFormContainer from '../project/project-form-container'
import ScheduledTaskList from './scheduled-task-list'
import TaskLogFormContainer from '../tasks/task-log-form-container'
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import WlTabBar from '../../components/wl-tab-bar'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    InProgressTaskList,
    ProjectFormContainer,
    ScheduledTaskList,
    TaskLogFormContainer,
    WlFrame,
    WlLoadingProxy,
    WlTabBar
  },
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      project: {},
      taskStat: {},
      scheduledTasks: [],
      inProgressTasks: [],
      selectedTab: 'summary',
      tabItems: {
        summary: {
          tabKey: 'summary',
          label: '概要',
        },
        tasks: {
          tabKey: 'tasks',
          label: 'タスク一覧',
        },

      }
    }
  },

  mounted () {
    this.handleMounted()
  },

  methods: {
    addTaskLogHandler (task) {
      this.$refs.taskLogForm.open(task, 0)
    },
    openProjectFormHandler () {
      this.$refs.projectForm.open(this.project.id)
    },
    handleMounted () {
      this.$emit('changeSideMenu', 'project', {
        id: this.id
      })

      const projectAdapter = adapterFactory.get('ProjectAdapter')
      projectAdapter.getProject(this.id).then((project) => {
        this.project = project
      })
    },
    async loadTaskStat () {
      const projectAdapter = adapterFactory.get('ProjectAdapter')
      this.taskStat = await projectAdapter.getTaskStat(this.id)
    },
    async loadScheduledTasks () {
      const projectAdapter = adapterFactory.get('ProjectAdapter')
      this.scheduledTasks = await projectAdapter.getScheduledTasks(
        this.id
      )
    },
    async loadInProgressTasks () {
      const projectAdapter = adapterFactory.get('ProjectAdapter')
      this.inProgressTasks = await projectAdapter.getInProgressTasks(
        this.id
      )
    },
    projectSavedHandler () {
      this.handleMounted()
    }
  },
  beforeRouteUpdate (to, from, next) {
    if (to !== from) {
      this.handleMounted()
    }
    next()
  }
}
</script>

<style lang="scss" scoped>
  .heading {
    display: flex;
    align-items: center;

    .heading-item {
    }
  }

  .edit-button {
    margin-left: 20px;
  }

  .description {
    white-space: pre-line;
    margin-bottom: 20px;
  }

  .frame-item {
    float: left;
  }
</style>
