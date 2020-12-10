import store from './store.js'

export default function getAccessFor(menu, action) {

  const role = store.getters.user.role.role_code || 'guest'

  let localAccess = access[role]
  menu.split('.').forEach((item, index) => {
    if (typeof localAccess != 'object') return
    if (Object.keys(localAccess).length == 0) return

    if (index === 0) {
      localAccess = localAccess[item] || {}
    } else {
      localAccess = localAccess.children[item]
    }
  })

  if (localAccess === undefined) localAccess = {}

  if (action === undefined) return localAccess || {}

  else return localAccess[action] || false
  
}

const access = {
  admin: require('./access/admin.js').default,
  leader: require('./access/leader.js').default,
  supervisor: require('./access/supervisor.js').default,
  trustee: require('./access/trustee.js').default,
}