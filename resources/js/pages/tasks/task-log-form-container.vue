<template>
    <WlModal :showModal="showModal">
        <template v-slot:body>
            <TaskLogForm
                ref="taskLogForm"
                @save="handleSave"
                @close="handleClose"
            />
        </template>
    </WlModal>
</template>

<script>
import WlModal from "../../components/wl-modal"
import Task from "../../domain/task"
import TaskLogForm from "./task-log-form"
import AdapterFactory from "../../adapters/adapter-factory"

export default {
    props: {},

    components: {
        WlModal,
        TaskLogForm
    },
    data() {
        return {
            id: 0,
            showModal: false,
            statuses: Task.getStatusNames(),
            task: {},
            taskLog: {}
        }
    },

    methods: {
        async open(task, id) {
            this.id = id
            this.showModal = true
            this.task = Object.assign({}, this.task, task)
            this.taskLog = {
                tag: "",
                hours: 0,
                memo: "",
                status: this.task.status
            }
            this.$nextTick(() => {
                this.$refs.taskLogForm.init(this.id, this.task, this.taskLog)
            })
        },
        handleSave(taskLog) {
            this.addTaskLog(taskLog)
        },
        handleClose() {
            this.showModal = false
        },
        addTaskLog(taskLog) {
            const adapter = AdapterFactory.get("TaskLogAdapter")
            adapter
                .addTaskLog(this.task.id, taskLog)
                .then(() => {
                    this.$emit("taskLogRegistered")
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
