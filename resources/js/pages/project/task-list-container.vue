<template>
  <div>
    <section class="search-form clear-fix" @keyup.enter="loadTaskList">
      <div class="row">
        <label class="label">キーワード</label>
        <div class="col">
          <input
            v-model="conditions.keyword"
            type="text"
            class="text-box"
          >
        </div>
      </div>
      <div class="row">
        <label class="label">状態</label>
        <div class="col">
          <span
            v-for="(statusName, statusCode) in statusList"
            :key="statusCode"
            :for="`status-${statusCode}`"
          >
            <WlCheckBox
              :id="`search-status-${statusCode}`"
              v-model="conditions.statuses"
              :value="statusCode"
              :label="statusName"
            />
          </span>
        </div>
      </div>
      <div class="row">
        <label class="label">ソート順</label>
        <div class="col">
          <WlDropDown
            v-model="conditions.sortOrder"
            name="sort-order"
            :menues="sortOrderList"
          />
        </div>
      </div>
      <div class="row">
        <div class="col">
          <button class="button" @click="loadTaskList">
            <b class="icon fas fa-search" />
          </button>
        </div>
      </div>
    </section>

    <section>
      <section class="action-area">
        <button class="command-button" @click="openNewForm">
          <i class="icon fas fa-plus-circle" />新規登録
        </button>
        <BulkActionButton
          title="一括操作"
          :disabled="!itemChecked"
          :menu-list="bulkActionMenuList"
        />
      </section>

      <WlLoadingProxy :loading-function="loadTaskList">
        <template slot="done">
          <table class="list-table">
            <tr>
              <th class="col-checkbox">
                <input
                  type="checkbox"
                  @change="
                    toggleChecks($event.target.checked)
                  "
                >
              </th>
              <th>ID</th>
              <th class="col-task-name">
                タスク名
              </th>
              <th>ステータス</th>
              <th>開始日</th>
              <th>終了日</th>
              <th>更新日</th>
            </tr>
            <tr v-for="task in taskList" :key="task.id">
              <td class="col-checkbox">
                <input
                  v-model="checks"
                  type="checkbox"
                  :value="task.id"
                >
              </td>
              <td class="col-number col-id">
                {{ task.id }}
              </td>
              <td class="col-task-name">
                <router-link :to="`/task/${task.id}`">
                  {{
                    task.title
                  }}
                </router-link>
              </td>
              <td class="col-status">
                {{ getStatusName(task.status) }}
              </td>
              <td class="col-date">
                {{
                  task.start_date
                    | format_date("YYYY-MM-DD", "-")
                }}
              </td>
              <td class="col-date">
                {{
                  task.end_date
                    | format_date("YYYY-MM-DD", "")
                }}
              </td>
              <td class="col-datetime">
                {{ task.updated_at }}
              </td>
            </tr>
          </table>
        </template>
      </WlLoadingProxy>
    </section>
    <TaskFormContainer
      ref="taskForm"
      :project="project"
      @taskRegistered="handleTaskRegistered"
    />
    <BulkDateUpdateFormContainer ref="bulkDateUpdateFormContainer" />
    <BulkStateUpdateFormContainer ref="bulkStateUpdateFormContainer" />
  </div>
</template>

<script>
import BulkActionButton from '../../components/bulk-action-button'
import BulkDateUpdateFormContainer from './bulk-date-update-form-container'
import BulkStateUpdateFormContainer from './bulk-state-update-form-container'
import Task from '../../domain/task.js'
import TaskFormContainer from '../tasks/task-form-container'
import WlCheckBox from '../../components/form/wl-checkbox'
import WlDropDown from '../../components/form/wl-dropdown'
import WlLoadingProxy from '../../components/wl-loading-proxy'
import adapterFactory from '../../adapters/adapter-factory'

export default {
  components: {
    BulkActionButton,
    BulkDateUpdateFormContainer,
    BulkStateUpdateFormContainer,
    TaskFormContainer,
    WlCheckBox,
    WlDropDown,
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
      checks: [],
      taskList: {},
      bulkActionMenuList: [
        {
          title: '状態の変更',
          handler: () => {
            this.$refs.bulkStateUpdateFormContainer.open(
              this.checks
            )
          }
        },
        {
          title: '開始日・終了日の変更',
          handler: () => {
            this.$refs.bulkDateUpdateFormContainer.open(this.checks)
          }
        }
      ],
      statusList: window._.pick(Task.getStatusNames(), [
        Task.STATE_NONE,
        Task.STATE_IN_PROGRESS,
        Task.STATE_DONE
      ]),
      sortOrderList: {
        updated_at_desc: '更新日 降順',
        end_date_asc: '終了日 昇順',
        start_date_asc: '開始日 昇順'
      },
      conditions: {
        keyword: '',
        statuses: [Task.STATE_NONE, Task.STATE_IN_PROGRESS],
        sortOrder: 'end_date_asc'
      }
    }
  },
  computed: {
    /**
       * 一覧の項目を選択しているかどうか
       */
    itemChecked () {
      return this.checks.length > 0
    }
  },

  mounted () {
    this.handleMounted()
  },

  methods: {
    getStatusName (status) {
      return Task.getStatusName(status)
    },
    handleTaskRegistered () {
      this.loadTaskList()
    },
    async loadTaskList () {
      const taskAdapter = adapterFactory.get('TaskAdapter')
      this.taskList = await taskAdapter.search(this.project.id, this.conditions)
    },

    openNewForm () {
      this.$refs.taskForm.open(0)
    },
    openEditForm (id) {
      this.$refs.taskForm.open(id)
    },
    handleMounted () {
      this.loadTaskList()
    },
    toggleChecks (checked) {
      if (!checked) {
        this.checks.splice(0)
        return
      }
      this.taskList.forEach((task) => {
        this.checks.push(task.id)
      })
    }
  },
  beforeRouteUpdate (to, from, next) {
    // if (to !== from) {
    this.handleMounted()
    // }
    next()
  }
}
</script>

<style lang="scss" scoped>
  .search-form {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    padding: 10px;

    .row {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      height: 50px;
      margin-right: 20px;

      .label {
        display: block;
        text-align: right;
        line-height: 1.5;
        margin-right: 10px;
        font-weight: bold;
      }

      .col {
      }
    }

    .button {
      margin-right: 20px;
    }

  }

  .action-area {
    margin-top: 20px;
    margin-bottom: 10px;
  }

  .col-checkbox {
    width: 60px;
  }

  .col-id {
    width: 60px;
  }

  .col-task-name {
  }

  .col-status {
    width: 100px;
  }

  .col-date {
    width: 120px;
  }

  .col-datetime {
    width: 200px;
  }
</style>
