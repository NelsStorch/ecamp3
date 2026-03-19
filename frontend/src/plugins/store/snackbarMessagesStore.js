export const state = {
  queue: [],
}

export const mutations = {
  addSnackbarMessage(state, message) {
    state.queue.push(message)
  },
}

export const getters = {
  snackbarMessages(state) {
    return [...state.queue]
  },
}

export default {
  state,
  mutations,
  getters,
}
