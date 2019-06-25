import Vue from "vue"
import Vuex from "vuex"
import initStore from "./store"
import DefaultHeader from "./components/header/default-header"

require("./bootstrap")

Vue.use(Vuex)
window.Vue = Vue

const store = initStore()

new Vue({
    data: {
        mini: false
    },
    methods: {},
    el: "#app",
    store,
    components: {
        DefaultHeader
    }
})
