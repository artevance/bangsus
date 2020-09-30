<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'formOperasional.formC1'">
      <transition name="fade" mode="out-in">
        <preloader-component v-if="state.page.loading"/>
        <div class="col-12 col-xl-12 stretch-card" v-else>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Cabang
                        </span>
                      </div>
                      <select class="form-control" name="cabang_id" v-model="query.absensi.cabang_id" @change="queryData">
                        <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                          {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Tipe Absensi
                        </span>
                      </div>
                      <select class="form-control" name="cabang_id" v-model="query.absensi.tipe_absensi_id" @change="queryData">
                        <option v-for="(tipe_absensi, i) in data.tipe_absensi" :key="i" :value="tipe_absensi.id">
                          {{ tipe_absensi.tipe_absensi }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Tanggal Absensi
                        </span>
                      </div>
                      <input type="date" class="form-control" name="tanggal_absensi" v-model="query.absensi.tanggal_absensi" @keyup="queryData">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
    <router-view v-else></router-view>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      state: { page: { loading: true } },
      data: {
        divisi: []
      },
      form: {
        create: {
          data: {
            divisi: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            divisi: ''
          },
          errors: {},
          loading: false
        }
      },
      query: {
        divisi: {
          q: ''
        }
      }
    }
  },
  created() {
    // this.prepare()
  },

  methods: {
    /**
     *  Prepare the page.
     */
    prepare() {
      this.state.page.loading = true
      Promise.all([
        this.fetchMainData()
      ])
        .then(res => {
          this.data.divisi = res[0].data.container
          this.state.page.loading = false
        })
        .catch(err => {
          this.$router.go(-1)
        }) 
    },
    /**
     *  Query result.
     */
    queryData() {
      this.fetchMainData()
        .then(res => {
          this.data.divisi = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/divisi?q=' + this.query.divisi.q)
    },
    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="divisi"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/divisi/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            divisi: res.data.container.divisi
          }
          $('[data-entity="divisi"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/divisi', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            divisi: ''
          }
          this.prepare()
          $('[data-entity="divisi"][data-method="create"]').modal('hide')
        })
        .catch(err => { console.log(err.response.data)
          if (err.response.status == 422) {
            this.form.create.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.create.loading = false
        })
    },
    update() {
      this.form.update.loading = true
      this.form.update.errors = {}
      this.$axios.put('/ajax/v1/master/divisi', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            divisi: ''
          }
          this.prepare()
          $('[data-entity="divisi"][data-method="update"]').modal('hide')
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.update.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.update.loading = false
        })
    },
  }
}
</script>