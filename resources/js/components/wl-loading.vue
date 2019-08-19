<template>
    <transition name="loading">
        <div class="spinner-container">
            <div class="spinner"></div>
        </div>
    </transition>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
.spinner-container {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-content: center;
    padding: 10px;
}

.spinner {
    width: 32px;
    height: 32px;
    background-image: url("/images/spinner.gif");

    transition: opacity 0.2s;
}

.loading-enter {
    opacity: 0;
}

.loading-leave-active {
    opacity: 0;
}
</style>
