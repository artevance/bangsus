export default {
  auth: require('./middleware/auth.js').default,
  access: require('./middleware/access.js').default
}