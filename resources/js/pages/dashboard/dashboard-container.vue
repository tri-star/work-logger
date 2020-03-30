<template>
  <div id="#dashboard-page">
    <h1 class="heading-1">
      ダッシュボード
    </h1>

    <div class="frame-list clear-fix">
      <TaskSelect
        :load-suggestions="loadSuggestions"
        :load-task-suggestions="loadTaskSuggestions"
        @register-result="handleRegisterResult"
      />
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
  </div>
</template>

<script>
import ProjectFormContainer from '../project/project-form-container'
import ProjectList from './project-list'
import TaskList from './task-list'
import TaskSelect from './task-select'
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    WlFrame,
    WlLoadingProxy,
    ProjectFormContainer,
    ProjectList,
    TaskList,
    TaskSelect,
  },
  data () {
    return {
      projects: [],
      projectTaskCountList: {},
      nearDeadlineTasks: {},
      inProgressTasks: {},
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
    async loadSuggestions (keyword) {
      const dashboardAdapter = adapterFactory.get('DashboardAdapter')
      return dashboardAdapter.getProjectSuggestionList(keyword)
    },
    async loadTaskSuggestions (projectId, keyword) {
      const dashboardAdapter = adapterFactory.get('DashboardAdapter')
      return dashboardAdapter.getTaskSuggestionList(projectId, keyword)
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
    async handleRegisterResult (payload, onDone) {
      const dashboardAdapter = adapterFactory.get('DashboardAdapter')
      await dashboardAdapter.registerResult(payload.taskId, {
        hours: payload.resultHours,
        memo: payload.resultMemo,
      })

      onDone()
    }
  }
}
</script>

<style lang="scss" sceped>
  #dashboard-page {
    width: 100%;
  }

</style>
