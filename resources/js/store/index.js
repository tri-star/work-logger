import Vuex from 'vuex'
import AddTask from './modules/task/add-task'

export default function initStore () {
  return new Vuex.Store({
    state: {},
    mutations: {},
    actions: {},
    modules: {
      AddTask
    }
  })
}
