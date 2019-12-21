<template>
  <div id="#dashboard-page">
    <h1 class="heading-1">
      ダッシュボード
    </h1>

    <div class="frame-list clear-fix">
      <WlFrame>
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
      <WlFrame>
        <template slot="title">
          プロジェクト毎実績
        </template>
        <template slot="body" />
      </WlFrame>

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
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    WlFrame,
    WlLoadingProxy,
    ProjectFormContainer,
    ProjectList,
    TaskList
  },
  data () {
    return {
      projects: [],
      projectTaskCountList: {},
      nearDeadlineTasks: {},
      inProgressTasks: {}
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
    }
  }
}
</script>

<style lang="scss" sceped>
  #dashboard-page {
    width: 100%;
  }

  .project-list {
    td {
      width: 100%;
    }
  }
</style>
