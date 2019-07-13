<template>
    <aside class="side">
        <div class="menu-content">
            <div class="item fas fa-bars" @click="$emit('toggle')"></div>
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
            <div class="item fas fa-bars" @click="$emit('toggle')"></div>
            <router-link
                v-for="(menuItem, index) in menuList"
                tag="div"
                :to="menuItem.to"
                :class="`item ${menuItem.icon}`"
                :key="index"
                >&nbsp;
            </router-link>
        </div>
    </aside>
</template>

<script>
const menues = {
    default: [
        { icon: "fas fa-chart-pie", label: "統計情報", to: "" },
        { icon: "fas fa-cog", label: "設定", to: "" }
    ],
    project: [
        { icon: "fas fa-chart-line", label: "ダッシュボード", to: "/" },
        {
            icon: "fas fa-tasks",
            label: "タスク一覧",
            to: "/project/:id/tasks"
        },
        { icon: "fas fa-cog", label: "設定", to: "" }
    ]
}

export default {
    props: {
        sideMenuType: {
            type: String,
            required: true
        },
        sideMenuParams: {
            type: Object,
            default: () => {
                return {}
            }
        }
    },
    computed: {
        menuList() {
            if (!menues[this.sideMenuType]) {
                return []
            }
            const convertedMenues = menues[this.sideMenuType].map(item => {
                Object.keys(this.sideMenuParams).forEach(key => {
                    item.to = item.to.replace(
                        `:${key}`,
                        this.sideMenuParams[key]
                    )
                })
                return item
            })
            return convertedMenues
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
        margin: 3px auto;
        cursor: pointer;
        padding: 5px 0 5px 10px;
        width: 90%;
        height: 30px;
        color: #fff;
        font-size: 1.2rem;
        transition: padding, background-color 0.3s;
    }

    .item:hover {
        background-color: lighten($side-menu-background-color, 5%);
        background-position-y: 3px;
        box-shadow: 2px 2px 0 rgba(255, 255, 255, 0.4);
        padding: 3px 0 5px 10px;
        transition: padding, background-color 0.3s;
    }
}
</style>
