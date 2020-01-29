import DefaultLayout from './pages/default-layout'
import JapaneseValidationMessages from './strings/validation_ja'
import VeeValidate from 'vee-validate'
import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import WlErrorHandler from './lib/errors/wl-error-handler'
import filters from './filters'
import initStore from './store'
import routes from './routes'

require('./bootstrap')
require('es6-promise/auto')

Vue.use(Vuex)
Vue.use(VueRouter)
Vue.use(VeeValidate, {
  locale: 'ja',
  dictionary: {
    ja: JapaneseValidationMessages
  }
})
window.Vue = Vue

const router = new VueRouter({ mode: 'history', routes })
const store = initStore()

for (const name in filters) {
  Vue.filter(name, filters[name])
}

new Vue({
  el: '#app',
  components: {
    DefaultLayout
  },
  data: {
    mini: false
  },
  methods: {},
  router,
  store,
  template: '<DefaultLayout/>'
})

window.Vue.config.errorHandler = (err, vm, info) => {
  WlErrorHandler.fromVueError(err, vm, info)
}
window.addEventListener('error', (event) => {
  WlErrorHandler.fromGeneralError(event)
})
window.addEventListener('unhandledrejection', (event) => {
  WlErrorHandler.fromPromiseError(event)
})
