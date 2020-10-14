<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name == 'formOperasional.formPengumpulanTugas'">
      <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
          <div class="card-body">
            <transition name="fade" mode="out-in">
              <preloader-component v-if="state.page.loading"/>
              <div v-else>
                <!-- If the user uses laptop or tablet -->
                <div class="row d-none d-md-block">
                  <div class="col-12">
                    <div class="form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Cabang
                          </span>
                        </div>
                        <select class="form-control" v-model="query.form_pemberian_tugas.cabang_id" @change="queryData">
                          <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                            {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- else -->
                <div class="row d-md-none">
                  <div class="col-12">
                    <div class="form-group">
                      <label>Cabang</label>
                      <select class="form-control" v-model="query.form_pemberian_tugas.cabang_id" @change="queryData">
                        <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                          {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="table-responsive mt-2">
                  <table class="table table-hover" v-if="$access('formOperasional.formPengumpulanTugas', 'read')">
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
                          <a class="badge badge-info"
                            @click="showCreateModal(form_pemberian_tugas.id)"
                            href="#"
                            v-if="$access('formOperasional.formPengumpulanTugas', 'create')">
                            Kumpulkan
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
        data-entity="formPengumpulanTugas"
        data-method="create"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        v-if="$access('formOperasional.formPengumpulanTugas', 'create')">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Form Pengumpulan Tugas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control form-control-sm" v-model="form.create.data.keterangan"></textarea>
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.keterangan">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>File</label>
                  <input type="file" ref="file" @input="handleCreateFile">
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.create.loading">
                  <spinner-component size="sm" v-if="form.create.loading"/>
                  Tambah
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
            form_pemberian_tugas_id: null,
            cabang_id: null,
            keterangan: '',
            file: null
          },
          errors: {},
          loading: false,
        },
      },
      query: {
        form_pemberian_tugas: {
          cabang_id: this.$route.params.cabang_id || null
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
        this.fetchCabang()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container

          if (this.data.cabang.length <= 0) {
            this.$router.go(-1)
          }

          if (this.query.form_pemberian_tugas.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.form_pemberian_tugas.cabang_id }))) {
            this.query.form_pemberian_tugas.cabang_id = this.data.cabang[0].id || null
          }

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
          if ( ! this.$_.isEqual(this.$route.query, this.query.form_pemberian_tugas) && this.$route.name === 'formOperasional.formPengumpulanTugas') {
            this.$router.push({
              name: 'formOperasional.formPengumpulanTugas',
              query: this.query.form_pemberian_tugas
            })
          }
          this.data.form_pemberian_tugas = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_pemberian_tugas/active_cabang', { params: this.query.form_pemberian_tugas })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },

    /**
     *  Modal functionality & utils
     */
    showCreateModal(id) {
      this.form.create.data.form_pemberian_tugas_id = id
      this.form.create.data.cabang_id = this.$route.query.cabang_id
      $('[data-entity="formPengumpulanTugas"][data-method="create"]').modal('show')
    },
    hideCreateModal() {
      $('[data-entity="formPengumpulanTugas"][data-method="create"]').modal('hide')
    },
    handleCreateFile(e) {
      this.form.create.data.file = this.$refs.file.files[0]
      this.$emit('input', e.target.files[0])
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}

      let formData = new FormData()
      formData.append('form_pemberian_tugas_id', this.form.create.data.form_pemberian_tugas_id)
      formData.append('cabang_id', this.form.create.data.cabang_id)
      formData.append('keterangan', this.form.create.data.keterangan)
      formData.append('file', this.form.create.data.file)
      this.$axios.post('/ajax/v1/form_operasional/form_pengumpulan_tugas',
        formData,
        { headers: { 'Content-Type': 'multipart/form-data' } }
      )
        .then(res => {
          this.form.create.data = {
            form_pemberian_tugas_id: null,
            cabang_id: null,
            keterangan: '',
            file: null
          }
          this.$refs.file.type = 'text'
          this.$refs.file.type = 'file'
          this.queryData(false)
          this.hideCreateModal()
        })
        .catch(err => { console.log(err.response)
          if (err.response.status == 422) {
            this.form.create.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.create.loading = false
        })
    },
  }
}
</script>