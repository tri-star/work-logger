import Vue from "vue"
import Vuex from "vuex"
import Vuetify from "vuetify"
import initStore from "./store"

import AddTaskDialog from "./components/task/add-task-dialog"

require("./bootstrap")

Vue.use(Vuetify)
Vue.use(Vuex)
window.Vue = Vue

const store = initStore()

new Vue({
    data: {
        mini: false
    },
    methods: {
        openAddTaskDialog() {
            this.$refs.AddTaskDialog.open()
        }
    },
    el: "#app",
    store,
    components: {
        AddTaskDialog
    }
})
