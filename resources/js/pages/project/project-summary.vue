<template>
  <div>
    <div class="frame-list">
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
    <div class="frame-list">
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
    </div>
    <div class="frame-list">
      <InProgressTaskList
        class="frame-item"
        :width="'400px;'"
        :task-list="inProgressTasks"
        :loading-function="loadInProgressTasks"
        @addTaskLog="addTaskLogHandler"
      />
      <TaskLogFormContainer ref="taskLogForm" />
    </div>
    <TaskLogFormContainer ref="taskLogForm" />
  </div>
</template>

<script>

import InProgressTaskList from './in-progress-task-list'
import ScheduledTaskList from './scheduled-task-list'
import TaskLogFormContainer from '../tasks/task-log-form-container'
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    InProgressTaskList,
    ScheduledTaskList,
    TaskLogFormContainer,
    WlFrame,
    WlLoadingProxy
  },
  props: {
    project: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      taskStat: {},
      scheduledTasks: [],
      inProgressTasks: []
    }
  },
  mounted () {
    this.handleMounted()
  },
  methods: {
    addTaskLogHandler (task) {
      this.$refs.taskLogForm.open(task, 0)
    },
    handleMounted () {
    },
    async loadTaskStat () {
      console.log(this.project)
      const projectAdapter = adapterFactory.get('ProjectAdapter')
      this.taskStat = await projectAdapter.getTaskStat(this.project.id)
    },
    async loadScheduledTasks () {
      const projectAdapter = adapterFactory.get('ProjectAdapter')
      this.scheduledTasks = await projectAdapter.getScheduledTasks(
        this.project.id
      )
    },
    async loadInProgressTasks () {
      const projectAdapter = adapterFactory.get('ProjectAdapter')
      this.inProgressTasks = await projectAdapter.getInProgressTasks(
        this.project.id
      )
    },
  },

}

</script>
