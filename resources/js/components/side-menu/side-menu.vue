<template>
  <aside :class="{side: !hideMenu, 'hide-menu': hideMenu}">
    <div class="menu-content">
      <div class="item" @click="handleToggle">
        <div class="fas fa-bars" />
      </div>
      <router-link
        v-for="(menuItem, index) in menuList"
        :key="index"
        tag="div"
        :to="menuItem.to"
        class="item "
      >
        <div :class="`${menuItem.icon}`" />
        <span class="label">{{ menuItem.label }}</span>
      </router-link>
    </div>
    <div class="menu-content-small">
      <div class="item" @click="handleToggle">
        <div class="fas fa-bars" />
      </div>
      <router-link
        v-for="(menuItem, index) in menuList"
        :key="index"
        tag="div"
        :to="menuItem.to"
        class="item"
      >
        <div :class="`${menuItem.icon}`" />
      </router-link>
    </div>
  </aside>
</template>

<script>
import _cloneDeep from 'lodash/cloneDeep'

const menues = {
  default: [
    // { icon: "fas fa-chart-pie", label: "統計情報", to: "" },
    { icon: 'fas fa-home', label: 'ダッシュボード', to: '/' },
    { icon: 'fas fa-project-diagram', label: 'プロジェクト', to: '/project' },
    { icon: 'fas fa-cog', label: '設定', to: '' }
  ],
  project: [
    { icon: 'fas fa-home', label: 'ダッシュボード', to: '/' },
    {
      icon: 'fas fa-project-diagram',
      label: 'プロジェクト',
      to: '/project/:id'
    },
    {
      icon: 'fas fa-tasks',
      label: 'タスク一覧',
      to: '/project/:id/tasks'
    }
    // { icon: "fas fa-cog", label: "設定", to: "" }
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
    },
    // hideMenu: {
    //   type: Boolean,
    //   default: false
    // }
  },
  data () {
    return {
      hideMenu: false
    }
  },
  computed: {
    menuList () {
      if (!menues[this.sideMenuType]) {
        return []
      }
      const convertedMenues = _cloneDeep(menues[this.sideMenuType]).map(
        (item) => {
          Object.keys(this.sideMenuParams).forEach((key) => {
            item.to = item.to.replace(
              `:${key}`,
              this.sideMenuParams[key]
            )
          })
          return item
        }
      )
      return convertedMenues
    }
  },
  methods: {
    handleToggle () {
      this.hideMenu = !this.hideMenu
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "../../../sass/imports";

  aside {
    padding-top: $header-height + 10px;
    width: $side-menu-width;
    background-color: $side-menu-background-color;

    transition: width 0.3s, opacity 0.3s;
    overflow: hidden;

    .menu-content-small {
      display: none;
    }

    .item {
      height: 40px;
      color: #fff;
      display: flex;
      margin: auto 5px;
      cursor: pointer;
      transition: background-color 0.3s;
      border-radius: 4px;

      .fas {
        display: inline-block;
        margin-right: 5px;
        height: 25px;
        margin: auto 5px;
      }

      .label {
        display: inline-block;
        margin: auto 0;
        height: 28px;
      }

      &:hover {
        background-color: lighten($side-menu-background-color, 20%);
      }
    }

  }

  .hide-menu {
    width: 50px;
    overflow: hidden;

    .menu-content {
      display: none;
    }
    .menu-content-small {
      display: block;
    }

    .item {
      height: 40px;
      color: #fff;
      display: flex;
      margin: auto 5px;
      cursor: pointer;
      transition: background-color 0.3s;
      border-radius: 4px;

      .fas {
        display: inline-block;
        margin-right: 5px;
        height: 25px;
        width: 25px;
        margin: auto;
        font-size: 25px;
      }

      &:hover {
        background-color: lighten($side-menu-background-color, 20%);
      }
    }

  }

</style>
