<template>
    <div>
        <div
            :class="{ modal: true, 'modal-visible': showMenu }"
            @mousedown="closeMenuList"
        ></div>

        <div :class="{ select: true, 'show-menu': showMenu }">
            <span class="title" @click="toggleMenuList">{{
                selectedLabel
            }}</span>
            <ul>
                <li v-for="(label, key) in menues" :key="key">
                    <input
                        type="radio"
                        name="name"
                        :value="key"
                        :id="`${name}-${key}`"
                        @change="changeHandler(key)"
                    /><label :for="`${name}-${key}`">{{ label }}</label>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    model: {
        prop: "value",
        event: "change"
    },
    props: {
        name: {
            type: String,
            required: "false"
        },
        menues: {
            type: Object,
            required: true
        },
        value: {}
    },

    data() {
        return {
            showMenu: false,
            selectedKey: null
        }
    },

    methods: {
        toggleMenuList() {
            this.showMenu = !this.showMenu
        },
        closeMenuList() {
            this.showMenu = false
        },

        changeHandler(selectedKey) {
            this.selectedKey = selectedKey
            this.showMenu = false
            this.$emit("change", this.selectedKey)
        }
    },

    computed: {
        selectedLabel() {
            if (
                this.value === null ||
                typeof this.menues[this.value] === "undefined"
            ) {
                return "未選択"
            }

            return this.menues[this.value]
        }
    },
    watch: {
        value(newValue) {
            this.selectedKey = newValue
        }
    }
}
</script>

<style lang="scss" scoped>
@import "../../../sass/imports";

.modal {
    display: none;
    z-index: 99;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.modal-visible {
    display: block;
}

.select {
    padding: 5px;
    border: 1px solid $primary-font-color;
    box-shadow: 1px 1px 0 rgba(0, 0, 0, 0.2);
    width: 200px;
    position: relative;
    cursor: pointer;
}

.select:hover {
    background-color: darken($light-primary-color, 10%);
}

.select::after {
    color: $primary-font-color;
    position: absolute;
    top: 5px;
    right: 15px;
    content: "\025BC";
}

.select .title {
    color: $primary-font-color;
    display: block;
}

.select ul {
    list-style: none;
    padding-left: 0px;

    position: absolute;
    z-index: 100;
    left: 0;
    width: calc(100%);
    overflow: hidden;

    max-height: 0;
    opacity: 0;
    transition: 0.5s;

    border: 1px solid $primary-font-color;
    box-shadow: 2px 2px 1px rgba(0, 0, 0, 0.2);
}

.select.show-menu ul {
    max-height: 500px;
    opacity: 1;
}

.select ul li {
    display: block;
    height: 30px;
}

.select ul li input[type="radio"] {
    display: none;
}

.select ul li label {
    display: block;
    cursor: pointer;
    padding: 5px;
    background-color: #fff;
}

.select ul li label:hover {
    background-color: darken($light-primary-color, 10%);
}

.select ul li input:checked + label {
    background-color: darken($light-primary-color, 20%);
}
</style>
