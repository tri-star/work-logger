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

    <WlFrame size="xl" :no-min-height="true" :no-title="true">
      <template slot="body">
        <div class="description">
          {{ project.description }}
        </div>
      </template>
    </WlFrame>

    <WlTabBar :items="tabItems" :initial-selected="selectedTab" @tabChange="handleTabChange" />

    <keep-alive v-if="project.id != undefined">
      <component :is="tabMap[selectedTab]" :project="project" keep-alive />
    </keep-alive>

    <ProjectFormContainer
      ref="projectForm"
      @projectSaved="projectSavedHandler"
    />
  </div>
</template>

<script>
import ProjectFormContainer from '../project/project-form-container'
import ProjectSummary from './project-summary'
import TaskListContainer from './task-list-container'
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import WlTabBar from '../../components/wl-tab-bar'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    ProjectFormContainer,
    ProjectSummary,
    TaskListContainer,
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

      },
      tabMap: {
        summary: 'ProjectSummary',
        tasks: 'TaskListContainer',
      }
    }
  },

  mounted () {
    this.handleMounted()
  },

  methods: {
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
    projectSavedHandler () {
      this.handleMounted()
    },
    handleTabChange (newTab) {
      this.selectedTab = newTab
    },
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
