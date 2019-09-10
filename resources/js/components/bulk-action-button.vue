<template>
    <div class="wrapper">
        <div
            ref="overlay"
            class="modal-overlay"
            @mousedown="toggleMenu()"
        ></div>
        <button ref="button" class="command-button" @click="toggleMenu()">
            <i class="icon fas fa-check-square"></i>{{ title }}
            <span class="separator"></span>
            <span class="icon fas fa-caret-down"></span>
            <ul :class="['menu', showMenu ? 'menu-open' : '']">
                <li
                    v-for="menu in menuList"
                    :key="menu.title"
                    @click="menu.handler"
                >
                    {{ menu.title }}
                </li>
            </ul>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },
        menuList: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            showMenu: false
        }
    },

    methods: {
        toggleMenu() {
            this.showMenu = !this.showMenu

            const menuListElement = this.$refs.button.querySelector("ul.menu")
            if (!menuListElement) {
                return
            }
            menuListElement.style.top = `${this.$refs.button.clientHeight}px`
            this.$refs.overlay.style.display = this.showMenu ? "block" : "none"
        }
    }
}
</script>

<style lang="scss">
.wrapper {
    display: inline-block;
}

.separator {
    margin: 0 5px;
    border-left: 1px solid rgba(0, 0, 0, 0.2);
    box-shadow: 1px 0 rgba(255, 255, 255, 0.8);
}

.fa-caret-down {
    margin-left: 5px;
}

button {
    position: relative;
    cursor: pointer;
}

ul.menu {
    display: none;
    opacity: 0;

    position: absolute;
    list-style: none;
    padding-left: 0;
    top: 0;
    left: 0;
}

ul.menu-open {
    display: block;
    opacity: 1;

    z-index: 100;
    max-height: 500px;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.2);

    box-shadow: 2px 2px rgba(0, 0, 0, 0.2);
}

li {
    padding: 5px;
    background-color: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.3);
    white-space: nowrap;
}

li:hover {
    background-color: darken(#fff, 10%);
}

.modal-overlay {
    display: none;
    z-index: 99;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
