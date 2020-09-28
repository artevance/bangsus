<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.tipeFotoKaryawan', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.tipe_foto_karyawan.q" @keyup="queryData" v-if="$access('master.tipeFotoKaryawan', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.tipeFotoKaryawan', 'read')">
                <thead>
                  <th>#</th>
                  <th>Tipe Foto Karyawan</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(tipe_foto_karyawan, i) in data.tipe_foto_karyawan">
                    <td>{{ i + 1 }}</td>
                    <td>{{ tipe_foto_karyawan.tipe_foto_karyawan }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(tipe_foto_karyawan.id)" href="#" v-if="$access('master.tipeFotoKaryawan', 'update')">
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
    <div class="modal fade" data-entity="tipeFotoKaryawan" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.tipeFotoKaryawan', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Tipe Foto Karyawan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Tipe Foto Karyawan</label>
                <input type="text" class="form-control" v-model="form.create.data.tipe_foto_karyawan">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.tipe_foto_karyawan" :key="index">
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
    <div class="modal fade" data-entity="tipeFotoKaryawan" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.tipeFotoKaryawan', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Tipe Foto Karyawan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Tipe Foto Karyawan</label>
                <input type="text" class="form-control" v-model="form.update.data.tipe_foto_karyawan">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.tipe_foto_karyawan" :key="index">
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
        tipe_foto_karyawan: []
      },
      form: {
        create: {
          data: {
            tipe_foto_karyawan: ''
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            tipe_foto_karyawan: ''
          },
          errors: {}
        }
      },
      query: {
        tipe_foto_karyawan: {
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
          this.data.tipe_foto_karyawan = res[0].data.container
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
          this.data.tipe_foto_karyawan = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/tipe_foto_karyawan?q=' + this.query.tipe_foto_karyawan.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="tipeFotoKaryawan"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/tipe_foto_karyawan/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            tipe_foto_karyawan: res.data.container.tipe_foto_karyawan
          }
          $('[data-entity="tipeFotoKaryawan"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/tipe_foto_karyawan', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            tipe_foto_karyawan: ''
          }
          this.prepare()
          $('[data-entity="tipeFotoKaryawan"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/tipe_foto_karyawan', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            tipe_foto_karyawan: ''
          }
          this.prepare()
          $('[data-entity="tipeFotoKaryawan"][data-method="update"]').modal('hide')
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