<template>
    <div>
        <h1 class="heading-1">{{ project.project_name }}: タスク一覧</h1>

        <h2 class="heading-2">検索条件</h2>
        <section class="search-form" @keyup.enter="loadTaskList">
            <div class="row">
                <label class="label">キーワード</label>
                <div class="col">
                    <input
                        type="text"
                        class="text-box"
                        v-model="conditions.keyword"
                    />
                </div>
            </div>
            <div class="row">
                <label class="label">状態</label>
                <div class="col">
                    <span
                        v-for="(statusName, statusCode) in statusList"
                        :for="`status-${statusCode}`"
                        :key="statusCode"
                    >
                        <WlCheckBox
                            :id="`search-status-${statusCode}`"
                            :value="statusCode"
                            :label="statusName"
                            v-model="conditions.statuses"
                        />
                    </span>
                </div>
            </div>
            <div class="row">
                <label class="label">ソート順</label>
                <div class="col">
                    <WlDropDown
                        name="sort-order"
                        :menues="sortOrderList"
                        v-model="conditions.sortOrder"
                    />
                </div>
            </div>
        </section>
        <section class="action-area">
            <a class="command-button" @click="loadTaskList">
                <i class="icon fas fa-search"></i>検索
            </a>
        </section>

        <h2 class="heading-2">一覧</h2>
        <section>
            <section class="action-area">
                <a class="command-button" @click="openNewForm">
                    <i class="icon fas fa-plus-circle"></i>新規登録
                </a>
                <BulkActionButton
                    title="一括操作"
                    :menuList="bulkActionMenuList"
                />
            </section>

            <WlLoadingProxy :loadingFunction="loadTaskList">
                <template slot="done">
                    <table class="list-table">
                        <tr>
                            <th class="col-checkbox">
                                <input
                                    type="checkbox"
                                    @change="
                                        toggleChecks($event.target.checked)
                                    "
                                />
                            </th>
                            <th>ID</th>
                            <th class="col-task-name">タスク名</th>
                            <th>ステータス</th>
                            <th>開始日</th>
                            <th>終了日</th>
                            <th>更新日</th>
                        </tr>
                        <tr v-for="task in taskList" :key="task.id">
                            <td class="col-checkbox">
                                <input
                                    type="checkbox"
                                    :value="task.id"
                                    v-model="checks"
                                />
                            </td>
                            <td class="col-number col-id">{{ task.id }}</td>
                            <td class="col-task-name">
                                <router-link :to="`/task/${task.id}`">{{
                                    task.title
                                }}</router-link>
                            </td>
                            <td class="col-status">
                                {{ getStatusName(task.status) }}
                            </td>
                            <td class="col-datetime">{{ task.start_date }}</td>
                            <td class="col-datetime">{{ task.end_date }}</td>
                            <td class="col-datetime">{{ task.updated_at }}</td>
                        </tr>
                    </table>
                </template>
            </WlLoadingProxy>
        </section>
        <TaskFormContainer
            ref="taskForm"
            :project="project"
            @taskRegistered="handleTaskRegistered"
        />
        <BulkDateUpdateFormContainer ref="bulkDateUpdateFormContainer" />
    </div>
</template>

<script>
import BulkActionButton from "../../components/bulk-action-button"
import BulkDateUpdateFormContainer from "./bulk-date-update-form-container"
import Task from "../../domain/task.js"
import TaskFormContainer from "../tasks/task-form-container"
import WlCheckBox from "../../components/form/wl-checkbox"
import WlDropDown from "../../components/form/wl-dropdown"
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
        BulkActionButton,
        BulkDateUpdateFormContainer,
        TaskFormContainer,
        WlCheckBox,
        WlDropDown,
        WlLoadingProxy
    },
    data() {
        return {
            checks: [],
            project: {},
            taskList: {},
            bulkActionMenuList: [
                { title: "状態の変更", handler: () => {} },
                {
                    title: "開始日・終了日の変更",
                    handler: () => {
                        this.$refs.bulkDateUpdateFormContainer.open(this.checks)
                    }
                }
            ],
            statusList: window._.pick(Task.getStatusNames(), [
                Task.STATE_NONE,
                Task.STATE_IN_PROGRESS,
                Task.STATE_DONE
            ]),
            sortOrderList: {
                updated_at_desc: "更新日 降順",
                end_date_asc: "終了日 昇順",
                start_date_asc: "開始日 昇順"
            },
            conditions: {
                keyword: "",
                statuses: [Task.STATE_NONE, Task.STATE_IN_PROGRESS],
                sortOrder: "end_date_asc"
            }
        }
    },

    methods: {
        getStatusName(status) {
            return Task.getStatusName(status)
        },
        handleTaskRegistered() {
            this.loadTaskList()
        },
        async loadTaskList() {
            const taskAdapter = adapterFactory.get("TaskAdapter")
            this.taskList = await taskAdapter.search(this.id, this.conditions)
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
        },
        toggleChecks(checked) {
            if (!checked) {
                this.checks.splice(0)
                return
            }
            this.taskList.forEach(task => {
                this.checks.push(task.id)
            })
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
.search-form {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 10px;

    .row {
        display: flex;
        justify-content: flex-start;
        align-content: center;
        padding: 10px;

        .label {
            width: 120px;
        }
        .col {
        }
    }
    .button {
        margin-right: 20px;
    }

    .button-area {
        margin-top: 10px;
    }
}

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
