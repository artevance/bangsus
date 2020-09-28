<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name == 'master.kelompokFoto.dendaFoto'">
      <transition name="fade" mode="out-in">
        <preloader-component v-if="state.page.loading"/>
        <div class="col-12 col-xl-12 stretch-card" v-else>
          <div class="card">
            <div class="card-body">
              <router-link :to="{ name: 'master.kelompokFoto' }">
                <i class="fas fa-backspace"></i> Kembali
              </router-link>
              <div class="row">
                <div class="col">
                  <div class="card-title">{{ data.parent.kelompok_foto }}</div>
                </div>
              </div>
              <button class="btn btn-primary mt-3" @click="showCreateModal" v-if="$access('master.kelompokFoto.dendaFoto', 'create')">Tambah</button>
              <div class="row mt-5">
                <div class="col-12 col-md-6">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.denda_foto.q" @keyup="queryData" v-if="$access('master.kelompokFoto.dendaFoto', 'read')">
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table table-hover" v-if="$access('master.kelompokFoto.dendaFoto', 'read')">
                  <thead>
                    <th>#</th>
                    <th>Denda Foto</th>
                    <th>Master</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(denda_foto, i) in data.denda_foto">
                      <td>{{ i + 1 }}</td>
                      <td>{{ denda_foto.denda_foto }}</td>
                      <td>{{ denda_foto.master ? 'YA' : 'TIDAK' }}</td>
                      <td>{{ denda_foto.nominal }}</td>
                      <td>
                        <a class="badge badge-warning" @click="showUpdateModal(denda_foto.id)" href="#" v-if="$access('master.kelompokFoto.dendaFoto', 'update') && ! denda_foto.master">
                          Ubah
                        </a>
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
      <div class="modal fade" data-entity="dendaFoto" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.kelompokFoto.dendaFoto', 'create')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Denda Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Denda Foto</label>
                  <input type="text" class="form-control" v-model="form.create.data.denda_foto">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.denda_foto" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Nominal Denda Tidak Kirim</label>
                  <input type="number" class="form-control" v-model="form.create.data.nominal">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.nominal" :key="index">
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
      <div class="modal fade" data-entity="dendaFoto" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.kelompokFoto.dendaFoto', 'update')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="update">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Denda Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Denda Foto</label>
                  <input type="text" class="form-control" v-model="form.update.data.denda_foto">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.denda_foto" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Nominal Denda Tidak Kirim</label>
                  <input type="number" class="form-control" v-model="form.update.data.nominal">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.nominal" :key="index">
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
        denda_foto: [],
        parent: []
      },
      form: {
        create: {
          data: {
            denda_foto: '',
            kelompok_foto_id: this.$route.params.id,
            nominal: 0
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            denda_foto: '',
            nominal: 0
          },
          errors: {}
        }
      },
      query: {
        denda_foto: {
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
        this.fetchMainData(),
        this.fetchParentData()
      ])
        .then(res => {
          this.data.denda_foto = res[0].data.container
          this.data.parent = res[1].data.container
          this.state.page.loading = false
        })
        .catch(err => { console.log(err.response)
          this.$router.go(-1)
        }) 
    },
    /**
     *  Query result.
     */
    queryData() {
      this.fetchMainData()
        .then(res => {
          this.data.denda_foto = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/denda_foto/parent/' + this.$route.params.id + '?q=' + this.query.denda_foto.q)
    },
    /**
     *  Fetch parent data
     */
    fetchParentData() {
      return this.$axios.get('/ajax/v1/master/kelompok_foto/' + this.$route.params.id)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="dendaFoto"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/denda_foto/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            denda_foto: res.data.container.denda_foto,
            nominal: res.data.container.nominal
          }
          $('[data-entity="dendaFoto"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/denda_foto', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            denda_foto: '',
            kelompok_foto_id: this.$route.params.id,
            nominal: 0
          }
          this.prepare()
          $('[data-entity="dendaFoto"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/denda_foto', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            denda_foto: '',
            nominal: 0
          }
          this.prepare()
          $('[data-entity="dendaFoto"][data-method="update"]').modal('hide')
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