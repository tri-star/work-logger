<template>
  <div class="container">
    <DefaultHeader />
    <div :class="{ 'side-wrapper': 1, 'side-hide': hideMenu }">
      <SideMenu
        :side-menu-type="sideMenuType"
        :side-menu-params="sideMenuParams"
        @toggle="handleToggle"
      />
      <main class="content">
        <router-view @changeSideMenu="handleChangeSideMenu" />
      </main>
    </div>
  </div>
</template>

<script>
import DefaultHeader from '../components/header/default-header'
import SideMenu from '../components/side-menu/side-menu'

export default {
  components: {
    DefaultHeader,
    SideMenu
  },
  data () {
    return {
      hideMenu: false,
      sideMenuType: 'default',
      sideMenuParams: {}
    }
  },
  methods: {
    handleToggle () {
      this.hideMenu = !this.hideMenu
    },
    handleChangeSideMenu (menuType, params = {}) {
      this.sideMenuType = menuType
      this.sideMenuParams = params
    }
  }
}
</script>

<style></style>
