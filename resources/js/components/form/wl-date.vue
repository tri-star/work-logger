<template>
    <input
        type="input"
        @input="onInput"
        @keydown.space="insertToday"
        @keydown.up="offsetDay(1)"
        @keydown.down="offsetDay(-1)"
        :value="value"
        autocomplete="off"
    />
</template>

<script>
import dayjs from "dayjs"

export default {
    props: {
        value: {
            required: true
        }
    },

    data() {
        return {
            innerValue: this.value
        }
    },
    methods: {
        insertToday() {
            this.innerValue = dayjs().format("YYYY-MM-DD")
            this.$emit("input", this.innerValue)
            return true
        },
        offsetDay(offset) {
            if (!/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test(this.innerValue)) {
                return
            }
            this.innerValue = dayjs(this.innerValue)
                .add(offset, "day")
                .format("YYYY-MM-DD")
            this.$emit("input", this.innerValue)
        },
        onInput(event) {
            this.innerValue = event.target.value.trim()
            this.$emit("input", this.innerValue)
        }
    },
    watch: {
        value(newValue) {
            this.innerValue = newValue
        }
    }
}
</script>
