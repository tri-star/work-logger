<template>
    <transition name="modal" v-if="showModal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="body">
                        <slot name="body"> </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        showModal: {
            type: Boolean,
            required: true
        }
    }
}
</script>

<style lang="scss" scoped>
.modal-mask {
    position: fixed;
    z-index: 9999;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    transition: opacity 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-wrapper {
    min-height: 200px;
    background-color: #fff;

    border: 1px solid #000;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    padding: 10px;
    transition: all 0.3s ease;
}

.modal-container {
}

.modal-enter {
    opacity: 0;

    .modal-wrapper {
        transform: scale(1.1);
    }
}

.modal-leave-active {
    opacity: 0;
    .modal-wrapper {
        transform: scale(1.1);
    }
}
</style>
