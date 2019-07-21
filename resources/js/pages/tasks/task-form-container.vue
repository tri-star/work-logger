<template>
    <WlModal :showModal="showModal">
        <template v-slot:body>
            <TaskForm ref="taskForm" @save="handleSave" @close="handleClose" />
        </template>
    </WlModal>
</template>

<script>
import AdapterFactory from "../../adapters/adapter-factory"
import Task from "../../domain/task"
import TaskForm from "./task-form"
import WlModal from "../../components/wl-modal"

export default {
    props: {
        project: {
            type: Object,
            required: true
        }
    },

    components: {
        WlModal,
        TaskForm
    },
    data() {
        return {
            id: 0,
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
        async open(id) {
            this.id = id
            this.showModal = true
            if (this.id === 0) {
                this.task = {
                    title: "",
                    start_date: "",
                    end_date: "",
                    estimate_minutes: 0,
                    status: 0
                }
            } else {
                this.task = await this.loadTask(this.id)
            }
            this.$nextTick(() => {
                this.$refs.taskForm.init(this.id, this.task)
            })
        },
        handleSave(task) {
            if (this.id === 0) {
                this.addTask(task)
            } else {
                this.updateTask(task)
            }
        },
        handleClose() {
            this.showModal = false
        },
        addTask(task) {
            const adapter = AdapterFactory.get("TaskAdapter")
            adapter
                .addTask(this.project.id, task)
                .then(() => {
                    this.$emit("taskRegistered")
                    this.showModal = false
                })
                .catch(error => {
                    console.error(error)
                    alert(error)
                })
        },
        updateTask(task) {
            const adapter = AdapterFactory.get("TaskAdapter")
            adapter
                .updateTask(this.id, task)
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

<style lang="scss" scoped></style>
