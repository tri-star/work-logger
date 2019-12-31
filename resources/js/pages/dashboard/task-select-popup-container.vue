<template>
  <WlModal :show-modal="showModal">
    <template v-slot:body>
      <div>
        <div class="form">
          <div class="row">
            <div class="col-label label-width-2">
              プロジェクト
            </div>
            <div class="col">
              <WlSuggest
                class="input-width-3"
                :value="projectId"
                :text="projectName"
                :suggest-callback="loadSuggestions"
                @selected="handleProjectSelected"
              />
            </div>
          </div>
          <div class="row">
            <div class="col-label label-width-2">
              タスク
            </div>
            <div class="col">
              <input type="text" class="text-box input-width-3" :value="taskName">
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button class="button" @click="handleSetProjectAndTask">
                決定
              </button>
              <button class="button" @click="handleClose">
                キャンセル
              </button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </WlModal>
</template>

<script>
import AdapterFactory from '../../adapters/adapter-factory'
import WlModal from '../../components/wl-modal'
import WlSuggest from '../../components/form/wl-suggest'

const dashboardAdapter = AdapterFactory.get('DashboardAdapter')

export default {
  components: {
    WlModal,
    WlSuggest
  },

  props: {
    activeProjectId: {
      type: Number,
      required: true
    },
    activeProjectName: {
      type: String,
      required: true
    },
    activeTaskId: {
      type: Number,
      required: true
    },
    activeTaskName: {
      type: String,
      required: true
    }
  },

  data () {
    return {
      showModal: false,
      id: 0,
      project: {},
      projectId: 0,
      projectName: '',
      taskId: 0,
      taskName: ''
    }
  },

  methods: {
    async open () {
      this.showModal = true
    },

    async loadSuggestions (keyword) {
      return dashboardAdapter.getProjectSuggestionList(keyword)
    },

    handleProjectSelected (payload) {
      this.projectId = payload.value
      this.projectName = payload.text
    },
    handleSetProjectAndTask () {
      this.$emit('setProjectAndTask', {
        projectId: this.projectId,
        projectName: this.projectName,
        taskId: this.taskId,
        taskName: this.taskName
      })
      this.showModal = false
    },

    handleClose () {
      this.showModal = false
    }

  }
}
</script>

<style lang="scss" scoped></style>
