<template>
  <transition name="fade" mode="out-in">
    <default-layout v-if="$route.meta.layout === 'default'" />
    <plain-layout v-else-if="$route.meta.layout === 'plain'" />
  </transition>
</template>