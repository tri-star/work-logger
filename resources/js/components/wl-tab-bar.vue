<template>
  <ul class="tab-bar clear-fix">
    <li v-for="item of items" :key="item.tabKey">
      <WlTabItem :label="item.label" :tab-key="item.tabKey" :is-active="isActive(item.tabKey)" @selected="handleSelected" />
    </li>
  </ul>
</template>

<script>

import WlTabItem from './wl-tab-item'

export default {

  components: {
    WlTabItem
  },

  props: {
    items: {
      type: Object,
      required: true
    },
    initialSelected: {
      type: String,
      default: ''
    }
  },

  data () {
    return {
      current: ''
    }
  },

  mounted () {
    this.current = this.initialSelected
    this.$emit('tabChange', this.current)
  },
  methods: {
    isActive (key) {
      return this.current === key
    },
    handleSelected (selectedKey) {
      if (this.current !== selectedKey) {
        this.$emit('tabChange', selectedKey)
      }
      this.current = selectedKey
    }
  }
}

</script>

<style lang="scss" scoped>
@import "../../sass/imports";

.tab-bar {
  list-style: none;
  padding: 0;
  border-bottom: 2px solid rgba(0,0,0,0.3);
  margin-bottom: 10px;

  li {
    float: left;
  }
}

</style>
