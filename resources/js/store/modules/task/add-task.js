export default {
  namespaced: true,
  state: {
    issueNo: '',
    title: '',
    description: '',
    estimateMinutes: 0,
    actualMinutes: 0
  },
  mutations: {
    update (state, payload) {
      console.log('passed')
      const key = payload.key
      const value = payload.value
      state[key] = value
    }
  },
  actions: {
    async create (context, payload) {}
  }
}
