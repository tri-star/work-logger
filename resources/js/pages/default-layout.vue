<template>
    <div class="container">
        <DefaultHeader />
        <div :class="{ 'side-wrapper': 1, 'side-hide': hideMenu }">
            <SideMenu @toggle="handleToggle" :sideMenuType="sideMenuType" />
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
            sideMenuType: "default"
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
        handleChangeSideMenu(menuType) {
            this.sideMenuType = menuType
        }
    }
}
</script>

<style></style>
