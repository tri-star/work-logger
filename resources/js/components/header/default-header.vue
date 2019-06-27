<template>
    <header><h1 class="logo">Task Logger</h1></header>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

header {
    background-color: $header-background-color;
    color: $header-font-color;
    padding: 5px;
    height: $header-height;

    .logo {
        font-size: 40pt;
    }
}
</style>
