<template>
    <div>
        <h1 class="heading-1">{{ project.project_name }}</h1>

        <h2 class="heading-2">統計情報</h2>
        <div class="clear-fix">
            <WlFrame class="frame-item" :width="'400px;'">
                <template slot="title">
                    タスク完了件数
                </template>
                <template slot="body"></template>
            </WlFrame>
            <WlFrame class="frame-item" :width="'400px;'">
                <template slot="title">
                    タスク完了件数(日別)
                </template>
                <template slot="body"></template>
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
            project: {}
        }
    },

    mounted() {
        const projectAdapter = adapterFactory.get("ProjectAdapter")
        projectAdapter.getProject(this.id).then(project => {
            this.project = project
            console.log(this.project)
        })
    }
}
</script>

<style lang="scss" scoped>
.frame-item {
    float: left;
}
</style>
