<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name == 'master.atributKaryawan'">
      <transition name="fade" mode="out-in">
        <preloader-component v-if="state.page.loading"/>
        <div class="col-12 col-xl-12 stretch-card" v-else>
          <div class="card">
            <div class="card-body">
              <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.atributKaryawan', 'create')">Tambah</button>
              <div class="row mt-5">
                <div class="col-12 col-md-6">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.atribut_karyawan.q" @keyup="queryData" v-if="$access('master.atributKaryawan', 'read')">
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table table-hover" v-if="$access('master.atributKaryawan', 'read')">
                  <thead>
                    <th>#</th>
                    <th>Atribut Karyawan</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(atribut_karyawan, i) in data.atribut_karyawan">
                      <td>{{ i + 1 }}</td>
                      <td>{{ atribut_karyawan.atribut_karyawan }}</td>
                      <td>
                        <a class="badge badge-warning" @click="showUpdateModal(atribut_karyawan.id)" href="#" v-if="$access('master.atributKaryawan', 'update')">
                          Ubah
                        </a>
                        <router-link class="badge badge-info" :to="{ name: 'master.atributKaryawan.parameterAtributKaryawan', params: { id: atribut_karyawan.id } }" v-if="$access('master.atributKaryawan.parameterAtributKaryawan', 'access')">
                          Lihat Parameter
                        </router-link>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </transition>

      <!-- Modal -->
      <div class="modal fade" data-entity="atributKaryawan" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.atributKaryawan', 'create')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Atribut Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Atribut Karyawan</label>
                  <input type="text" class="form-control" v-model="form.create.data.atribut_karyawan">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.atribut_karyawan" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.create.loading">
                  <spinner-component size="sm" color="light" v-if="form.create.loading"/>
                  Tambah
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" data-entity="atributKaryawan" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.atributKaryawan', 'update')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="update">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Atribut Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Atribut Karyawan</label>
                  <input type="text" class="form-control" v-model="form.update.data.atribut_karyawan">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.atribut_karyawan" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.update.loading">
                  <spinner-component size="sm" color="light" v-if="form.update.loading"/>
                  Ubah
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
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
        atribut_karyawan: []
      },
      form: {
        create: {
          data: {
            atribut_karyawan: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            atribut_karyawan: ''
          },
          errors: {},
          loading: false
        }
      },
      query: {
        atribut_karyawan: {
          q: ''
        }
      }
    }
  },
  created() {
    this.prepare()
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
          this.data.atribut_karyawan = res[0].data.container
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
          this.data.atribut_karyawan = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/atribut_karyawan?q=' + this.query.atribut_karyawan.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="atributKaryawan"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/atribut_karyawan/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            atribut_karyawan: res.data.container.atribut_karyawan
          }
          $('[data-entity="atributKaryawan"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/atribut_karyawan', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            atribut_karyawan: ''
          }
          this.prepare()
          $('[data-entity="atributKaryawan"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/atribut_karyawan', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            atribut_karyawan: ''
          }
          this.prepare()
          $('[data-entity="atributKaryawan"][data-method="update"]').modal('hide')
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