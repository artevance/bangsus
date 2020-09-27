<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.jabatan', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <form data-role="search">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." name="q">
                </form>
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.jabatan', 'read')">
                <thead>
                  <th>#</th>
                  <th>Jabatan</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(jabatan, i) in data.jabatan">
                    <td>{{ i + 1 }}</td>
                    <td>{{ jabatan.jabatan }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(jabatan.id)" href="#" v-if="$access('master.jabatan', 'update')">
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
    <div class="modal fade" data-entity="jabatan" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.jabatan', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Jabatan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Jabatan</label>
                <input type="text" class="form-control" v-model="form.create.data.jabatan">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.jabatan" :key="index">
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
    <div class="modal fade" data-entity="jabatan" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.jabatan', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Jabatan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Jabatan</label>
                <input type="text" class="form-control" v-model="form.update.data.jabatan">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.jabatan" :key="index">
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
        jabatan: []
      },
      form: {
        create: {
          data: {
            jabatan: ''
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            jabatan: ''
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
        this.$axios.get('/ajax/v1/master/jabatan')
      ])
        .then(res => {
          this.data.jabatan = res[0].data.container
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
      $('[data-entity="jabatan"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/jabatan/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            jabatan: res.data.container.jabatan
          }
          $('[data-entity="jabatan"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/master/jabatan', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            jabatan: ''
          }
          this.prepare()
          $('[data-entity="jabatan"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/jabatan', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            jabatan: ''
          }
          this.prepare()
          $('[data-entity="jabatan"][data-method="update"]').modal('hide')
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