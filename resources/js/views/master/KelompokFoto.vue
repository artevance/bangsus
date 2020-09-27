<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name == 'master.kelompokFoto'">
      <transition name="fade" mode="out-in">
        <preloader-component v-if="state.page.loading"/>
        <div class="col-12 col-xl-12 stretch-card" v-else>
          <div class="card">
            <div class="card-body">
              <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.kelompokFoto', 'create')">Tambah</button>
              <div class="row mt-5">
                <div class="col-12 col-md-6">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.kelompok_foto.q" @keyup="queryData" v-if="$access('master.kelompokFoto', 'read')">
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table table-hover" v-if="$access('master.kelompokFoto', 'read')">
                  <thead>
                    <th>#</th>
                    <th>Kelompok Foto</th>
                    <th>Denda Bila Tidak Mengirim</th>
                    <th>Qty Minimal Per Hari</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(kelompok_foto, i) in data.kelompok_foto">
                      <td>{{ i + 1 }}</td>
                      <td>{{ kelompok_foto.kelompok_foto }}</td>
                      <td>{{ kelompok_foto.denda_tidak_kirim ? 'YA' : 'TIDAK' }}</td>
                      <td>{{ kelompok_foto.pengaturan_kelompok_foto == null ? '' : kelompok_foto.pengaturan_kelompok_foto.qty_minimum_form }}</td>
                      <td>
                        <a class="badge badge-warning" @click="showUpdateModal(kelompok_foto.id)" href="#" v-if="$access('master.kelompokFoto', 'update') && ! kelompok_foto.master">
                          Ubah
                        </a>
                        <router-link class="badge badge-info" :to="{ name: 'master.kelompokFoto.dendaFoto', params: { id: kelompok_foto.id } }" v-if="$access('master.kelompokFoto.dendaFoto', 'access')">
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
      <div class="modal fade" data-entity="kelompokFoto" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.kelompokFoto', 'create')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Kelompok Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Kelompok Foto</label>
                  <input type="text" class="form-control" v-model="form.create.data.kelompok_foto">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.kelompok_foto" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group form-check">
                  <input type="checkbox" v-model="form.create.data.denda_tidak_kirim">
                  <label class="ml-2">Denda Tidak Kirim</label>
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.denda_tidak_kirim" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group" v-if="form.create.data.denda_tidak_kirim">
                  <label>Qty Minimal Kirim</label>
                  <input type="number" class="form-control" v-model="form.create.data.qty_minimum_kirim">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.qty_minimum_kirim" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group" v-if="form.create.data.denda_tidak_kirim">
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
      <div class="modal fade" data-entity="kelompokFoto" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.kelompokFoto', 'update')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="update">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Kelompok Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Kelompok Foto</label>
                  <input type="text" class="form-control" v-model="form.update.data.kelompok_foto">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.kelompok_foto" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group" v-if="form.update.data.denda_tidak_kirim">
                  <label>Qty Minimal Kirim</label>
                  <input type="number" class="form-control" v-model="form.update.data.qty_minimum_form">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.qty_minimum_form" :key="index">
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
        kelompok_foto: []
      },
      form: {
        create: {
          data: {
            kelompok_foto: '',
            denda_tidak_kirim: false,
            nominal: 0,
            qty_minimum_form: 0
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            kelompok_foto: '',
            denda_tidak_kirim: false,
            qty_minimum_form: 0
          },
          errors: {}
        }
      },
      query: {
        kelompok_foto: {
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
          this.data.kelompok_foto = res[0].data.container
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
          this.data.kelompok_foto = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/kelompok_foto?q=' + this.query.kelompok_foto.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="kelompokFoto"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/kelompok_foto/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            kelompok_foto: res.data.container.kelompok_foto,
            denda_tidak_kirim: res.data.container.denda_tidak_kirim,
            qty_minimum_form: res.data.container.pengaturan_kelompok_foto.qty_minimum_form 
          };
          $('[data-entity="kelompokFoto"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/master/kelompok_foto', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kelompok_foto: '',
            denda_tidak_kirim: false,
            nominal: 0,
            qty_minimum_form: 0
          }
          this.prepare()
          $('[data-entity="kelompokFoto"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/kelompok_foto', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            kelompok_foto: '',
            qty_minimum_form: 0
          }
          this.prepare()
          $('[data-entity="kelompokFoto"][data-method="update"]').modal('hide')
        })
        .catch(err => { console.log(err.response)
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