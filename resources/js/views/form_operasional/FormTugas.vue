<template>
  <div class="row mt-5">
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
                      <select class="form-control" v-model="query.form_tugas.cabang_id" @change="queryData">
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
                    <select class="form-control" v-model="query.form_tugas.cabang_id" @change="queryData">
                      <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                        {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
              <button class="btn btn-primary"
                @click="showCreateModal"
                v-if="
                  $access('formOperasional.formTugas', 'create') && (
                    $access('formOperasional.formTugas.create', 'timeFree') ||
                    $moment($moment(query.form_tugas.tanggal_form)).isBetween(
                      $moment(utils.date).subtract($access('formOperasional.formTugas.create', 'dateMin')).format('YYYY-MM-DD'),
                      $moment(utils.date).add($access('formOperasional.formTugas.create', 'dateMax')).format('YYYY-MM-DD'),
                      undefined,
                      '[]'
                    )
                  )
                ">
                Tambah
              </button>
              <div v-if="$access('formOperasional.formTugas', 'read')">
                <div v-if="$access('formOperasional.formTugas.active', 'read') && data.form_tugas.cabang_aktif.length > 0">
                  <div class="card-title mt-5">BELUM LEWAT DEADLINE</div>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(form_tugas, i) in data.form_tugas.cabang_aktif">
                          <td>{{ i + 1 }}</td>
                          <td>{{ form_tugas.judul }}</td>
                          <td>{{ $moment(form_tugas.waktu_deadline).format('DD-MM-YYYY HH:mm:ss') }}</td>
                          <td>
                            <a class="badge badge-warning"
                              @click="showUpdateModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'update')">
                              Ubah
                            </a>
                            <a class="badge badge-danger"
                              @click="showDestroyModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'destroy')">
                              Hapus
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div v-if="$access('formOperasional.formTugas.passDeadline', 'read') && data.form_tugas.cabang_lewat_deadline.length > 0">
                  <div class="card-title mt-5 text-danger">LEWAT DEADLINE</div>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(form_tugas, i) in data.form_tugas.cabang_lewat_deadline">
                          <td>{{ i + 1 }}</td>
                          <td>{{ form_tugas.judul }}</td>
                          <td>{{ $moment(form_tugas.waktu_deadline).format('DD-MM-YYYY HH:mm:ss') }}</td>
                          <td>
                            <a class="badge badge-warning"
                              @click="showUpdateModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'update')">
                              Ubah
                            </a>
                            <a class="badge badge-danger"
                              @click="showDestroyModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'destroy')">
                              Hapus
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div v-if="$access('formOperasional.formTugas.waitingChecker', 'read') && data.form_tugas.cabang_belum_diperiksa.length > 0">
                  <div class="card-title mt-5">BELUM DIPERIKSA</div>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Pengumpulan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(form_tugas, i) in data.form_tugas.cabang_belum_diperiksa">
                          <td>{{ i + 1 }}</td>
                          <td>{{ form_tugas.judul }}</td>
                          <td>{{ $moment(form_tugas.waktu_deadline).format('DD-MM-YYYY HH:mm:ss') }}</td>
                          <td>{{ $moment(form_tugas.waktu_pengumpulan).format('DD-MM-YYYY HH:mm:ss') }}</td>
                          <td>
                            <span v-if="$moment(form_tugas.waktu_pengumpulan).isAfter(form_tugas.waktu_deadline)" class="text-danger">
                              TERLAMBAT MENGUMPULKAN
                            </span>
                            <span v-else class="text-success">
                              TEPAT WAKTU
                            </span>
                          </td>
                          <td>
                            <a class="badge badge-warning"
                              @click="showUpdateModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'update')">
                              Ubah
                            </a>
                            <a class="badge badge-danger"
                              @click="showDestroyModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'destroy')">
                              Hapus
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div v-if="$access('formOperasional.formTugas.checked', 'read') && data.form_tugas.cabang_sudah_diperiksa.length > 0">
                  <div class="card-title mt-5">SUDAH DIPERIKSA</div>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Deadline</th>
                        <th>Pengumpulan</th>
                        <th>Diperiksa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(form_tugas, i) in data.form_tugas.cabang_sudah_diperiksa">
                          <td>{{ i + 1 }}</td>
                          <td>{{ form_tugas.judul }}</td>
                          <td>{{ $moment(form_tugas.waktu_deadline).format('DD-MM-YYYY HH:mm:ss') }}</td>
                          <td>{{ $moment(form_tugas.waktu_pengumpulan).format('DD-MM-YYYY HH:mm:ss') }}</td>
                          <td>{{ $moment(form_tugas.waktu_diperiksa).format('DD-MM-YYYY HH:mm:ss') }}</td>
                          <td>
                            <span v-if="$moment(form_tugas.waktu_pengumpulan).isAfter(form_tugas.waktu_deadline)" class="text-danger">
                              TERLAMBAT MENGUMPULKAN
                            </span>
                            <span v-else class="text-success">
                              TEPAT WAKTU
                            </span>
                          </td>
                          <td>
                            <a class="badge badge-warning"
                              @click="showUpdateModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'update')">
                              Ubah
                            </a>
                            <a class="badge badge-danger"
                              @click="showDestroyModal(form_tugas.id)"
                              href="#"
                              v-if="$access('formOperasional.formTugas', 'destroy')">
                              Hapus
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
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade"
      data-entity="formFoto"
      data-method="create"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formTugas', 'create')
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Form Goreng</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-12 col-lg-4">
                  <label>Kode Cabang</label>
                  <input type="text" class="form-control" v-model="form.create.data.kode_cabang" readonly>
                </div>
                <div class="col-12 col-lg-8">
                  <label>Nama Cabang</label>
                  <input type="text" class="form-control" v-model="form.create.data.nama_cabang" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-12 col-lg-6">
                  <label>Tanggal Form</label>
                  <input type="date" class="form-control" readonly v-model="form.create.data.tanggal_form">
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.tanggal_form">
                    {{ msg }}
                  </small>
                </div>
                <div class="col-12 col-lg-6">
                  <label>Jam</label>
                  <input
                    type="time"
                    class="form-control"
                    v-model="form.create.data.jam"
                    :readonly="$access('formOperasional.formTugas.create', 'automatedTime')"
                    >
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.jam">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Karyawan</label>
                <select class="form-control" v-model="form.create.data.tugas_karyawan_id">
                  <option value="null">-- Pilih Karyawan --</option>
                  <option v-for="(tugas_karyawan, i) in data.tugas_karyawan" :value="tugas_karyawan.id">
                    {{ tugas_karyawan.karyawan.nip }} - {{ tugas_karyawan.karyawan.nama_karyawan }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.tugas_karyawan_id">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Kelompok Foto</label>
                <select class="form-control" v-model="form.create.data.kelompok_foto_id">
                  <option value="null">-- Pilih Kelompok Foto --</option>
                  <option v-for="(kelompok_foto, i) in data.kelompok_foto" :value="kelompok_foto.id">
                    {{ kelompok_foto.kelompok_foto }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.kelompok_foto_id">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Gambar</label>
                <webcam-component v-model="form.create.data.gambar" ref="webcam"></webcam-component>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.gambar">
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
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade"
      data-entity="formFoto"
      data-method="update"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formTugas', 'update')
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Form Goreng</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-12 col-lg-4">
                  <label>Kode Cabang</label>
                  <input type="text" class="form-control" v-model="form.update.data.kode_cabang" readonly>
                </div>
                <div class="col-12 col-lg-8">
                  <label>Nama Cabang</label>
                  <input type="text" class="form-control" v-model="form.update.data.nama_cabang" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-12 col-lg-6">
                  <label>Tanggal Form</label>
                  <input type="date" class="form-control" readonly v-model="form.update.data.tanggal_form">
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.tanggal_form">
                    {{ msg }}
                  </small>
                </div>
                <div class="col-12 col-lg-6">
                  <label>Jam</label>
                  <input
                    type="time"
                    class="form-control"
                    v-model="form.update.data.jam"
                    :readonly="$access('formOperasional.formTugas.update', 'readonlyTime')"
                    >
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.jam">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Karyawan</label>
                <select class="form-control" v-model="form.update.data.tugas_karyawan_id">
                  <option value="null">-- Pilih Karyawan --</option>
                  <option v-for="(tugas_karyawan, i) in data.tugas_karyawan" :value="tugas_karyawan.id">
                    {{ tugas_karyawan.karyawan.nip }} - {{ tugas_karyawan.karyawan.nama_karyawan }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, i) in form.update.errors.tugas_karyawan_id">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Kelompok Foto</label>
                <select class="form-control" v-model="form.update.data.kelompok_foto_id">
                  <option value="null">-- Pilih Kelompok Foto --</option>
                  <option v-for="(kelompok_foto, i) in data.kelompok_foto" :value="kelompok_foto.id">
                    {{ kelompok_foto.kelompok_foto }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, i) in form.update.errors.kelompok_foto_id">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group" v-if="$access('formOperasional.formTugas.update', 'takePhoto')">
                <label>Gambar</label>
                <webcam-component v-model="form.update.data.gambar" ref="webcam"></webcam-component>
                <small class="text-danger" v-for="(msg, i) in form.update.errors.gambar">
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
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Ubah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade"
      data-entity="formFoto"
      data-method="destroy"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formTugas', 'destroy')
      ">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="destroy">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Form Goreng</h5>
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
        cabang: [],
        form_tugas: {
          cabang_aktif: [],
          cabang_lewat_deadline: [],
          cabang_belum_diperiksa: [],
          cabang_sudah_diperiksa: []
        },
        tugas_karyawan: [],
        kelompok_foto: []
      },
      form: {
        create: {
          data: {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            kelompok_foto_id: null,
            kelompok_foto: '',
            keterangan: '',
            gambar: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            kelompok_foto_id: null,
            kelompok_foto: '',
            keterangan: '',
            gambar: ''
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
      query: {
        form_tugas: {
          cabang_id: this.$route.query.cabang_id || null
        }
      },
      interval: {
        form: {
          create: {
            data: {
              jam: null
            }
          },
          update: {
            data: {
              jam: null
            }
          }
        },
        utils: {
          date: null
        }
      },
      utils: {
        date: this.$moment().format('YYYY-MM-DD')
      }
    }
  },
  created() {
    this.prepare()
  },
  mounted() {
    this.setDateWatcher()

    if (this.$access('formOperasional.formTugas', 'create') && this.$access('formOperasional.formTugas.create', 'automatedTime')) {
      this.setCreateClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.create.data.jam)
  },

  watch: {
    'query.form_tugas.tanggal_form'(n, o) {
      if (this.$access('formOperasional.formTugas.read', 'timeFree')) {
        this.query.form_tugas.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.formTugas.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.formTugas.read', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        ) {
          this.query.form_tugas.tanggal_form = n
        } else {
          this.query.form_tugas.tanggal_form = o
        }
      }
    }
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

          if (this.query.form_tugas.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.form_tugas.cabang_id }))) {
            this.query.form_tugas.cabang_id = this.data.cabang[0].id || null
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.form_tugas) && this.$route.name === 'formOperasional.formTugas') {
            this.$router.push({
              name: 'formOperasional.formTugas',
              query: this.query.form_tugas
            })
          }
          this.data.form_tugas = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_tugas/cabang', { params: this.query.form_tugas })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchTugasKaryawan(id, tanggal_penugasan) {
      return this.$axios.get('/ajax/v1/tugas_karyawan/cabang/?cabang_id=' + id + '&tanggal_penugasan=' + tanggal_penugasan)
    },

    /**
     *  Modal functionality & utils
     */
    showCreateModal() {
      Promise.all([
        this.fetchTugasKaryawan(this.query.form_tugas.cabang_id, this.query.form_tugas.tanggal_penugasan),
        this.fetchKelompokFoto()
      ])
        .then(res => {
          this.data.tugas_karyawan = res[0].data.container
          this.data.kelompok_foto = res[1].data.container

          this.form.create.data.tanggal_form = this.query.form_tugas.tanggal_form
          let currentCabang = this.$_.findWhere(this.data.cabang, {id: parseInt(this.query.form_tugas.cabang_id)})
          this.form.create.data.kode_cabang = currentCabang.kode_cabang
          this.form.create.data.nama_cabang = currentCabang.cabang

          $('[data-entity="formFoto"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/form_operasional/form_tugas/' + id)
        .then(res => {
          let currentCabang = this.$_.findWhere(this.data.cabang, {id: parseInt(this.query.form_tugas.cabang_id)})
          this.form.update.data = {
            id: id,
            kode_cabang: currentCabang.kode_cabang,
            nama_cabang: currentCabang.cabang,
            tanggal_form: this.query.form_tugas.tanggal_form,
            jam: res.data.container.jam,
            tugas_karyawan_id: res.data.container.tugas_karyawan_id,
            kelompok_foto_id: res.data.container.kelompok_foto_id,
            keterangan: res.data.container.keterangan
          }
          Promise.all([
            this.fetchTugasKaryawan(this.query.form_tugas.cabang_id, this.query.form_tugas.tanggal_penugasan),
            this.fetchKelompokFoto()
          ])
            .then(res => {
              this.data.tugas_karyawan = res[0].data.container
              this.data.kelompok_foto = res[1].data.container

              $('[data-entity="formFoto"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
        .catch(err => {})
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="formFoto"][data-method="destroy"]').modal('show')
    },
    hideCreateModal() {
      $('[data-entity="formFoto"][data-method="create"]').modal('hide')
    },
    hideUpdateModal() {
      $('[data-entity="formFoto"][data-method="update"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="formFoto"][data-method="destroy"]').modal('hide')
    },
    setCreateClockInterval() {
      this.interval.form.create.data.jam = setInterval(function () {
        this.form.create.data.jam = this.$moment().format('HH:mm:ss')
      }.bind(this), 1000)
    },
    setDateWatcher() {
      this.interval.utils.date = setInterval(function () {
        let o = this.utils.date
        let n = this.$moment().format('YYYY-MM-DD')

        if ( ! this.$moment(n).isSame(o)) {
          this.dateChangeHandler()
        }

        this.utils.date = n
      }.bind(this), 1000)
    },
    dateChangeHandler() {
      // Handle date change on read action
      if (this.$access('formOperasional.formTugas', 'read')) {
        if (this.$access('formOperasional.formTugas.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formTugas.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formTugas.read', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.query.form_tugas.tanggal_form = this.$moment().format('YYYY-MM-DD')
            this.queryData()
          }
        }
      }
      // Handle date change on create action
      if (this.$access('formOperasional.formTugas', 'create')) {
        if (this.$access('formOperasional.formTugas.create', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formTugas.create', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formTugas.create', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.hideCreateModal()
          }
        }
      }
      // Handle data change on update action
      if (this.$access('formOperasional.formTugas', 'update')) {
        if (this.$access('formOperasional.formTugas.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formTugas.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formTugas.update', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.hideUpdateModal()
          }
        }
      }
      // Handle data change on destroy action
      if (this.$access('formOperasional.formTugas', 'destroy')) {
        if (this.$access('formOperasional.formTugas.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formTugas.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formTugas.destroy', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.hideDestroyModal()
          }
        }
      } 
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/form_operasional/form_tugas', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            kelompok_foto_id: null,
            kelompok_foto: '',
            keterangan: '',
            gambar: ''
          }
          this.queryData(false)
          this.$refs.webcam.reset()
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
      this.$axios.put('/ajax/v1/form_operasional/form_tugas', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            kelompok_foto_id: null,
            kelompok_foto: '',
            keterangan: '',
            gambar: ''
          }
          this.queryData(false)
          this.$refs.webcam.reset()
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
      this.$axios.delete('/ajax/v1/form_operasional/form_tugas', { data: this.form.destroy.data })
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