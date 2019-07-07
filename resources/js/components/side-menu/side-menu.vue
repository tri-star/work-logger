<template>
    <aside class="side">
        <div class="menu-content">
            <div class="item icon-toggle" @click="$emit('toggle')"></div>
            <router-link
                v-for="(menuItem, index) in menuList"
                tag="div"
                :to="menuItem.to"
                :class="`item ${menuItem.icon}`"
                :key="index"
            >
                {{ menuItem.label }}
            </router-link>
        </div>
        <div class="menu-content-small">
            <div class="item icon-toggle" @click="$emit('toggle')"></div>
            <router-link
                v-for="(menuItem, index) in menuList"
                tag="div"
                :to="menuItem.to"
                :class="`item ${menuItem.icon}`"
                :key="index"
            >
            </router-link>
        </div>
    </aside>
</template>

<script>
const menues = {
    default: [
        { icon: "icon-dashboard", label: "ダッシュボード", to: "/" },
        { icon: "icon-stats", label: "統計情報", to: "" },
        { icon: "icon-config", label: "設定", to: "" }
    ],
    project: [
        { icon: "icon-dashboard", label: "ダッシュボード", to: "/" },
        { icon: "icon-tasks", label: "タスク一覧", to: "/tasks" },
        { icon: "icon-config", label: "設定", to: "" }
    ]
}

export default {
    props: {
        sideMenuType: {
            type: String,
            required: true
        }
    },
    computed: {
        menuList() {
            return menues[this.sideMenuType] ? menues[this.sideMenuType] : []
        }
    }
}
</script>

<style lang="scss" scoped>
@import "../../../sass/imports";
aside {
    position: fixed;
    top: $header-height;
    left: 0;
    z-index: 10000;
    height: 100%;

    transition: width 0.3s;
    background-color: $side-menu-background-color;

    .item {
        margin: 10px auto;
        cursor: pointer;
        padding: 5px 0 5px 35px;
        height: 30px;
        color: #fff;
        transition: padding, background-color 0.3s;
    }

    .item:hover {
        background-color: lighten($side-menu-background-color, 5%);
        background-position-y: 3px;
        box-shadow: 2px 2px 0 rgba(255, 255, 255, 0.4);
        padding: 3px 0 5px 35px;
        transition: padding, background-color 0.3s;
    }

    .icon-toggle {
        display: block;
        background: url("/images/menu-toggle.png") no-repeat 5px 5px;
    }
    .icon-dashboard {
        display: block;
        background: url("/images/menu-dashboard.png") no-repeat 5px 5px;
    }
    .icon-stats {
        display: block;
        background: url("/images/menu-stats.png") no-repeat 5px 5px;
    }
    .icon-tasks {
        display: block;
        background: url("/images/menu-tasks.png") no-repeat 5px 5px;
    }
    .icon-config {
        display: block;
        background: url("/images/menu-config.png") no-repeat 5px 5px;
    }
}
</style>
