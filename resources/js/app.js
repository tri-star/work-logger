import DefaultLayout from "./pages/default-layout"
import JapaneseValidationMessages from "./strings/validation_ja"
import VeeValidate from "vee-validate"
import Vue from "vue"
import VueRouter from "vue-router"
import Vuex from "vuex"
import initStore from "./store"
import routes from "./routes"

require("./bootstrap")
require("es6-promise/auto")

Vue.use(Vuex)
Vue.use(VueRouter)
Vue.use(VeeValidate, {
    locale: "ja",
    dictionary: {
        ja: JapaneseValidationMessages
    }
})
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
