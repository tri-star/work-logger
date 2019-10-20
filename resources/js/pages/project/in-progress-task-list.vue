<template>
  <WlFrame>
    <template slot="title">
      作業中のタスク
    </template>
    <template slot="body">
      <WlLoadingProxy :loading-function="loadingFunction">
        <template slot="done">
          <table v-if="taskList" class="small-table">
            <tr v-for="(task, id) of taskList" :key="id">
              <td>
                <router-link :to="`/task/${task.id}`">
                  {{ task.title }}
                </router-link>
              </td>
              <td class="col-number">
                {{ task.estimate_minutes }}h
              </td>
              <td class="col-icons">
                <a
                  class="add-log-button"
                  @click="$emit('addTaskLog', task)"
                >
                  <i class="icon fas fa-plus-circle" />
                </a>
              </td>
            </tr>
          </table>
        </template>
      </WlLoadingProxy>
    </template>
  </WlFrame>
</template>

<script>
import WlFrame from '../../components/wl-frame'
import WlLoadingProxy from '../../components/wl-loading-proxy'

export default {
  components: {
    WlFrame,
    WlLoadingProxy
  },
  props: {
    taskList: {
      type: Object,
      required: true
    },
    loadingFunction: {
      type: Function,
      required: true
    }
  },
  data () {
    return {}
  }
}
</script>

<style lang="scss" scoped>
  .add-log-button {
    cursor: pointer;
  }
</style>
