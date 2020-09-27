import axios from 'axios'
import store from '../store.js'

export default (to, from, next) => {
  axios.get('/sanctum/csrf-cookie').then(res => {
    axios.get('/ajax/v1/robust').then(res => {
      if (res.data.container.authentication) {
        store.dispatch('setUser', res.data.container.user)
        next()
      } else {
        next({ name: 'logout' })
      }
    }).catch(err => {
      next({ name: 'logout' })
    })
  }).catch(err => {
    next({ name: 'logout' })
  })
}