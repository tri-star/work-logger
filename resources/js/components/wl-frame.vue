<template>
  <section :class="`wl-frame size-${size}`">
    <div class="wl-frame-title">
      <slot name="title" />
    </div>
    <div class="wl-frame-body">
      <slot name="body" />
    </div>
  </section>
</template>

<script>
export default {
  props: {
    size: {
      default: 'l',
      type: String
    }
  }
}
</script>

<style lang="scss" scoped>
  @import "../../sass/imports";

  .wl-frame {
    background-color: #fff;
    margin-right: 20px;
    margin-bottom: 20px;
    padding: 10px;
    display: inline-block;
    min-height: 300px;
    overflow-y: auto;
  }

  .size-xl {
    width: calc(100% - 20px);
  }

  .size-l {
    width: calc(50% - 40px);
  }

  .size-m {
    width: calc(33% - 60px);
  }

  .wl-frame-title {
    color: $frame-title-color;
    background-color: $frame-title-background-color;
    font-size: $frame-title-size;
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 15px;
    padding-left: 5px;
  }

  .wl-frame-body {
    display: inline-block;
    width: 100%;
    padding: 5px;
  }

  .fixed-height {
    .body {
      overflow-y: auto;
    }
  }
</style>
