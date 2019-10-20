<template>
  <section class="wl-frame" :style="'width: ' + width">
    <div class="title">
      <slot name="title" />
    </div>
    <div class="body">
      <slot name="body" />
    </div>
  </section>
</template>

<script>
export default {
  props: {
    width: {
      default: '400px',
      type: String
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "../../sass/imports";

  .wl-frame {
    border: 1px solid $brand-color-second;
    box-shadow: 2px 2px 0 rgb(0, 0, 0, 0.3);
    margin-right: 20px;
    margin-bottom: 20px;
    display: block;
    min-width: 300px;
    height: 300px;
  }

  .title {
    color: #fff;
    background-color: $brand-color-second;
    height: 45px;
    font-size: 15pt;
    font-weight: bold;
    line-height: 2.5;
    padding-left: 5px;
  }

  .body {
    display: inline-block;
    width: 100%;
    height: calc(300px - 45px - 5px);
    padding: 5px;
    overflow-y: auto;
  }
</style>
