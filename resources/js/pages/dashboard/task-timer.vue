<template>
  <div>
    <WlCircleTimer :time-limit="timeLimit" :remained-seconds="remainedSeconds" />
  </div>
</template>

<script>

import WlCircleTimer from '../../components/wl-circle-timer'

const STATE_STOPPED = 0
const STATE_RUNNING = 1
const STATE_PAUSED = 2

export default {

  components: {
    WlCircleTimer
  },

  props: {
    taskId: {
      type: Number,
      required: true
    },
  },

  data () {
    return {
      state: STATE_STOPPED,
      timeLimit: 0,
      remainedSeconds: 0,
      timer: null
    }
  },

  methods: {
    start (timeLimit) {
      if (this.timer !== null) {
        clearTimeout(this.timer)
      }

      this.state = STATE_RUNNING
      this.timeLimit = timeLimit
      this.remainedSeconds = this.timeLimit
      this.timer = setInterval(() => {
        this.remainedSeconds--
        if (this.remainedSeconds <= 0) {
          clearTimeout(this.timer)
          this.state = STATE_STOPPED
          this.$emit('done')
        }
      }, 1000)
    },

    pause () {
      if (this.timer !== null) {
        clearTimeout(this.timer)
      }
      this.state = STATE_PAUSED
      this.$emit('paused')
    },

    stop () {
      if (this.timer !== null) {
        clearTimeout(this.timer)
      }
      this.state = STATE_STOPPED
      this.remainedSeconds = 0
      this.$emit('stopped')
    },
  }
}

</script>
