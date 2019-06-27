import Vue from "vue"
import Vuex from "vuex"
import VueRouter from "vue-router"
import initStore from "./store"
import routes from "./routes"
import DefaultLayout from "./pages/default-layout"

require("./bootstrap")

Vue.use(Vuex)
window.Vue = Vue

const router = new VueRouter(routes)
const store = initStore()

new Vue({
    data: {
        mini: false
    },
    methods: {},
    el: "#app",
    router,
    store,
    components: {
        DefaultLayout
    },
    template: `<DefaultLayout/>`
})
