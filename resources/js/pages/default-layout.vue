<template>
  <div class="container">
    <DefaultHeader />
    <div class="menu-and-main">
      <SideMenu
        :side-menu-type="sideMenuType"
        :side-menu-params="sideMenuParams"
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
      sideMenuType: 'default',
      sideMenuParams: {}
    }
  },
  methods: {
    handleChangeSideMenu (menuType, params = {}) {
      this.sideMenuType = menuType
      this.sideMenuParams = params
    }
  }
}
</script>

<style>

.menu-and-main {
  display: flex;
  word-wrap: break-word;
  width: 100%;
  min-height: 100vh;
}


</style>
