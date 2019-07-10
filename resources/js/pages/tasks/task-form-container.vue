<template>
    <WlModal :showModal="showModal">
        <template v-slot:body>
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
                    <button class="button" @click="save">
                        {{ submitButtonTitle }}
                    </button>
                    <button class="button" @click="showModal = false">
                        キャンセル
                    </button>
                </div>
            </div>
        </template>
    </WlModal>
</template>

<script>
import WlModal from "../../components/wl-modal"
import Task from "../../domain/task"
import AdapterFactory from "../../adapters/adapter-factory"

export default {
    props: {
        project: {
            type: Object,
            required: true
        }
    },

    components: {
        WlModal
    },
    data() {
        return {
            id: 0,
            formTitle: "",
            submitButtonTitle: "",
            showModal: false,
            statuses: Task.getStatusNames(),
            task: {}
        }
    },

    methods: {
        async loadTask(id) {
            const adapter = AdapterFactory.get("TaskAdapter")
            return await adapter.getTask(id)
        },
        open(id) {
            this.id = id
            this.showModal = true
            this.formTitle = "タスク登録"
            this.submitButtonTitle = "登録"
            if (this.id === 0) {
                this.task = {
                    title: "",
                    start_date: "",
                    end_date: "",
                    estimate_minutes: 0,
                    status: 0
                }
            } else {
                this.formTitle = "タスク更新"
                this.submitButtonTitle = "更新"
                this.loadTask(this.id)
                    .then(task => {
                        this.task = task
                    })
                    .catch(error => {
                        console.error(error)
                        alert(error)
                    })
            }
        },
        save() {
            const adapter = AdapterFactory.get("TaskAdapter")
            adapter
                .addTask(this.project.id, this.task)
                .then(() => {
                    this.$emit("taskRegistered")
                    this.showModal = false
                })
                .catch(error => {
                    console.error(error)
                    alert(error)
                })
        }
    },

    mounted() {}
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
