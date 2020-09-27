import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loggedIn: localStorage.getItem('loggedIn') || false,
    user: []
  },
  getters: {
    loggedIn: state => state.loggedIn,
    user: state => state.user
  },
  mutations: {
    setLoggedIn(state, value) {
      state.loggedIn = value
    },
    setUser(state, user) {
      state.user = user
    }
  },
  actions: {
    setLoggedIn(context, value) {
      localStorage.setItem('loggedIn', value)
      context.commit('setLoggedIn', value)
    },
    setUser(context, user) {
      context.commit('setUser', user)
    }
  }
})