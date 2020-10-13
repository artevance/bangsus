<template>
  <div class="row mt-5">
    <div class="col-12 col-xl-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <transition name="fade" mode="out-in">
            <preloader-component v-if="state.page.loading"/>
            <div v-else>
              <div class="card-title"></div>
              <p class="text-muted"></p>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      state: {
        page: { loading: true }
      },
      data: {
        form_pemberian_tugas: {}
      }
    }
  },
  created() {
    this.prepare()
  },

  methods: {
    prepare() {
      this.state.page.loading = true
      this.$axios.get('/ajax/v1/form_operasional/form_pemberian_tugas/' + this.$route.params.id)
        .then(res => {
          this.data.form_pemberian_tugas = res.data.container
        })
        .catch(err => {

        })
        .finally(() => {
          this.state.page.loading = false
        })
    }
  }
}
</script>