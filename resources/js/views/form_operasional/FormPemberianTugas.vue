<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name == 'formOperasional.formPemberianTugas'">
      <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <transition name="fade" mode="out-in">
              <preloader-component v-if="state.page.loading"/>
              <div v-else>
                <button class="btn btn-primary"
                  @click="showCreateModal"
                  v-if="$access('formOperasional.formPemberianTugas', 'create')">
                  Tambah
                </button>
                <div class="table-responsive mt-2">
                  <table class="table table-hover" v-if="$access('formOperasional.formPemberianTugas', 'read')">
                    <thead>
                      <th>#</th>
                      <th>Judul Tugas</th>
                      <th>Keterangan</th>
                      <th>Waktu Mulai</th>
                      <th>Waktu Deadline</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(form_pemberian_tugas, i) in data.form_pemberian_tugas">
                        <td>{{ i + 1 }}</td>
                        <td>{{ form_pemberian_tugas.judul_tugas }}</td>
                        <td>{{ form_pemberian_tugas.keterangan }}</td>
                        <td>{{ form_pemberian_tugas.waktu_mulai }}</td>
                        <td>{{ form_pemberian_tugas.waktu_deadline }}</td>
                        <td>
                          <router-link
                            class="badge badge-info"
                            :to="{ name: 'formOperasional.formPemberianTugas.detail', params: { id: form_pemberian_tugas.id } }"
                            v-if="$access('formOperasional.formPemberianTugas.detail', 'access')"
                            >
                            Detail
                          </router-link>
                          <a class="badge badge-warning"
                            @click="showUpdateModal(form_pemberian_tugas.id)"
                            href="#"
                            v-if="$access('formOperasional.formPemberianTugas', 'update')">
                            Ubah
                          </a>
                          <a class="badge badge-danger"
                            @click="showDestroyModal(form_pemberian_tugas.id)"
                            href="#"
                            v-if="$access('formOperasional.formPemberianTugas', 'destroy')">
                            Hapus
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade"
        data-entity="formPemberianTugas"
        data-method="create"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        v-if="$access('formOperasional.formPemberianTugas', 'create')">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Form Pemberian Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Judul Tugas</label>
                  <input type="text" class="form-control" v-model="form.create.data.judul_tugas">
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.judul_tugas">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control form-control-sm" v-model="form.create.data.keterangan"></textarea>
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.keterangan">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Waktu Mulai</label>
                  <div class="input-group">
                    <input type="date" class="form-control" v-model="form.create.data.tanggal_mulai">
                    <input type="time" class="form-control" v-model="form.create.data.jam_mulai">
                  </div>
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.waktu_mulai">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Waktu Deadline</label>
                  <div class="input-group">
                    <input type="date" class="form-control" v-model="form.create.data.tanggal_deadline">
                    <input type="time" class="form-control" v-model="form.create.data.jam_deadline">
                  </div>
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.waktu_deadline">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Cabang Ditugaskan</label>
                  <div class="form-check">
                    <input class="form-check-input m-0" type="checkbox" v-model="form.create.data.semua_cabang">
                    <label class="form-check-label">
                      Semua Cabang
                    </label>
                  </div>
                </div>
                <transition name="fade" mode="out-in">
                  <div class="form-group ml-3" v-if="!form.create.data.semua_cabang">
                    <div class="form-check" v-for="(cabang, i) in data.cabang">
                      <input class="form-check-input m-0" type="radio" :value="cabang.id" v-model="form.create.data.cabang_id[i]">
                      <label class="form-check-label">
                        {{ cabang.cabang }}
                      </label>
                    </div>
                  </div>
                </transition>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Tambah</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade"
        data-entity="formPemberianTugas"
        data-method="update"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        v-if="$access('formOperasional.formPemberianTugas', 'update')">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form @submit.prevent="update">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Form Pemberian Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Judul Tugas</label>
                  <input type="text" class="form-control" v-model="form.update.data.judul_tugas">
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.judul_tugas">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control form-control-sm" v-model="form.update.data.keterangan"></textarea>
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.keterangan">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Waktu Mulai</label>
                  <div class="input-group">
                    <input type="date" class="form-control" v-model="form.update.data.tanggal_mulai">
                    <input type="time" class="form-control" v-model="form.update.data.jam_mulai">
                  </div>
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.waktu_mulai">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Waktu Deadline</label>
                  <div class="input-group">
                    <input type="date" class="form-control" v-model="form.update.data.tanggal_deadline">
                    <input type="time" class="form-control" v-model="form.update.data.jam_deadline">
                  </div>
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.waktu_deadline">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Cabang Ditugaskan</label>
                  <div class="form-check">
                    <input class="form-check-input m-0" type="checkbox" v-model="form.update.data.semua_cabang">
                    <label class="form-check-label">
                      Semua Cabang
                    </label>
                  </div>
                </div>
                <transition name="fade" mode="out-in">
                  <div class="form-group ml-3" v-if="!form.update.data.semua_cabang">
                    <div class="form-check" v-for="(cabang, i) in data.cabang">
                      <input class="form-check-input m-0" type="radio" :value="cabang.id" v-model="form.update.data.cabang_id[i]">
                      <label class="form-check-label">
                        {{ cabang.cabang }}
                      </label>
                    </div>
                  </div>
                </transition>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Ubah</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade"
        data-entity="formPemberianTugas"
        data-method="destroy"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        v-if="$access('formOperasional.formPemberianTugas', 'destroy')"
        >
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="destroy">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Form Pemberian Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Apakah anda yakin?</p>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.destroy.loading">
                  <spinner-component size="sm" color="light" v-if="form.destroy.loading"/>
                  Hapus
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
      state: {
        page: { loading: false },
        table: { loading: false }
      },
      data: {
        form_pemberian_tugas: [],
        cabang: []
      },
      form: {
        create: {
          data: {
            judul_tugas: '',
            keterangan: '',
            tanggal_mulai: '',
            jam_mulai: '',
            tanggal_deadline: '',
            jam_deadline: '',
            cabang_id: [],
            semua_cabang: false
          },
          errors: {},
          loading: false,
        },
        update: {
          data: {
            id: null,
            judul_tugas: '',
            keterangan: '',
            tanggal_mulai: '',
            jam_mulai: '',
            tanggal_deadline: '',
            jam_deadline: '',
            cabang_id: [],
            semua_cabang: false
          },
          errors: {},
          loading: false
        },
        destroy: {
          data: {
            id: null
          },
          errors: {},
          loading: false
        }
      },
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
        this.fetchCabang()
      ])
        .then(res => {
          this.queryData()
          this.state.page.loading = false
        })
        .catch(err => { console.log(err)
          this.$router.go(-1)
        }) 
    },

    /**
     *  Query result.
     */
    queryData(withSpinner = true) {
      if (withSpinner) this.state.table.loading = true
      this.fetchMainData()
        .then(res => {
          this.data.form_pemberian_tugas = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_pemberian_tugas')
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },

    /**
     *  Modal functionality & utils
     */
    showCreateModal() {
      Promise.all([
        this.fetchCabang()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          $('[data-entity="formPemberianTugas"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/form_operasional/form_pemberian_tugas/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            judul_tugas: res.data.container,
            keterangan: res.data.container,
            tanggal_mulai: res.data.container,
            jam_mulai: res.data.container,
            tanggal_deadline: res.data.container,
            jam_deadline: res.data.container,
            cabang_id: res.data.container.form_pemberian_tugas_cabang,
            semua_cabang: false
          }
          Promise.all([
            this.fetchCabang()
          ])
            .then(res => {
              this.data.cabang = res[0].data.container

              $('[data-entity="formPemberianTugas"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
        .catch(err => {})
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="formPemberianTugas"][data-method="destroy"]').modal('show')
    },
    hideCreateModal() {
      $('[data-entity="formPemberianTugas"][data-method="create"]').modal('hide')
    },
    hideUpdateModal() {
      $('[data-entity="formPemberianTugas"][data-method="update"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="formPemberianTugas"][data-method="destroy"]').modal('hide')
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}

      this.form.create.data.waktu_mulai = this.form.create.data.tanggal_mulai + ' ' + this.form.create.data.jam_mulai
      this.form.create.data.waktu_deadline = this.form.create.data.tanggal_deadline + ' ' + this.form.create.data.jam_deadline
      this.$axios.post('/ajax/v1/form_operasional/form_pemberian_tugas', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            judul_tugas: '',
            keterangan: '',
            tanggal_mulai: '',
            jam_mulai: '',
            tanggal_deadline: '',
            jam_deadline: '',
            cabang_id: [],
            semua_cabang: false
          }
          this.queryData(false)
          this.hideCreateModal()
        })
        .catch(err => {
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

      this.form.update.data.waktu_mulai = this.form.update.data.tanggal_mulai + ' ' + this.form.update.data.jam_mulai
      this.form.update.data.waktu_deadline = this.form.update.data.tanggal_deadline + ' ' + this.form.update.data.jam_deadline
      this.$axios.put('/ajax/v1/form_operasional/form_pemberian_tugas', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            judul_tugas: '',
            keterangan: '',
            tanggal_mulai: '',
            jam_mulai: '',
            tanggal_deadline: '',
            jam_deadline: '',
            cabang_id: [],
            semua_cabang: false
          }
          this.queryData(false)
          this.hideUpdateModal()
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
    destroy() {
      this.form.destroy.loading = true
      this.form.destroy.errors = {}
      this.$axios.delete('/ajax/v1/form_operasional/form_pemberian_tugas', { data: this.form.destroy.data })
        .then(res => {
          this.form.destroy.data.id = null
          this.queryData(false)
          this.hideDestroyModal()
        })
        .catch(err => {})
        .finally(() => {
          this.form.destroy.loading = false
        })
    },
  }
}
</script>