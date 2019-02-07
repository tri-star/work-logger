import Vue from "vue"
import Vuetify from "vuetify"

require("./bootstrap")

Vue.use(Vuetify)
window.Vue = Vue

new Vue({
    data: {
        mini: false
    },
    el: "#app"
    // components: {
    //     Page1
    // }
})
