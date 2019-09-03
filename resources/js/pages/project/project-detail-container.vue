<template>
    <div>
        <div class="heading">
            <h1 class="heading-1 heading-item">{{ project.project_name }}</h1>
            <div class="heading-item">
                <a
                    class="command-button edit-button"
                    @click="openProjectFormHandler"
                >
                    <i class="icon fas fa-pen"></i>編集
                </a>
            </div>
        </div>

        <div class="description">{{ project.description }}</div>

        <h2 class="heading-2">統計情報</h2>
        <div class="clear-fix">
            <WlFrame class="frame-item" :width="'400px;'">
                <template slot="title">
                    タスク完了件数
                </template>
                <template slot="body">
                    <div class="stat-number-box">
                        <strong class="stat-number">
                            {{ taskStat.weekly_done_count }}
                        </strong>
                    </div>
                </template>
            </WlFrame>
            <WlFrame class="frame-item" :width="'400px;'">
                <template slot="title">
                    タスク完了件数(日別)
                </template>
                <template slot="body">
                    <WlLoadingProxy :loadingFunction="loadTaskStat">
                        <template slot="done">
                            <table class="small-table">
                                <tr
                                    v-for="(dailyCount,
                                    date) in taskStat.daily_done_list"
                                    :key="date"
                                >
                                    <td>{{ date }}</td>
                                    <td class="col-number">{{ dailyCount }}</td>
                                </tr>
                            </table>
                        </template>
                    </WlLoadingProxy>
                </template>
            </WlFrame>
        </div>
        <h2 class="heading-2">直近の状況</h2>
        <div class="clear-fix">
            <ScheduledTaskList
                class="frame-item"
                :width="'400px;'"
                :taskList="scheduledTasks"
                :loadingFunction="loadScheduledTasks"
                @addTaskLog="addTaskLogHandler"
            />
            <WlFrame class="frame-item" :width="'400px;'">
                <template slot="title">
                    期限の近いタスク
                </template>
                <template slot="body"></template>
            </WlFrame>
            <WlFrame class="frame-item" :width="'400px;'">
                <template slot="title">
                    見積差分の大きいタスク
                </template>
                <template slot="body"></template>
            </WlFrame>
            <TaskLogFormContainer ref="taskLogForm" />
            <ProjectFormContainer
                ref="projectForm"
                @projectSaved="projectSavedHandler"
            />
        </div>
    </div>
</template>

<script>
import ProjectFormContainer from "../project/project-form-container"
import ScheduledTaskList from "./scheduled-task-list"
import TaskLogFormContainer from "../tasks/task-log-form-container"
import WlFrame from "../../components/wl-frame"
import WlLoadingProxy from "../../components/wl-loading-proxy"
import adapterFactory from "../../adapters/adapter-factory"

export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },
    components: {
        ProjectFormContainer,
        ScheduledTaskList,
        TaskLogFormContainer,
        WlFrame,
        WlLoadingProxy
    },
    data() {
        return {
            project: {},
            taskStat: {},
            scheduledTasks: {}
        }
    },

    methods: {
        addTaskLogHandler(task) {
            this.$refs.taskLogForm.open(task, 0)
        },
        openProjectFormHandler() {
            this.$refs.projectForm.open(this.project.id)
        },
        handleMounted() {
            this.$emit("changeSideMenu", "project", {
                id: this.id
            })

            const projectAdapter = adapterFactory.get("ProjectAdapter")
            projectAdapter.getProject(this.id).then(project => {
                this.project = project
            })
        },
        async loadTaskStat() {
            const projectAdapter = adapterFactory.get("ProjectAdapter")
            this.taskStat = await projectAdapter.getTaskStat(this.id)
        },
        async loadScheduledTasks() {
            const projectAdapter = adapterFactory.get("ProjectAdapter")
            this.scheduledTasks = await projectAdapter.getScheduledTasks(
                this.id
            )
        },
        projectSavedHandler() {
            this.handleMounted()
        }
    },

    mounted() {
        this.handleMounted()
    },
    beforeRouteUpdate(to, from, next) {
        if (to !== from) {
            this.handleMounted()
        }
        next()
    }
}
</script>

<style lang="scss" scoped>
.heading {
    display: flex;
    align-items: center;

    .heading-item {
    }
}

.edit-button {
    margin-left: 20px;
}

.description {
    white-space: pre-line;
    margin-bottom: 20px;
}

.frame-item {
    float: left;
}
</style>
