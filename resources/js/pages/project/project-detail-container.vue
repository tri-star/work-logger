<template>
    <div>
        <h1 class="heading-1">{{ project.project_name }}</h1>

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
                    <table class="small-table">
                        <tr
                            v-for="(dailyCount,
                            date) in taskStat.daily_done_list"
                            :key="date"
                        >
                            <td>{{ date }}</td>
                            <td class="number-col">{{ dailyCount }}</td>
                        </tr>
                    </table>
                </template>
            </WlFrame>
        </div>
        <h2 class="heading-2">直近の状況</h2>
        <div class="clear-fix">
            <WlFrame class="frame-item" :width="'400px;'">
                <template slot="title">
                    今日のタスク
                </template>
                <template slot="body"></template>
            </WlFrame>
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
        </div>
    </div>
</template>

<script>
import adapterFactory from "../../adapters/adapter-factory"
import WlFrame from "../../components/wl-frame"

export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },
    components: {
        WlFrame
    },
    data() {
        return {
            project: {},
            taskStat: {}
        }
    },

    mounted() {
        const projectAdapter = adapterFactory.get("ProjectAdapter")
        projectAdapter.getProject(this.id).then(project => {
            this.project = project
        })

        projectAdapter.getTaskStat(this.id).then(stat => {
            this.taskStat = stat
        })
    }
}
</script>

<style lang="scss" scoped>
.frame-item {
    float: left;
}
</style>
