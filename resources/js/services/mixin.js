/**
 * Title mixin
 */
function getTitle (vm) {
  const { title } = vm.$options
  if (title) {
    return typeof title === 'function'
      ? title.call(vm)
      : title
  }
}
export let title = {
  created () {
    const title = getTitle(this)
    if (title) {
      document.title = title
    }
  }
}