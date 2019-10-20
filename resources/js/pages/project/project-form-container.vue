<template>
  <WlModal :show-modal="showModal">
    <template v-slot:body>
      <ProjectForm
        ref="projectForm"
        @close="handleClose"
        @save="handleSave"
      />
    </template>
  </WlModal>
</template>

<script>
import AdapterFactory from '../../adapters/adapter-factory'
import ProjectForm from './project-form'
import WlModal from '../../components/wl-modal'

export default {
  components: {
    ProjectForm,
    WlModal
  },

  data () {
    return {
      showModal: false,
      id: 0,
      project: {}
    }
  },

  methods: {
    async open (id) {
      if (id) {
        this.id = id
        this.project = await this.loadProject(id)
      } else {
        this.id = 0
        this.project = {
          id: 0,
          project_name: '',
          description: ''
        }
      }
      this.showModal = true
      this.$nextTick(() => {
        this.$refs.projectForm.init(this.id, this.project)
      })
    },

    async handleSave (project) {
      const projectAdapter = AdapterFactory.get('ProjectAdapter')
      if (project.id) {
        await projectAdapter.editProject(this.id, project)
      } else {
        await projectAdapter.addProject(project)
      }
      this.showModal = false
      this.$emit('projectSaved')
    },

    handleClose () {
      this.showModal = false
    },

    async loadProject (id) {
      const projectAdapter = AdapterFactory.get('ProjectAdapter')
      const project = await projectAdapter.getProject(id)
      return project
    }
  }
}
</script>

<style lang="scss" scoped></style>
