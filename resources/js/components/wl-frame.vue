<template>
  <section :class="`wl-frame size-${size}`">
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
  }

  .size-xl {
    width: 100%;
  }

  .size-l {
    width: calc(50% - 40px);
  }

  .size-m {
    width: calc(33% - 60px);
  }

  .title {
    color: $frame-title-color;
    background-color: $frame-title-background-color;
    font-size: $frame-title-size;
    font-weight: bold;
    text-decoration: underline;
    margin-bottom: 15px;
  }

  .body {
    display: inline-block;
    width: 100%;
    height: calc(300px - 45px - 5px);
    padding: 5px;
    overflow-y: auto;
  }
</style>
