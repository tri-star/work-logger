<template>
    <div id="#dashboard-page">
        <h1 class="heading-1">DASHBOARD</h1>

        <h2 class="heading-2">統計情報</h2>
        <div class="frame-list clear-fix">
            <WlFrame class="frame-item">
                <template slot="title">
                    プロジェクト別課題件数
                </template>
                <template slot="body">
                    body
                </template>
            </WlFrame>
            <WlFrame class="frame-item">
                <template slot="title">
                    タスク完了件数
                </template>
                <template slot="body">
                    <div class="stat-number-box">
                        <strong class="stat-number">10</strong>
                    </div>
                </template>
            </WlFrame>
        </div>

        <h2 class="heading-2">直近の状況</h2>
        <div class="frame-list clear-fix">
            <WlFrame class="frame-item" :width="'30%'">
                <template slot="title">
                    プロジェクト一覧
                </template>
                <template slot="body">
                    <ProjectList :projects="projects" />
                </template>
            </WlFrame>
            <WlFrame class="frame-item" :width="'30%'">
                <template slot="title">
                    期限の近いタスク一覧
                </template>
                <template slot="body"> </template>
            </WlFrame>
            <WlFrame class="frame-item" :width="'30%'">
                <template slot="title">
                    見積差分の大きいタスク一覧
                </template>
                <template slot="body"> </template>
            </WlFrame>
        </div>
    </div>
</template>

<script>
import WlFrame from "../../components/wl-frame"
import ProjectList from "./project-list"
import adapterFactory from "../../adapters/adapter-factory"

export default {
    data() {
        return {
            projects: []
        }
    },
    components: {
        WlFrame,
        ProjectList
    },

    mounted() {
        const dashboardAdapter = adapterFactory.get("DashboardAdapter")
        dashboardAdapter.getProjectList().then(projects => {
            this.projects = projects
        })
    }
}
</script>

<style lang="scss" sceped>
#dashboard-page {
    width: 100%;
}

.stat-number-box {
    display: table;
    width: 100%;
    height: 100%;
}

.stat-number {
    display: table-cell;
    text-align: center;
    vertical-align: middle;

    font-size: 40pt;
    font-weight: bold;
    text-shadow: 2px 2px rgba(0, 0, 0, 0.3);
}

.frame-item {
    float: left;
}

.project-list {
    td {
        width: 100%;
    }
}
</style>
