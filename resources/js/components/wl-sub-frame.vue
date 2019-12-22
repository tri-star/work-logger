<template>
  <section :class="`wl-sub-frame size-${size}`">
    <div class="wl-sub-frame-title">
      <slot name="title" />
    </div>
    <div class="wl-sub-frame-body">
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

  .wl-sub-frame {
    background-color: $frame-sub-background-color;
    margin-right: 20px;
    margin-bottom: 20px;
    padding: 10px;
    // display: inline-block;
    border: 1px solid $frame-sub-border-color;
    border-radius: 2px;
  }

  .size-l {
    width: calc(100% - 20px);
  }

  .size-m {
    width: calc(50% - 40px);
  }

  .size-s {
    width: calc(33% - 60px);
    min-width: 400px;
  }

  .wl-sub-frame-title {
    color: $frame-sub-title-color;
    background-color: $frame-sub-background-color;
    font-size: $frame-sub-title-size;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .wl-sub-frame-body {
    display: inline-block;
    width: 100%;
  }
</style>
