<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.cabang', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.cabang.q" @keyup="queryData" v-if="$access('master.cabang', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.cabang', 'read')">
                <thead>
                  <th>#</th>
                  <th>Kode Cabang</th>
                  <th>Cabang</th>
                  <th>Tipe Cabang</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(cabang, i) in data.cabang">
                    <td>{{ i + 1 }}</td>
                    <td>{{ cabang.kode_cabang }}</td>
                    <td>{{ cabang.cabang }}</td>
                    <td>{{ cabang.tipe_cabang.tipe_cabang }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(cabang.id)" href="#" v-if="$access('master.cabang', 'update')">
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
    <div class="modal fade" data-entity="cabang" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.cabang', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Cabang</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kode Cabang</label>
                <input type="text" class="form-control" v-model="form.create.data.kode_cabang">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.kode_cabang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Cabang</label>
                <input type="text" class="form-control" v-model="form.create.data.cabang">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.cabang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tipe Cabang</label>
                <select class="form-control" v-model="form.create.data.tipe_cabang_id">
                  <option value="null">-- Pilih Tipe Cabang --</option>
                  <option v-for="(tipe_cabang, i) in data.tipe_cabang" :key="i" :value="tipe_cabang.id">
                    {{ tipe_cabang.tipe_cabang }}
                  </option>
                </select>
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
    <div class="modal fade" data-entity="cabang" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.cabang', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Cabang</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Kode Cabang</label>
                <input type="text" class="form-control" v-model="form.update.data.kode_cabang">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.kode_cabang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Cabang</label>
                <input type="text" class="form-control" v-model="form.update.data.cabang">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.cabang" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tipe Cabang</label>
                <select class="form-control" v-model="form.update.data.tipe_cabang_id">
                  <option value="null">-- Pilih Tipe Cabang --</option>
                  <option v-for="(tipe_cabang, i) in data.tipe_cabang" :key="i" :value="tipe_cabang.id">
                    {{ tipe_cabang.tipe_cabang }}
                  </option>
                </select>
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
        cabang: [],
        tipe_cabang: []
      },
      form: {
        create: {
          data: {
            kode_cabang: '',
            cabang: '',
            tipe_cabang_id: null,
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            kode_cabang: '',
            cabang: '',
            tipe_cabang_id: null,
          },
          errors: {}
        }
      },
      query: {
        cabang: {
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
          this.data.cabang = res[0].data.container
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
          this.data.cabang = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/cabang?q=' + this.query.cabang.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      this.$axios.get('/ajax/v1/master/tipe_cabang')
        .then(res => {
          this.data.tipe_cabang = res.data.container
          $('[data-entity="cabang"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/cabang/' + id)
        .then(res => {
          this.$axios.get('/ajax/v1/master/tipe_cabang')
            .then(res => {
              this.data.tipe_cabang = res.data.container
              this.form.update.data = {
                id: id,
                kode_cabang: res.data.container.kode_cabang,
                cabang: res.data.container.cabang,
                tipe_cabang: res.data.container.tipe_cabang_id
              }
              $('[data-entity="cabang"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/master/cabang', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kode_cabang: '',
            cabang: '',
            tipe_cabang: null
          }
          this.prepare()
          $('[data-entity="cabang"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/cabang', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            kode_cabang: '',
            cabang: '',
            tipe_cabang: null
          }
          this.prepare()
          $('[data-entity="cabang"][data-method="update"]').modal('hide')
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