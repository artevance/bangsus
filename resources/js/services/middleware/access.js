import access from '../access.js'
import store from '../store.js'

export default (to, from, next) => {
  if ( ! access(to.name, 'access')) {
    let name = from.name === null
      ? 'dashboard'
      : from.name
    next({
      name: name
    })
  } else {
    next()
  }
}