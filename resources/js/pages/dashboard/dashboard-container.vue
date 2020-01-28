<template>
  <div id="#dashboard-page">
    <h1 class="heading-1">
      ダッシュボード
    </h1>

    <div class="frame-list clear-fix">
      <WlFrame size="xl">
        <template slot="title">
          現在のタスク
        </template>
        <template slot="body">
          <div class="form form-align-left">
            <div class="row">
              <div class="col-label label-width-3">
                プロジェクト名:
              </div>
              <div class="col input-width-2">
                <WlPopupSelect
                  :value="activeProjectId"
                  :text="activeProjectName"
                  @openPopup="handleOpenTaskSelectPopup"
                />
              </div>
              <div class="col-label label-width-2">
                タスク名:
              </div>
              <div class="col input-width-2">
                <WlPopupSelect :value="activeTaskId" :text="activeTaskName" @openPopup="handleOpenTaskSelectPopup" />
              </div>
            </div>
          </div>

          <WlSubFrame size="s">
            <template slot="title">
              実績
            </template>
            <template slot="body">
              <div class="form form-align-left">
                <div class="row">
                  <div class="col-label label-width-2">
                    作業時間:
                  </div>
                  <div class="col input-width-2">
                    <input type="number" class="text-box" size="5" :disabled="!canInputResult" v-model="resultHours"> min
                  </div>
                </div>
                <div class="row">
                  <div class="col-label label-width-2">
                    メモ:
                  </div>
                  <div class="col input-width-2">
                    <textarea class="text-box" style="width: 90%; height: 60px;" :disabled="!canInputResult" v-model="resultMemo" />
                  </div>
                </div>
                <div class="row row-align-right">
                  <button class="button" :disabled="!canRegisterResult" @click="handleRegisterResult">
                    登録
                  </button>
                </div>
              </div>
            </template>
          </WlSubFrame>
        </template>
      </Wlframe>
    </div>

    <div class="frame-list clear-fix">
      <WlFrame size="xl">
        <template slot="title">
          プロジェクト一覧
        </template>
        <template slot="body">
          <WlLoadingProxy :loading-function="loadProjectList">
            <template slot="done">
              <ProjectList
                :projects="projects"
                @openNewProjectForm="handleOpenNewProjectForm"
              />
            </template>
          </WlLoadingProxy>
        </template>
      </WlFrame>
      <!-- <WlFrame>
        <template slot="title">
          プロジェクト毎実績
        </template>
        <template slot="body" />
      </WlFrame> -->

      <WlFrame>
        <template slot="title">
          作業中のタスク一覧
        </template>
        <template slot="body">
          <WlLoadingProxy :loading-function="loadInProgressTaskList">
            <template slot="done">
              <TaskList :tasks="inProgressTasks" />
            </template>
          </WlLoadingProxy>
        </template>
      </WlFrame>
      <WlFrame>
        <template slot="title">
          期限の近いタスク一覧
        </template>
        <template slot="body">
          <WlLoadingProxy :loading-function="loadNearDeadlineList">
            <template slot="done">
              <TaskList :tasks="nearDeadlineTasks" />
            </template>
          </WlLoadingProxy>
        </template>
      </WlFrame>
    </div>
    <ProjectFormContainer
      ref="projectFormContainer"
      @projectSaved="handleProjectSaved"
    />
    <TaskSelectPopupContainer
      ref="taskSelectPopupContainer"
      :active-project-id="activeProjectId"
      :active-project-name="activeProjectName"
      :active-task-id="activeTaskId"
      :active-task-name="activeTaskName"
      @setProjectAndTask="handleSetProjectAndTask"
    />
  </div>
</template>

<script>
import ProjectFormContainer from '../project/project-form-container'
import ProjectList from './project-list'
import TaskList from './task-list'
import TaskSelectPopupContainer from './task-select-popup-container'
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import WlPopupSelect from '../../components/form/wl-popup-select'
import WlSubFrame from '../../components/wl-sub-frame'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    WlFrame,
    WlLoadingProxy,
    WlPopupSelect,
    WlSubFrame,
    ProjectFormContainer,
    ProjectList,
    TaskList,
    TaskSelectPopupContainer
  },
  data () {
    return {
      activeProjectId: 0,
      activeTaskId: 0,
      activeProjectName: '',
      activeTaskName: '',
      projects: [],
      projectTaskCountList: {},
      nearDeadlineTasks: {},
      inProgressTasks: {},
      resultHours: 0.0,
      resultMemo: '',
    }
  },

  computed: {
    canInputResult () {
      return this.activeTaskId !== 0
    },
    canRegisterResult () {
      return this.activeTaskId !== 0 && this.resultHours > 0
    }
  },

  mounted () {
    this.$emit('changeSideMenu', 'default')
  },

  methods: {
    async loadProjectList () {
      const dashboardAdapter = adapterFactory.get('DashboardAdapter')
      this.projects = await dashboardAdapter.getProjectList()
    },
    async loadNearDeadlineList () {
      const dashboardAdapter = adapterFactory.get('DashboardAdapter')
      this.nearDeadlineTasks = await dashboardAdapter.getNearDeadlineTaskList()
    },
    async loadInProgressTaskList () {
      const dashboardAdapter = adapterFactory.get('DashboardAdapter')
      this.inProgressTasks = await dashboardAdapter.getInProgressTaskList()
    },
    handleOpenNewProjectForm () {
      this.$refs.projectFormContainer.open(0)
    },
    handleProjectSaved () {
      this.loadProjectList()
    },
    handleOpenTaskSelectPopup () {
      this.$refs.taskSelectPopupContainer.open()
    },
    handleSetProjectAndTask (payload) {
      this.activeProjectId = payload.projectId
      this.activeProjectName = payload.projectName
      this.activeTaskId = payload.taskId
      this.activeTaskName = payload.taskName
    },
    async handleRegisterResult () {
      const dashboardAdapter = adapterFactory.get('DashboardAdapter')
      await dashboardAdapter.registerResult(this.activeTaskId, {
        hours: this.resultHours,
        memo: this.resultMemo,
      })
      this.resultHours = 0
      this.resultMemo = ''
    }
  }
}
</script>

<style lang="scss" sceped>
  #dashboard-page {
    width: 100%;
  }

</style>
