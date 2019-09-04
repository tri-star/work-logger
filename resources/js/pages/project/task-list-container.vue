<template>
    <div>
        <h1 class="heading-1">{{ project.project_name }}: タスク一覧</h1>

        <section></section>

        <section>
            <section class="action-area">
                <a class="command-button" @click="openNewForm">
                    <i class="icon fas fa-plus-circle"></i>新規登録
                </a>
            </section>

            <table class="list-table">
                <tr>
                    <th class="col-checkbox"><input type="checkbox" /></th>
                    <th>ID</th>
                    <th class="col-task-name">タスク名</th>
                    <th>タグ</th>
                    <th>ステータス</th>
                    <th>開始日</th>
                    <th>終了日</th>
                    <th>更新日</th>
                </tr>
                <tr v-for="task in taskList" :key="task.id">
                    <td class="col-checkbox"><input type="checkbox" /></td>
                    <td class="col-number col-id">{{ task.id }}</td>
                    <td class="col-task-name">
                        <router-link :to="`/task/${task.id}`">{{
                            task.title
                        }}</router-link>
                    </td>
                    <td></td>
                    <td class="col-status">{{ getStatusName(task.status) }}</td>
                    <td class="col-datetime">{{ task.start_date }}</td>
                    <td class="col-datetime">{{ task.end_date }}</td>
                    <td class="col-datetime">{{ task.updated_at }}</td>
                </tr>
            </table>
        </section>
        <TaskFormContainer
            ref="taskForm"
            :project="project"
            @taskRegistered="handleTaskRegistered"
        />
    </div>
</template>

<script>
import Task from "../../domain/task.js"
import TaskFormContainer from "../tasks/task-form-container"
import adapterFactory from "../../adapters/adapter-factory"

export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },
    components: {
        TaskFormContainer
    },
    data() {
        return {
            project: {},
            taskList: {}
        }
    },

    methods: {
        getStatusName(status) {
            return Task.getStatusName(status)
        },
        handleTaskRegistered() {
            this.loadTaskList()
        },
        loadTaskList() {
            const taskAdapter = adapterFactory.get("TaskAdapter")
            taskAdapter.search(this.id).then(result => {
                this.taskList = result
            })
        },

        openNewForm() {
            this.$refs.taskForm.open(0)
        },
        openEditForm(id) {
            this.$refs.taskForm.open(id)
        },
        handleMounted() {
            this.$emit("changeSideMenu", "project", {
                id: this.id
            })

            const projectAdapter = adapterFactory.get("ProjectAdapter")
            projectAdapter.getProject(this.id).then(project => {
                this.project = project
            })

            this.loadTaskList()
        }
    },

    mounted() {
        this.handleMounted()
    },
    beforeRouteUpdate(to, from, next) {
        // if (to !== from) {
        this.handleMounted()
        // }
        next()
    }
}
</script>

<style lang="scss" scoped>
.action-area {
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

.col-datetime {
    width: 200px;
}
</style>
