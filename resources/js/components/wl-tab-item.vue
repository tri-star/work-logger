<template>
  <div :class="{'tab-item': 1, 'active': isActive}" @click="$emit('selected', tabKey)">
    {{ label }}
  </div>
</template>

<script>

export default {
  props: {
    tabKey: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    isActive: {
      type: Boolean,
      default: false
    },
  },

}

</script>

<style lang="scss" scoped>
@import "../../sass/imports";

.tab-item {
  display: inline-block;
  padding: 10px 20px;
  min-height: 50px;
  min-width: 150px;
  border-radius: 8px 8px 0 0;
  transition: background-color 0.3s, border-bottom, 0.3s;
  margin-right: 10px;
  margin-bottom: -2px;
  border-bottom: 2px solid rgba(100,100,100);
  text-align: center;
  cursor: pointer;

  &.active {
    border-bottom: 5px solid $accent-color;
  }
}

.tab-item:hover {
  background-color: rgba(255,255,255,0.7);
}

</style>
