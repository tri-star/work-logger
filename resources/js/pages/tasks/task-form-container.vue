<template>
  <WlModal :show-modal="showModal">
    <template v-slot:body>
      <TaskForm ref="taskForm" @save="handleSave" @close="handleClose" />
    </template>
  </WlModal>
</template>

<script>
import AdapterFactory from '../../adapters/adapter-factory'
import Task from '../../domain/task'
import TaskForm from './task-form'
import WlModal from '../../components/wl-modal'

export default {

  components: {
    WlModal,
    TaskForm
  },
  props: {
    projectId: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      id: 0,
      showModal: false,
      statuses: Task.getStatusNames(),
      task: {}
    }
  },

  mounted () {},

  methods: {
    async loadTask (id) {
      const adapter = AdapterFactory.get('TaskAdapter')
      return await adapter.getTask(id)
    },
    async open (id) {
      this.id = id
      this.showModal = true
      if (this.id === 0) {
        this.task = {
          title: '',
          start_date: '',
          end_date: '',
          estimate_hours: 0,
          status: 0
        }
      } else {
        this.task = await this.loadTask(this.id)
      }
      this.$nextTick(() => {
        this.$refs.taskForm.init(this.id, this.task)
      })
    },
    async handleSave (task) {
      if (this.id === 0) {
        await this.addTask(task)
      } else {
        await this.updateTask(task)
      }
      this.$emit('taskSaved')
    },
    handleClose () {
      this.showModal = false
    },
    async addTask (task) {
      const adapter = AdapterFactory.get('TaskAdapter')
      const result = await adapter.addTask(this.projectId, task)
      this.$emit('taskRegistered', result.registered_task)
      this.showModal = false
    },
    async updateTask (task) {
      const adapter = AdapterFactory.get('TaskAdapter')
      await adapter.updateTask(this.id, task)
      this.$emit('taskRegistered')
      this.showModal = false
    }
  }
}
</script>

<style lang="scss" scoped></style>
