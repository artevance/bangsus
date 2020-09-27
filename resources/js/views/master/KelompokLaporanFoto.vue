<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.kelompokLaporanFoto', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <form data-role="search">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." name="q">
                </form>
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.kelompokLaporanFoto', 'read')">
                <thead>
                  <th>#</th>
                  <th>Kelompok Laporan Foto</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(kelompok_laporan_foto, i) in data.kelompok_laporan_foto">
                    <td>{{ i + 1 }}</td>
                    <td>{{ kelompok_laporan_foto.kelompok_laporan_foto }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(kelompok_laporan_foto.id)" href="#" v-if="$access('master.kelompokLaporanFoto', 'update')">
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
    <div class="modal fade" data-entity="kelompokLaporanFoto" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.kelompokLaporanFoto', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Kelompok Laporan Foto</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kelompok Laporan Foto</label>
                <input type="text" class="form-control" v-model="form.create.data.kelompok_laporan_foto">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.kelompok_laporan_foto" :key="index">
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
    <div class="modal fade" data-entity="kelompokLaporanFoto" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.kelompokLaporanFoto', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Kelompok Laporan Foto</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kelompok Laporan Foto</label>
                <input type="text" class="form-control" v-model="form.update.data.kelompok_laporan_foto">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.kelompok_laporan_foto" :key="index">
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
</template>

<script>
export default {
  data() {
    return {
      state: { page: { loading: true } },
      data: {
        kelompok_laporan_foto: []
      },
      form: {
        create: {
          data: {
            kelompok_laporan_foto: ''
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            kelompok_laporan_foto: ''
          },
          errors: {}
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
        this.$axios.get('/ajax/v1/master/kelompok_laporan_foto')
      ])
        .then(res => {
          this.data.kelompok_laporan_foto = res[0].data.container
          this.state.page.loading = false
        })
        .catch(err => {
          this.$router.go(-1)
        }) 
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="kelompokLaporanFoto"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/kelompok_laporan_foto/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            kelompok_laporan_foto: res.data.container.kelompok_laporan_foto
          }
          $('[data-entity="kelompokLaporanFoto"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/master/kelompok_laporan_foto', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kelompok_laporan_foto: ''
          }
          this.prepare()
          $('[data-entity="kelompokLaporanFoto"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/kelompok_laporan_foto', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            kelompok_laporan_foto: ''
          }
          this.prepare()
          $('[data-entity="kelompokLaporanFoto"][data-method="update"]').modal('hide')
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