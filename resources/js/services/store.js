import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loggedIn: localStorage.getItem('loggedIn') || false
  },
  getters: {
    loggedIn: state => state.loggedIn
  },
  mutations: {
    setLoggedIn(state, value) {
      state.loggedIn = value
    }
  },
  actions: {
    setLoggedIn(context, value) {
      localStorage.setItem('loggedIn', value)
      context.commit('setLoggedIn', value)
    }
  }
})