<template>
  <header :class="{ dev: env == 'local' }">
    <h1 class="logo">
      <span v-if="env == 'local'">(LOCAL)</span>WORK LOGGER
    </h1>
  </header>
</template>

<script>
export default {

  props: {
    env: {
      type: String,
      default: process.env.MIX_APP_ENV
    }
  }

}
</script>

<style lang="scss" scoped>
  @import "../../../sass/imports";

  header {
    background-color: $header-background-color;
    color: $header-font-color;
    padding: 5px;
    height: $header-height;
    width: 100vw;
    position: fixed;
    top: 0;
    z-index: 10000;
    box-shadow: 0 3px 3px rgba(0,0,0,0.2);

    .logo {
      font-size: 20pt;
      text-shadow: 1px 1px 0 rgba(0,0,0,0.3);
    }
  }

  .dev {
    background-color: $header-background-color-dev;
  }

</style>
