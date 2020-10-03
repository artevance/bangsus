<template>
  <div class="row mt-5">
    <div class="col-12 col-xl-12 stretch-card">
      <div class="card">
        <div class="card-header d-none d-md-block">
          <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item" v-for="(nav, i) in nav">
              <router-link class="nav-link" :class="{ active: $route.name === nav.to }" :to="{ name: nav.to }">
                {{ nav.title }}
              </router-link>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="form-group d-md-none">
            <label>Tipe Form C1</label>
            <select class="form-control" @change="changeType" v-model="data.formC1Type">
              <option v-for="(nav, i) in nav" :value="nav.to">
                {{ nav.title }}
              </option>
            </select>
          </div>
          <transition name="fade" mode="out-in">
            <router-view></router-view>
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
      state: { page: { loading: false } },
      nav: [
        { title: 'Thawing Ayam', to: 'formOperasional.formC1.formThawingAyam' },
        { title: 'Goreng', to: 'formOperasional.formC1.formGoreng' },
        { title: 'Masak Nasi', to: 'formOperasional.formC1.formMasakNasi' },
        { title: 'Sambal', to: 'formOperasional.formC1.formSambal' },
        { title: 'Tepung', to: 'formOperasional.formC1.formTepung' },
        { title: 'Minyak', to: 'formOperasional.formC1.formMinyak' },
        { title: 'Margarin', to: 'formOperasional.formC1.formMargarin' },
        { title: 'LPG', to: 'formOperasional.formC1.formLPG' }
      ],
      data: {
        formC1Type: this.$route.name
      }
    }
  },

  methods: {
    changeType() {
      this.$router.push({ name: this.data.formC1Type })
    }
  }
}
</script>