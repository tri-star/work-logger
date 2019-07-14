<template>
    <WlModal :showModal="showModal">
        <template v-slot:body>
            <TaskForm
                :id="id"
                :task="task"
                @save="handleSave"
                @close="handleClose"
            />
        </template>
    </WlModal>
</template>

<script>
import WlModal from "../../components/wl-modal"
import Task from "../../domain/task"
import TaskForm from "./task-form"
import AdapterFactory from "../../adapters/adapter-factory"

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
        open(id) {
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
        handleSave() {
            if (this.id === 0) {
                this.addTask()
            } else {
                this.updateTask()
            }
        },
        handleClose() {
            this.showModal = false
        },
        addTask() {
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
        },
        updateTask() {
            const adapter = AdapterFactory.get("TaskAdapter")
            adapter
                .updateTask(this.id, this.task)
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
