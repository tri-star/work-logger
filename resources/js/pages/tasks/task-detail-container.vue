<template>
    <div>
        <TaskDetail
            :task="task"
            :project="project"
            :taskLogs="taskLogs"
            @openEditForm="handleOpenEditForm"
        />
        <TaskFormContainer
            ref="editForm"
            :project="project"
            @taskSaved="handleTaskSaved"
        />
    </div>
</template>

<script>
import AdapterFactory from "../../adapters/adapter-factory"
import Task from "../../domain/task"
import TaskDetail from "./task-detail"
import TaskFormContainer from "./task-form-container"

export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },

    components: {
        TaskDetail,
        TaskFormContainer
    },
    data() {
        return {
            statuses: Task.getStatusNames(),
            task: {},
            project: {},
            taskLogs: []
        }
    },

    methods: {
        loadTask(id) {
            const adapter = AdapterFactory.get("TaskAdapter")
            return adapter.getTask(id, {
                with_task_logs: true
            })
        },
        loadProject(id) {
            const adapter = AdapterFactory.get("ProjectAdapter")
            return adapter.getProject(id)
        },
        handleOpenEditForm() {
            this.$refs.editForm.open(this.id)
        },
        handleTaskSaved() {
            this.loadTask(this.id).then(task => {
                console.log(this.task)
                this.task = Object.assign({}, this.task, task)
            })
        }
    },

    async mounted() {
        this.task = await this.loadTask(this.id)
        this.taskLogs = this.task.logs
        this.project = await this.loadProject(this.task.project_id)

        this.$emit("changeSideMenu", "project", {
            id: this.project.id
        })
    }
}
</script>

<style lang="scss" scoped></style>
