<template>
  <div>
    <button class="btn btn-primary mt-3" @click="optimizeImage" :disabled="loading">Optimize</button>
    <div class="progress mt-5">
      <div class="progress-bar" role="progressbar" v-bind:style="{ width: progress + '%' }"></div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      progress: 0,
      maxPage: 1750,
      page: 0,
      loading: false
    }
  },
  created() {

  },

  methods: {
    optimizeImage() {
      this.loading = true
      this.$axios.get('/ajax/v1/optimize_image/' + this.page)
        .then(res => {
          this.progress = (this.page / this.maxPage) * 100
          this.page++

          if (this.page <= this.maxPage)
            this.optimizeImage()
          else
            this.loading = false
        })
    }
  }
}
</script>