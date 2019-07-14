import Vue from "vue"
import Vuex from "vuex"
import VueRouter from "vue-router"
import VeeValidate from "vee-validate"
import initStore from "./store"
import routes from "./routes"
import DefaultLayout from "./pages/default-layout"

require("./bootstrap")
require("es6-promise/auto")

Vue.use(Vuex)
Vue.use(VueRouter)
Vue.use(VeeValidate)
window.Vue = Vue

const router = new VueRouter({ mode: "history", routes })
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
