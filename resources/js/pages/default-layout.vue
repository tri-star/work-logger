<template>
    <div class="container">
        <DefaultHeader />
        <div :class="{ 'side-wrapper': 1, 'side-hide': hideMenu }">
            <SideMenu @toggle="handleToggle" />
            <main class="content"><router-view /></main>
        </div>
    </div>
</template>

<script>
import DefaultHeader from "../components/header/default-header"
import SideMenu from "../components/side-menu/side-menu"

export default {
    data() {
        return {
            hideMenu: true
        }
    },
    components: {
        DefaultHeader,
        SideMenu
    },
    methods: {
        handleToggle() {
            this.hideMenu = !this.hideMenu
        }
    }
}
</script>

<style></style>
