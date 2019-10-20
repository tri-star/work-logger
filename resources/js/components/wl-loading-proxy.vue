<template>
  <div>
    <slot v-if="state === 'loading'" name="loading">
      <WlLoading />
    </slot>
    <slot v-if="state === 'done'" name="done" />
    <slot v-if="state === 'error'" name="error">
      Error
    </slot>
  </div>
</template>

<script>
import WlLoading from './wl-loading'

export default {
  components: {
    WlLoading
  },
  props: {
    loadingFunction: {
      Type: Function,
      required: true
    }
  },
  data () {
    return {
      state: 'loading'
    }
  },
  mounted () {
    this.state = 'loading'
    this.loadingFunction()
      .then(() => {
        this.state = 'done'
      })
      .catch((error) => {
        this.state = 'error'
      })
  }
}
</script>

<style lang="scss" scoped></style>
