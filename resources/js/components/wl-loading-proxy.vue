<template>
    <div>
        <slot name="loading" v-if="state === 'loading'">
            <WlLoading />
        </slot>
        <slot name="done" v-if="state === 'done'"> </slot>
        <slot name="error" v-if="state === 'error'">
            Error
        </slot>
    </div>
</template>

<script>
import WlLoading from "./wl-loading"

export default {
    props: {
        loadingFunction: {
            Type: Function,
            required: true
        }
    },
    components: {
        WlLoading
    },
    data() {
        return {
            state: "loading"
        }
    },
    mounted() {
        this.state = "loading"
        this.loadingFunction()
            .then(() => {
                this.state = "done"
            })
            .catch(error => {
                this.state = "error"
            })
    }
}
</script>

<style lang="scss" scoped></style>
