<template>
    <div class="container">
        <DefaultHeader />
        <div :class="{ 'side-wrapper': 1, 'side-hide': hideMenu }">
            <SideMenu
                @toggle="handleToggle"
                :sideMenuType="sideMenuType"
                :sideMenuParams="sideMenuParams"
            />
            <main class="content">
                <router-view @changeSideMenu="handleChangeSideMenu" />
            </main>
        </div>
    </div>
</template>

<script>
import DefaultHeader from "../components/header/default-header"
import SideMenu from "../components/side-menu/side-menu"

export default {
    data() {
        return {
            hideMenu: true,
            sideMenuType: "default",
            sideMenuParams: {}
        }
    },
    components: {
        DefaultHeader,
        SideMenu
    },
    methods: {
        handleToggle() {
            this.hideMenu = !this.hideMenu
        },
        handleChangeSideMenu(menuType, params = {}) {
            this.sideMenuType = menuType
            this.sideMenuParams = params
        }
    }
}
</script>

<style></style>
