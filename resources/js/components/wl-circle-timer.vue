<template>
  <div :class="['timer', disabled ? 'timer-disabled' : '']">
    <div class="slice slice-1" :style="`transform:rotate(${slice1Degree}deg); background-color: #f00;`" />
    <div class="slice slice-2" :style="`transform:rotate(calc(${slice2Degree}deg)); background-color: ${slice2Color};`" />
    <div class="timer-center">
      <span>{{ remainedSeconds }}</span>
    </div>
  </div>
</template>

<script>

export default {

  props: {
    taskId: {
      type: Number,
      required: true,
    },
    timeLimit: {
      type: Number,
      required: true
    },
    remainedSeconds: {
      type: Number,
      required: true
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },

  computed: {
    percentage () {
      if (this.remainedSeconds > this.timeLimit) {
        return 100
      }
      if (this.timeLimit <= 0 || this.remainedSeconds <= 0) {
        return 0
      }

      return (this.remainedSeconds / this.timeLimit) * 100
    },

    /**
     * 50%未満の時のゲージの角度を返す。
     */
    slice1Degree () {
      if (this.percentage > 50) {
        return 90
      }
      return (this.percentage / 100 * 360) - 90
    },

    /**
     * 50%以上の場合のゲージの角度を返す。
     */
    slice2Degree () {
      if (this.percentage <= 50) {
        return 0
      }
      return (this.percentage / 100 * 360)
    },
    slice2Color () {
      if (this.percentage <= 50) {
        return '#ddd'
      }
      return '#f00'
    }

  }

}

</script>

<style lang="scss" scoped>

.timer {
  position: relative;
  border-radius: 50%;
  overflow: hidden;
  width: 200px;
  height: 200px;
  background-color: #ddd;

  //グラフの余白部分(0%の時はslice-2)。
  .slice {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    // 上半分を塗りつぶす(50%未満の表現に使用する)
    &.slice-1 {
      clip: rect(0 200px 100px 0);
      background-color: #f00;
    }
    // 左半分を塗りつぶす(50%以上の表現に使用する。50%未満の時は色を変更する)
    &.slice-2 {
      clip: rect(0 100px 200px 0);
      background-color: #f00;
    }

  }

  // グラフの中央部分(ドーナツ状に表示するために使用)
  .timer-center {
    position: absolute;
    border-radius: 50%;
    top: 10px;
    left: 10px;
    width: 180px;  //calc(200px - (10px * 2))
    height: 180px;
    background-color: #fff;

    span {
      display: block;
      text-align: center;
      font-size: 40px;
      line-height: (200px - (10px * 2));
      color: #f00;
    }
  }

}

  .timer-disabled::after{
    display: block;
    z-index: 1000;
    width: 200px;
    height: 200px;
    background-color: rgba(255,255,255, 0.4)
  }

</style>
