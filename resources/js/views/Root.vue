<template>
  <transition name="fade" mode="out-in">
    <default-layout v-if="$route.meta.layout === 'default'"/>
    <plain-layout v-else-if="$route.meta.layout === 'plain'"/>
  </transition>
</template>

<script>
export default {
  title() {
    return this.$route.meta.title + ' | BangsusApp'
  },
  watch: {
    '$route' (to, from) {
      document.title = to.meta.title + ' | BangsusApp'
    }
  },
}
</script>