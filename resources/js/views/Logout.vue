<template></template>

<script>
export default {
  created() {
    this.$axios.get('/ajax/v1/logout')
      .then(res => {})
      .catch(err => {})
      .finally(() => {
        this.$store.dispatch('setLoggedIn', false)
        this.$router.push({ name: 'login' })
      })
  }
}
</script>