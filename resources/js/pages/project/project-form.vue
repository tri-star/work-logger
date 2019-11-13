<template>
  <div>
    <h1 class="heading-1">
      {{ formTitle }}
    </h1>

    <div class="form">
      <div class="row">
        <div class="col-header">
          プロジェクト名
        </div>
        <div class="col">
          <input
            v-model="project.project_name"
            v-validate="'required'"
            type="text"
            name="project_name"
            data-vv-as="プロジェクト名"
            class="text-box project-name"
          >
          <ul class="errors">
            <li
              v-for="(error, index) in errors.collect(
                'project_name'
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
          概要
        </div>
        <div class="col">
          <textarea
            v-model="project.description"
            v-validate="'required'"
            name="description"
            data-vv-as="概要"
            class="text-area description"
          />
          <ul class="errors">
            <li
              v-for="(error, index) in errors.collect(
                'project_name'
              )"
              :key="index"
            >
              {{ error }}
            </li>
          </ul>
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
export default {
  components: {},
  props: {},
  data () {
    return {
      id: 0,
      project: {}
    }
  },
  computed: {
    formTitle () {
      return this.id ? 'プロジェクト編集' : 'プロジェクト登録'
    },
    buttonTitle () {
      return this.id ? '更新' : '登録'
    },
    canSubmit () {
      return this.errors.all().length === 0
    }
  },
  methods: {
    init (id, project) {
      this.id = id
      this.project = project
    },
    submit () {
      this.$validator.validate().then((result) => {
        if (!result) {
          return
        }
        this.$emit('save', this.project)
      })
    }
  }
}
</script>

<style lang="scss" scoped>
  .description {
    width: 100%;
    height: 200px;
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
