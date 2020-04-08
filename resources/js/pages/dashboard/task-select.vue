<template>
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
            <WlSuggest
              :value="activeProjectId"
              :text="activeProjectName"
              :suggest-callback="loadSuggestions"
              data-test="project-input"
              @selected="handleProjectSelected"
            />
          </div>
          <div class="col-label label-width-2">
            タスク名:
          </div>
          <div class="col input-width-2">
            <WlSuggest
              ref="taskSuggest"
              :value="activeTaskId"
              :text="activeTaskName"
              :suggest-callback="loadTaskSuggestionsCallback"
              :disabled="!canEditTask"
              data-test="task-input"
              @selected="handleTaskSelected"
            />
          </div>
          <div class="col input-width-1">
            <a
              :class="['icon-button', 'fas' ,'fa-plus-circle', canEditTask ? '' : 'icon-button-disabled']"
              data-test="add-task-button"
              title="新規タスク"
              @click="handleAddTaskClick"
            />
          </div>
        </div>
      </div>

      <div class="form form-align-left">
        <div class="row">
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
                    <input
                      v-model="resultHours"
                      type="number"
                      class="text-box"
                      data-test="result-hours"
                      size="5"
                      :disabled="!canInputResult"
                    > h
                  </div>
                </div>
                <div class="row">
                  <div class="col-label label-width-2">
                    メモ:
                  </div>
                  <div class="col input-width-2">
                    <textarea
                      v-model="resultMemo"
                      class="text-box"
                      data-test="result-memo"
                      style="width: 90%; height: 60px;"
                      :disabled="!canInputResult"
                    />
                  </div>
                </div>
                <div class="row row-align-right">
                  <button class="button" :disabled="!canRegisterResult" data-test="register-button" @click="handleRegisterResult">
                    登録
                  </button>
                </div>
              </div>
            </template>
          </WlSubFrame>
          <WlSubFrame size="s">
            <template slot="title">
              ポモドーロ
            </template>
            <template slot="body">
              <div class="pomodoro-container">
                <div class="pomodoro-column">
                  <TaskTimer
                    ref="taskTimer"
                    :task-id="1"
                  />
                </div>
                <div class="pomodoro-column-form">
                  <button v-show="isTimerStartButtonVisible" class="button" data-test="start-timer-button" @click="handleStartTimer">
                    スタート
                  </button>
                  <button v-show="isTimerStopButtonVisible" class="button" data-test="start-timer-button" @click="handleStopTimer">
                    停止
                  </button>
                </div>
              </div>
            </template>
          </WlSubFrame>
        </div>
      </div>

      <TaskFormContainer
        ref="addTaskForm"
        :project-id="activeProjectId"
        @taskRegistered="handleTaskRegistered"
      />
    </template>
  </Wlframe>
</template>

<script>

import { PomodoroController } from '../../domain/pomodoro/pomodoro'
import TaskFormContainer from '../tasks/task-form-container'
import TaskTimer from './task-timer'
import WlFrame from '../../components/wl-frame'
import WlSubFrame from '../../components/wl-sub-frame'
import WlSuggest from '../../components/form/wl-suggest'

const TIMER_STATE_STOPPED = 0
const TIMER_STATE_RUNNING = 1
const TIMER_STATE_PAUSED = 2
const pomodoroController = new PomodoroController()

export default {

  components: {
    TaskFormContainer,
    TaskTimer,
    WlFrame,
    WlSubFrame,
    WlSuggest
  },

  props: {
    loadSuggestions: {
      type: Function,
      required: true
    },
    loadTaskSuggestions: {
      type: Function,
      required: true
    }

  },

  data () {
    return {
      activeProjectId: 0,
      activeTaskId: 0,
      activeProjectName: '',
      activeTaskName: '',
      resultHours: 0.0,
      resultMemo: '',
      pomodoroState: null,
      timerState: TIMER_STATE_STOPPED,
    }
  },

  computed: {
    canInputResult () {
      return this.activeTaskId !== 0
    },
    canRegisterResult () {
      return this.activeTaskId !== 0 && this.resultHours > 0 && this.resultMemo !== ''
    },
    canEditTask () {
      return this.activeProjectId !== 0
    },
    canStartTimer () {
      return this.activeTaskId !== 0
    },
    isTimerStartButtonVisible () {
      return this.timerState === TIMER_STATE_STOPPED
    },
    isTimerStopButtonVisible () {
      return this.timerState === TIMER_STATE_RUNNING
    },

  },

  methods: {
    handleProjectSelected (payload) {
      this.activeProjectId = payload.value
      this.activeProjectName = payload.text

      if (this.activeProjectId === 0) {
        this.activeTaskId = 0
        this.activeTaskName = ''
        this.$refs.taskSuggest.init('', 0)
      }
    },
    handleTaskSelected (payload) {
      this.activeTaskId = payload.value
      this.activeTaskName = payload.text
    },
    async loadTaskSuggestionsCallback (keyword) {
      return await this.loadTaskSuggestions(this.activeProjectId, keyword)
    },

    async handleRegisterResult () {
      const done = () => {
        this.resultHours = 0
        this.resultMemo = ''
      }
      this.$emit('register-result', {
        taskId: this.activeTaskId,
        resultHours: this.resultHours,
        resultMemo: this.resultMemo,
      }, done)
    },

    async handleAddTaskClick () {
      const taskId = 0
      this.$refs.addTaskForm.open(taskId)
    },
    handleTaskRegistered (payload) {
      this.activeTaskId = payload.id
      this.activeTaskName = payload.title
      this.$refs.taskSuggest.init(payload.title, payload.id)
    },
    handleStartTimer () {
      this.pomodoroState = { ...pomodoroController.progressState() }
      this.timerState = TIMER_STATE_RUNNING
      this.$refs.taskTimer.start(this.pomodoroState.minutes * 60)
    },
    handleStopTimer () {
      pomodoroController.restoreState()
      this.pomodoroState = { ...pomodoroController.getCurrentState() }
      this.timerState = TIMER_STATE_STOPPED
      this.$refs.taskTimer.stop()
    }

  },

}

</script>

<style lang="scss" scoped>

.pomodoro-container {
  display: flex;

  .pomodoro-column-form {
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
    width: 100%;
  }
}

</style>
