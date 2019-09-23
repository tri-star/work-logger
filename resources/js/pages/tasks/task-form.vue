<template>
    <div>
        <h1 class="heading-1">{{ formTitle }}</h1>

        <div class="form">
            <div class="row">
                <div class="col-header">タスク名</div>
                <div class="col">
                    <input
                        type="text"
                        name="title"
                        v-model="task.title"
                        data-vv-as="タスク名"
                        v-validate="'required'"
                        class="text-box task-name"
                    />
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
                <div class="col-header">課題番号</div>
                <div class="col">
                    <input
                        type="text"
                        name="issue_no"
                        v-model="task.issue_no"
                        data-vv-as="課題番号"
                        class="text-box"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-header">詳細</div>
                <div class="col">
                    <textarea
                        type="text"
                        name="description"
                        v-model="task.description"
                        data-vv-as="詳細"
                        v-validate="'required'"
                        class="text-box task-description"
                    ></textarea>
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
                <div class="col-header">開始予定日</div>
                <div class="col">
                    <WlDate
                        name="start_date"
                        v-model="task.start_date"
                        data-vv-as="開始予定日"
                        v-validate="'date_format:yyyy-MM-dd'"
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
                <div class="col-header">完了予定日</div>
                <div class="col">
                    <WlDate
                        name="end_date"
                        v-model="task.end_date"
                        data-vv-as="完了予定日"
                        v-validate="'date_format:yyyy-MM-dd'"
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
                <div class="col-header">予定工数</div>
                <div class="col">
                    <input
                        type="number"
                        name="estimate_minutes"
                        v-model="task.estimate_minutes"
                        data-vv-as="予定工数"
                        v-validate="'required|min_value:0.1'"
                        class="text-box estimate-time"
                    />
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
                <div class="col-header">ステータス</div>
                <div class="col">
                    <WlDropDown
                        name="status"
                        :menues="statuses"
                        v-model="task.status"
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
import WlDate from "../../components/form/wl-date"
import WlDropDown from "../../components/form/wl-dropdown"

export default {
    props: {},

    components: {
        WlDate,
        WlDropDown
    },
    data() {
        return {
            id: 0,
            task: {},
            statuses: Task.getStatusNames()
        }
    },

    computed: {
        formTitle() {
            return this.id === 0 ? "タスク登録" : "タスク更新"
        },
        buttonTitle() {
            return this.id === 0 ? "登録" : "更新"
        },
        canSubmit() {
            return this.errors.all().length === 0
        }
    },

    methods: {
        init(id, task) {
            this.id = id
            this.task = Object.assign({}, this.task, task)
        },
        submit() {
            this.$validator.validate().then(result => {
                if (!result) {
                    return
                }
                this.$emit("save", this.task)
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
