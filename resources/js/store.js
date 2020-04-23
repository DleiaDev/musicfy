export default {
  state: {
    emptyNotification: false
  },

  getters: {
    emptyNotification(state) {
      return state.emptyNotification;
    }
  },

  mutations: {
    setEmptyNotification(state, payload) {
      state.emptyNotification = payload;
    }
  }
}
