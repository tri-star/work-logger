<template>
    <div>
        <h1 class="heading-1">{{ formTitle }}</h1>

        <div class="form">
            <div class="row">
                <div class="col-header">タスク名</div>
                <div class="col">
                    {{ task.title }}
                </div>
            </div>
            <div class="row">
                <div class="col-header">作業時間</div>
                <div class="col">
                    <input
                        type="number"
                        name="hours"
                        v-model="taskLog.hours"
                        data-vv-as="作業時間"
                        v-validate="'required|decimal:1|min_value:0.1'"
                        class="text-box hours"
                    />h
                    <ul class="errors">
                        <li
                            v-for="(error, index) in errors.collect('hours')"
                            :key="index"
                        >
                            {{ error }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-header">メモ</div>
                <div class="col">
                    <textarea
                        name="memo"
                        v-model="taskLog.memo"
                        class="text-box memo"
                    >
                    </textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-header">ステータス</div>
                <div class="col">
                    <WlDropDown
                        name="status"
                        :menues="statuses"
                        v-model="taskLog.status"
                    />
                </div>
            </div>
            <div class="col-center button-area">
                <button class="button" @click="submit" :disabled="!canSubmit">
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
import Task from "../../domain/task"
import WlDropDown from "../../components/form/wl-dropdown"

export default {
    props: {},

    components: {
        WlDropDown
    },
    data() {
        return {
            id: 0,
            task: {},
            taskLog: {},
            statuses: Task.getStatusNames()
        }
    },

    computed: {
        formTitle() {
            return this.id === 0 ? "作業履歴登録" : "作業履歴更新"
        },
        buttonTitle() {
            return this.id === 0 ? "登録" : "更新"
        },
        canSubmit() {
            return this.errors.all().length === 0
        }
    },

    methods: {
        init(id, task, taskLog) {
            this.id = id
            this.task = Object.assign({}, this.task, task)
            this.taskLog = Object.assign({}, this.taskLog, taskLog)
        },
        submit() {
            this.$validator.validate().then(result => {
                if (!result) {
                    return
                }
                this.$emit("save", this.taskLog)
            })
        }
    }
}
</script>

<style lang="scss" scoped>
.hours {
    width: 60px;
    text-align: right;
    margin-right: 5px;
}

.memo {
    width: 300px;
    height: 100px;
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
