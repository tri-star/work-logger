<template>
  <div>
    <h1 class="heading-1">
      {{ formTitle }}
    </h1>

    <div class="form">
      <div class="row">
        <div class="col-header">
          タスク名
        </div>
        <div class="col">
          <input
            v-model="task.title"
            v-validate="'required'"
            type="text"
            name="title"
            data-vv-as="タスク名"
            class="text-box task-name"
          >
          <ul class="errors">
            <li
              v-for="(error, index) in errors.collect('title')"
              :key="index"
            >
              {{ error }}
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-header">
          課題番号
        </div>
        <div class="col">
          <input
            v-model="task.issue_no"
            type="text"
            name="issue_no"
            data-vv-as="課題番号"
            class="text-box"
          >
        </div>
      </div>
      <div class="row">
        <div class="col-header">
          詳細
        </div>
        <div class="col">
          <textarea
            v-model="task.description"
            v-validate="'required'"
            type="text"
            name="description"
            data-vv-as="詳細"
            class="text-box task-description"
          />
          <ul class="errors">
            <li
              v-for="(error, index) in errors.collect(
                'description'
              )"
              :key="index"
            >
              {{ error }}
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-header">
          開始予定日
        </div>
        <div class="col">
          <WlDate
            v-model="start_date"
            v-validate="'date_format:yyyy-MM-dd'"
            name="start_date"
            data-vv-as="開始予定日"
            class="text-box"
          />
          <ul class="errors">
            <li
              v-for="(error, index) in errors.collect(
                'start_date'
              )"
              :key="index"
            >
              {{ error }}
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-header">
          完了予定日
        </div>
        <div class="col">
          <WlDate
            v-model="end_date"
            v-validate="'date_format:yyyy-MM-dd'"
            name="end_date"
            data-vv-as="完了予定日"
            class="text-box"
          />
          <ul class="errors">
            <li
              v-for="(error, index) in errors.collect('end_date')"
              :key="index"
            >
              {{ error }}
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-header">
          予定工数
        </div>
        <div class="col">
          <input
            v-model="task.estimate_minutes"
            v-validate="'required|min_value:0.1'"
            type="number"
            name="estimate_minutes"
            data-vv-as="予定工数"
            class="text-box estimate-time"
          >
          <ul class="errors">
            <li
              v-for="(error, index) in errors.collect(
                'estimate_minutes'
              )"
              :key="index"
            >
              {{ error }}
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-header">
          ステータス
        </div>
        <div class="col">
          <WlDropDown
            v-model="task.status"
            name="status"
            :menues="statuses"
          />
        </div>
      </div>
      <div class="col-center button-area">
        <button class="button" :disabled="!canSubmit" @click="submit">
          {{ buttonTitle }}
        </button>
        <button class="button" @click="$emit('close')">
          キャンセル
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Task from '../../domain/task'
import WlDate from '../../components/form/wl-date'
import WlDropDown from '../../components/form/wl-dropdown'
import dayjs from 'dayjs'

export default {

  components: {
    WlDate,
    WlDropDown
  },
  props: {},
  data () {
    return {
      id: 0,
      task: {},
      statuses: Task.getStatusNames()
    }
  },

  computed: {
    formTitle () {
      return this.id === 0 ? 'タスク登録' : 'タスク更新'
    },
    buttonTitle () {
      return this.id === 0 ? '登録' : '更新'
    },
    canSubmit () {
      return this.errors.all().length === 0
    },
    start_date: {
      get () {
        return dayjs(this.task.start_date).isValid()
          ? dayjs(this.task.start_date).format('YYYY-MM-DD')
          : ''
      },
      set (value) {
        this.task.start_date = value
      }
    },
    end_date: {
      get () {
        return dayjs(this.task.end_date).isValid()
          ? dayjs(this.task.end_date).format('YYYY-MM-DD')
          : ''
      },
      set (value) {
        this.task.end_date = value
      }
    }
  },

  methods: {
    init (id, task) {
      this.id = id
      this.task = Object.assign({}, this.task, task)
    },
    submit () {
      this.$validator.validate().then((result) => {
        if (!result) {
          return
        }
        this.$emit('save', this.task)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .task-name {
    width: 300px;
  }

  .task-description {
    width: 300px;
    height: 100px;
  }

  .estimate-time {
    width: 60px;
  }

  .form {
    width: 600px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;

    .row {
      display: flex;
      justify-content: flex-start;
      align-content: center;
      padding: 10px;

      .col-header {
        width: 120px;
      }

      .col {
        width: 400px;
      }

      .col-center {
        width: 100px;
      }
    }

    .button {
      margin-right: 20px;
    }

    .button-area {
      margin-top: 10px;
    }
  }

  .errors {
    margin-top: 5px;
    background-color: rgba(255, 100, 100, 0.2);
    list-style: none;
    padding: 0;

    li {
      padding: 5px 10px;
      color: #e00;
    }
  }
</style>
