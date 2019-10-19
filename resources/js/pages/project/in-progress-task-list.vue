<template>
    <WlFrame>
        <template slot="title">
            作業中のタスク
        </template>
        <template slot="body">
            <WlLoadingProxy :loadingFunction="loadingFunction">
                <template slot="done">
                    <table class="small-table" v-if="taskList">
                        <tr v-for="(task, id) of taskList" :key="id">
                            <td>
                                <router-link :to="`/task/${task.id}`">
                                    {{ task.title }}
                                </router-link>
                            </td>
                            <td class="col-number">
                                {{ task.estimate_minutes }}h
                            </td>
                            <td class="col-icons">
                                <a
                                    @click="$emit('addTaskLog', task)"
                                    class="add-log-button"
                                >
                                    <i class="icon fas fa-plus-circle"></i>
                                </a>
                            </td>
                        </tr>
                    </table>
                </template>
            </WlLoadingProxy>
        </template>
    </WlFrame>
</template>

<script>
import WlFrame from "../../components/wl-frame"
import WlLoadingProxy from "../../components/wl-loading-proxy"

export default {
    props: {
        taskList: {
            type: Object,
            required: true
        },
        loadingFunction: {
            type: Function,
            required: true
        }
    },
    components: {
        WlFrame,
        WlLoadingProxy
    },
    data() {
        return {}
    }
}
</script>

<style lang="scss" scoped>
.add-log-button {
    cursor: pointer;
}
</style>
