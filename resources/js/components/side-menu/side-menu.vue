<template>
    <aside class="side">
        <div class="menu-content">
            <div class="item icon-toggle" @click="$emit('toggle')"></div>
            <router-link tag="div" to="/" class="item icon-dashboard">
                ダッシュボード
            </router-link>
            <div class="item icon-stats">統計情報</div>
            <div class="item icon-tasks">タスク一覧</div>
            <div class="item icon-config">設定</div>
        </div>
        <div class="menu-content-small">
            <div class="item icon-toggle" @click="$emit('toggle')"></div>
            <router-link
                tag="div"
                to="/"
                class="item icon-dashboard"
            ></router-link>
            <div class="item icon-stats"></div>
            <div class="item icon-tasks"></div>
            <div class="item icon-config"></div>
        </div>
    </aside>
</template>

<script>
export default {}
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
