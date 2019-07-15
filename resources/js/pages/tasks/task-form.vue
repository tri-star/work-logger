<template>
    <div>
        <h1 class="heading-1">{{ formTitle }}</h1>

        <div class="form">
            <div class="row">
                <div class="col-header">タスク名</div>
                <div class="col">
                    <input
                        type="text"
                        v-model="task.title"
                        class="text-box task-name"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-header">開始予定日</div>
                <div class="col">
                    <input
                        type="text"
                        v-model="task.start_date"
                        class="text-box"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-header">完了予定日</div>
                <div class="col">
                    <input
                        type="text"
                        v-model="task.end_date"
                        class="text-box"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-header">予定工数</div>
                <div class="col">
                    <input
                        type="number"
                        v-model="task.estimate_minutes"
                        class="text-box estimate-time"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-header">ステータス</div>
                <div class="col">
                    <select v-model="task.status">
                        <option
                            v-for="(statusName, value) of statuses"
                            :value="value"
                            :key="value"
                            >{{ statusName }}</option
                        >
                    </select>
                </div>
            </div>
            <div class="col-center button-area">
                <button class="button" @click="$emit('save', task)">
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

export default {
    props: {},

    components: {},
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
        }
    },

    methods: {
        init(id, task) {
            this.id = id
            this.task = Object.assign({}, this.task, task)
        }
    }
}
</script>

<style lang="scss" scoped>
.task-name {
    width: 300px;
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
</style>
