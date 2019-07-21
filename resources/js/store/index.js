import AddTask from "./modules/task/add-task"
import Vuex from "vuex"

export default function initStore() {
    return new Vuex.Store({
        state: {},
        mutations: {},
        actions: {},
        modules: {
            AddTask
        }
    })
}
