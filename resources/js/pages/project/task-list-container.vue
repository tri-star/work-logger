<template>
    <div>
        <h1 class="heading-1">{{ project.project_name }}: タスク一覧</h1>

        <section></section>

        <section>
            <section class="action-area">
                <a class="command-button">
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
                    <td class="col-number">{{ task.id }}</td>
                    <td class="col-task-name">{{ task.title }}</td>
                    <td></td>
                    <td>{{ getStatusName(task.status) }}</td>
                    <td>{{ task.start_date }}</td>
                    <td>{{ task.end_date }}</td>
                    <td>{{ task.updated_at }}</td>
                </tr>
            </table>
        </section>
    </div>
</template>

<script>
import adapterFactory from "../../adapters/adapter-factory"
import Task from "../../domain/task.js"

export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },
    components: {},
    data() {
        return {
            project: {},
            taskList: {}
        }
    },

    methods: {
        getStatusName(status) {
            return Task.getStatusName(status)
        }
    },

    mounted() {
        this.$emit("changeSideMenu", "project", {
            id: this.id
        })

        const projectAdapter = adapterFactory.get("ProjectAdapter")
        projectAdapter.getProject(this.id).then(project => {
            this.project = project
        })

        projectAdapter.searchTaskList(this.id).then(result => {
            this.taskList = result
        })
    }
}
</script>

<style lang="scss" scoped>
.action-area {
    margin-bottom: 10px;
}

.col-task-name {
    width: 300px;
}
</style>
