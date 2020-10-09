<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'absensi'">
      <transition name="fade" mode="out-in">
        <preloader-component v-if="state.page.loading"/>
        <div class="col-12 col-xl-12 stretch-card" v-else>
          <div class="card">
            <div class="card-body">
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
                      <select class="form-control" v-model="query.absensi.cabang_id" @change="queryData">
                        <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                          {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Tipe Absensi
                        </span>
                      </div>
                      <select class="form-control" v-model="query.absensi.tipe_absensi_id" @change="queryData">
                        <option v-for="(tipe_absensi, i) in data.tipe_absensi" :key="i" :value="tipe_absensi.id">
                          {{ tipe_absensi.tipe_absensi }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Tanggal Absensi
                        </span>
                      </div>
                      <input type="date" class="form-control" v-model="query.absensi.tanggal_absensi" @keyup="queryData">
                    </div>
                  </div>
                </div>
              </div>
              <!-- else -->
              <div class="row d-md-none">
                <div class="col-12">
                  <div class="form-group">
                    <label>Cabang</label>
                    <select class="form-control" v-model="query.absensi.cabang_id" @change="queryData">
                      <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                        {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Tipe Absensi</label>
                    <select class="form-control" v-model="query.absensi.tipe_absensi_id" @change="queryData">
                      <option v-for="(tipe_absensi, i) in data.tipe_absensi" :key="i" :value="tipe_absensi.id">
                        {{ tipe_absensi.tipe_absensi }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Tanggal Absensi</label>
                    <input type="date" class="form-control" v-model="query.absensi.tanggal_absensi" @keyup="queryData">
                  </div>
                </div>
              </div>
              <router-link class="btn btn-secondary mt-2" v-if="$access('absensi.imporJadwal', 'access')" :to="{ name: 'absensi.imporJadwal' }">
                <i class="far fa-file-import mr-1 fa-xs"></i>
                Impor Jadwal
              </router-link>
              <router-link class="btn btn-secondary mt-2" v-if="$access('absensi.imporAbsensi', 'access')" :to="{ name: 'absensi.imporAbsensi' }">
                <i class="far fa-file-import mr-1 fa-xs"></i>
                Impor Absensi
              </router-link>
              <div class="table-responsive mt-2">
                <table class="table table-hover" v-if="$access('absensi', 'read')">
                  <thead>
                    <th>#</th>
                    <th>NIP</th>
                    <th>Nama Karyawan</th>
                    <th>No. Finger</th>
                    <th>Status Foto KTP</th>
                    <th>Jam Jadwal Diajukan</th>
                    <th>Jam Jadwal</th>
                    <th>Jam Absen</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <template v-if="state.table.loading">
                      <tr>
                        <td class="text-center" colspan="8">
                          <spinner-component/>
                        </td>
                      </tr>
                    </template>
                    <template v-else>
                      <tr v-for="(absensi, i) in data.absensi">
                        <td>{{ i + 1 }}</td>
                        <td>{{ absensi.karyawan.nip }}</td>
                        <td>
                          <router-link :to="{ name: 'karyawan.profil', params: { id: absensi.karyawan.id } }" v-if="$access('karyawan.profil', 'access')">
                            {{ absensi.karyawan.nama_karyawan }}
                          </router-link>
                          <span v-else>
                            {{ absensi.karyawan.nama_karyawan }}
                          </span>
                        </td>
                        <td>{{ absensi.no_finger }}</td>
                        <td>
                          <span :class="{ 'text-success': absensi.karyawan.foto_ktp_id != null, 'text-danger': absensi.karyawan.foto_ktp_id == null }">
                            <span v-if="absensi.karyawan.foto_ktp_id != null">SUDAH UPLOAD</span>
                            <span v-else>BELUM UPLOAD</span>
                          </span>
                        </td>
                        <td>
                          {{
                            absensi.pengajuan_jadwal_absensi[0] == null
                              ? ''
                              : (
                                absensi.pengajuan_jadwal_absensi[0].jam_jadwal == null 
                                  ? '-'
                                  : absensi.pengajuan_jadwal_absensi[0].jam_jadwal
                              )
                          }}
                        </td>
                        <td>
                          {{
                            absensi.absensi[0] == null
                              ? ''
                              : (
                                absensi.absensi[0].jam_jadwal == null
                                  ? '-'
                                  : absensi.absensi[0].jam_jadwal
                              )
                          }}
                        </td>
                        <td>
                          {{
                            absensi.absensi[0] == null
                              ? ''
                              : (
                                absensi.absensi[0].jam_absen == null
                                  ? '-'
                                  : absensi.absensi[0].jam_absen
                              )
                          }}
                        </td>
                        <td>
                          <span v-if="absensi.absensi.length == 0">
                            <a class="badge badge-primary" @click="showCreateModal(absensi.id)" href="#" v-if="$access('absensi', 'create')">
                              Tambah
                            </a>
                          </span>
                          <span v-else>
                            <a class="badge badge-warning" @click="showUpdateModal(absensi.absensi[0].id)" href="#" v-if="$access('absensi', 'update')">
                              Ubah
                            </a>
                            <a class="badge badge-danger" @click="showDestroyModal(absensi.absensi[0].id)" href="#" v-if="$access('absensi', 'destroy')">
                              Hapus
                            </a>
                          </span>

                          <span v-if="absensi.pengajuan_jadwal_absensi.length == 0">
                            <a class="badge badge-light" @click="showCreatePengajuanModal(absensi.id)" href="#" v-if="$access('absensi.pengajuanJadwalAbsensi', 'create')">
                              Ajukan Jam Jadwal
                            </a>
                          </span>
                          <span v-else>
                            <a class="badge badge-light" @click="showAcceptPengajuanModal(absensi.pengajuan_jadwal_absensi[0].id)" href="#" v-if="$access('absensi.pengajuanJadwalAbsensi', 'accept')">
                              Terima Pengajuan
                            </a>
                            <a class="badge badge-danger" @click="showDestroyPengajuanModal(absensi.pengajuan_jadwal_absensi[0].id)" href="#" v-if="$access('absensi.pengajuanJadwalAbsensi', 'destroy')">
                              Hapus Pengajuan
                            </a>
                          </span>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </transition>

      <!-- Modal -->
      <div class="modal fade" data-entity="absensi" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>NIP</label>
                  <input type="text" class="form-control" readonly v-model="form.create.data.nip">
                </div>
                <div class="form-group">
                  <label>Nama Karyawan</label>
                  <input type="text" class="form-control" readonly v-model="form.create.data.nama_karyawan">
                </div>
                <div class="form-group row">
                  <div class="col-12 col-lg-4">
                    <label>Kode Cabang</label>
                    <input type="text" class="form-control" readonly v-model="form.create.data.kode_cabang">
                  </div>
                  <div class="col-12 col-lg-8">
                    <label>Nama Cabang</label>
                    <input type="text" class="form-control" readonly v-model="form.create.data.nama_cabang">
                  </div>
                </div>
                <div class="form-group">
                  <label>Tanggal Absensi</label>
                  <input type="date" class="form-control" readonly v-model="form.create.data.tanggal_absensi">
                </div>
                <div class="form-group">
                  <label>Tipe Absensi</label>
                  <input text="date" class="form-control" readonly v-model="form.create.data.tipe_absensi">
                </div>
                <div class="form-group">
                  <label>Jam Jadwal</label>
                  <input type="time" class="form-control" v-model="form.create.data.jam_jadwal">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.jam_jadwal" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Jam Absen</label>
                  <input type="time" class="form-control" v-model="form.create.data.jam_absen">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.jam_absen" :key="index">
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
      <div class="modal fade" data-entity="absensi" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="update">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>NIP</label>
                  <input type="text" class="form-control" readonly v-model="form.update.data.nip">
                </div>
                <div class="form-group">
                  <label>Nama Karyawan</label>
                  <input type="text" class="form-control" readonly v-model="form.update.data.nama_karyawan">
                </div>
                <div class="form-group row">
                  <div class="col-12 col-lg-4">
                    <label>Kode Cabang</label>
                    <input type="text" class="form-control" readonly v-model="form.update.data.kode_cabang">
                  </div>
                  <div class="col-12 col-lg-8">
                    <label>Nama Cabang</label>
                    <input type="text" class="form-control" readonly v-model="form.update.data.nama_cabang">
                  </div>
                </div>
                <div class="form-group">
                  <label>Tanggal Absensi</label>
                  <input type="date" class="form-control" readonly v-model="form.update.data.tanggal_absensi">
                </div>
                <div class="form-group">
                  <label>Tipe Absensi</label>
                  <input text="date" class="form-control" readonly v-model="form.update.data.tipe_absensi">
                </div>
                <div class="form-group">
                  <label>Jam Jadwal</label>
                  <input type="time" class="form-control" v-model="form.update.data.jam_jadwal">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.jam_jadwal" :key="index">
                    {{ msg }}
                  </small>
                </div>
                <div class="form-group">
                  <label>Jam Absen</label>
                  <input type="time" class="form-control" v-model="form.update.data.jam_absen">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.jam_absen" :key="index">
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
      <div class="modal fade" data-entity="absensi" data-method="destroy" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="destroy">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Absensi</h5>
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
      <div class="modal fade" data-entity="pengajuanJadwalAbsensi" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="createPengajuan">
              <div class="modal-header">
                <h5 class="modal-title">Pengajuan Jadwal Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>NIP</label>
                  <input type="text" class="form-control" readonly v-model="form.pengajuan_jadwal_absensi.create.data.nip">
                </div>
                <div class="form-group">
                  <label>Nama Karyawan</label>
                  <input type="text" class="form-control" readonly v-model="form.pengajuan_jadwal_absensi.create.data.nama_karyawan">
                </div>
                <div class="form-group row">
                  <div class="col-12 col-lg-4">
                    <label>Kode Cabang</label>
                    <input type="text" class="form-control" readonly v-model="form.pengajuan_jadwal_absensi.create.data.kode_cabang">
                  </div>
                  <div class="col-12 col-lg-8">
                    <label>Nama Cabang</label>
                    <input type="text" class="form-control" readonly v-model="form.pengajuan_jadwal_absensi.create.data.nama_cabang">
                  </div>
                </div>
                <div class="form-group">
                  <label>Tanggal Absensi</label>
                  <input type="date" class="form-control" readonly v-model="form.pengajuan_jadwal_absensi.create.data.tanggal_absensi">
                </div>
                <div class="form-group">
                  <label>Tipe Absensi</label>
                  <input text="date" class="form-control" readonly v-model="form.pengajuan_jadwal_absensi.create.data.tipe_absensi">
                </div>
                <div class="form-group">
                  <label>Jam Jadwal</label>
                  <input type="time" class="form-control" v-model="form.pengajuan_jadwal_absensi.create.data.jam_jadwal">
                  <small class="text-danger" v-for="(msg, index) in form.pengajuan_jadwal_absensi.create.errors.jam_jadwal" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.pengajuan_jadwal_absensi.create.loading">
                  <spinner-component size="sm" color="light" v-if="form.pengajuan_jadwal_absensi.create.loading"/>
                  Ajukan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" data-entity="pengajuanJadwalAbsensi" data-method="accept" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="acceptPengajuan">
              <div class="modal-header">
                <h5 class="modal-title">Terima Pengajuan Jadwal Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Apakah anda yakin?</p>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.pengajuan_jadwal_absensi.accept.loading">
                  <spinner-component size="sm" color="light" v-if="form.pengajuan_jadwal_absensi.accept.loading"/>
                  Terima
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" data-entity="pengajuanJadwalAbsensi" data-method="destroy" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="destroyPengajuan">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Pengajuan Jadwal Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Apakah anda yakin?</p>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.pengajuan_jadwal_absensi.destroy.loading">
                  <spinner-component size="sm" color="light" v-if="form.pengajuan_jadwal_absensi.destroy.loading"/>
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
      state: { page: { loading: true }, table: { loading: false } },
      data: {
        absensi: [],
        cabang: [],
        tipe_absensi: []
      },
      form: {
        create: {
          data: {
            tugas_karyawan_id: null,
            tipe_absensi_id: null,
            tanggal_absensi: '',
            jam_jadwal: '',
            jam_absen: '',

            nip: '',
            nama_karyawan: '',
            kode_cabang: '',
            nama_cabang: '',
            tipe_absensi: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            tanggal_absensi: '',
            jam_jadwal: '',
            jam_absen: '',

            nip: '',
            nama_karyawan: '',
            kode_cabang: '',
            nama_cabang: '',
            tipe_absensi: ''
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
        },
        pengajuan_jadwal_absensi: {
          create: {
            data: {
              tugas_karyawan_id: null,
              tipe_absensi_id: null,
              tanggal_absensi: '',
              jam_jadwal: '',

              nip: '',
              nama_karyawan: '',
              kode_cabang: '',
              nama_cabang: '',
              tipe_absensi: ''
            },
            errors: {},
            loading: false
          },
          accept: {
            data: {
              id: null
            },
            errors: {}
          },
          destroy: {
            data: {
              id: null
            },
            errors: {},
            loading: false
          }
        }
      },
      query: {
        absensi: {
          cabang_id: this.$route.query.cabang_id || null,
          tipe_absensi_id: this.$route.query.tipe_absensi_id || null,
          tanggal_absensi: this.$route.query.tanggal_absensi || this.$moment().format('YYYY-MM-DD')
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
      // this.state.page.loading = true
      Promise.all([
        this.fetchCabang(),
        this.fetchTipeAbsensi()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.tipe_absensi = res[1].data.container

          if (this.data.cabang.length <= 0 || this.data.tipe_absensi <= 0) {
            this.$router.go(-1)
          }

          if (this.query.absensi.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.absensi.cabang_id }))) {
            this.query.absensi.cabang_id = this.data.cabang[0].id || null
          }
          if (this.query.absensi.tipe_absensi_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.tipe_absensi, { id: this.query.absensi.tipe_absensi_id }))) {
            this.query.absensi.tipe_absensi_id = this.data.tipe_absensi[0].id || null
          }

          this.queryData()
          this.state.page.loading = false
        })
        .catch(err => {
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.absensi) && this.$route.name === 'absensi') {
            this.$router.push({
              name: 'absensi',
              query: this.query.absensi
            })
          }
          this.data.absensi = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/absensi/manual', { params: this.query.absensi })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchTipeAbsensi() {
      return this.$axios.get('/ajax/v1/master/tipe_absensi')
    },

    /**
     *  Modal functionality
     */
    showCreateModal(id) {
      this.$axios.get('/ajax/v1/tugas_karyawan/' + id)
        .then(res => {
          this.form.create.data.tugas_karyawan_id = id
          this.form.create.data.tipe_absensi_id = this.query.absensi.tipe_absensi_id
          this.form.create.data.tanggal_absensi = this.query.absensi.tanggal_absensi
          this.form.create.data.nip = res.data.container.karyawan.nip
          this.form.create.data.nama_karyawan = res.data.container.karyawan.nama_karyawan
          this.form.create.data.kode_cabang = res.data.container.cabang.kode_cabang
          this.form.create.data.nama_cabang = res.data.container.cabang.cabang
          Promise.all([
            this.$axios.get('/ajax/v1/master/tipe_absensi/' + this.query.absensi.tipe_absensi_id)
          ])
            .then(res => {
              this.form.create.data.tipe_absensi = res[0].data.container.tipe_absensi
              $('[data-entity="absensi"][data-method="create"]').modal('show')
            })
            .catch(err => console.log(err))
        })
        .catch(err => { console.log(err)

        })
    },
    showUpdateModal(id) {
      this.$axios.get('/ajax/v1/absensi/' + id)
        .then(res => {
          this.form.update.data.id = id
          this.form.update.data.tipe_absensi_id = this.query.tipe_absensi_id
          this.form.update.data.tanggal_absensi = this.query.tanggal_absensi
          this.form.update.data.nip = res.data.container.tugas_karyawan.karyawan.nip
          this.form.update.data.nama_karyawan = res.data.container.tugas_karyawan.karyawan.nama_karyawan
          this.form.update.data.kode_cabang = res.data.container.tugas_karyawan.cabang.kode_cabang
          this.form.update.data.nama_cabang = res.data.container.tugas_karyawan.cabang.cabang
          this.form.update.data.tipe_absensi = res.data.container.tipe_absensi.tipe_absensi
          this.form.update.data.jam_jadwal = res.data.container.jam_jadwal
          this.form.update.data.jam_absen = res.data.container.jam_absen
          $('[data-entity="absensi"][data-method="update"]').modal('show')
        })
    },
    showDestroyModal(id) {
      this.$axios.get('/ajax/v1/absensi/' + id)
        .then(res => {
          this.form.destroy.data.id = id
          $('[data-entity="absensi"][data-method="destroy"]').modal('show')
        })
    },
    showCreatePengajuanModal(id) {
      this.$axios.get('/ajax/v1/tugas_karyawan/' + id)
        .then(res => {
          this.form.pengajuan_jadwal_absensi.create.data.tugas_karyawan_id = id
          this.form.pengajuan_jadwal_absensi.create.data.tipe_absensi_id = this.query.absensi.tipe_absensi_id
          this.form.pengajuan_jadwal_absensi.create.data.tanggal_absensi = this.query.absensi.tanggal_absensi
          this.form.pengajuan_jadwal_absensi.create.data.nip = res.data.container.karyawan.nip
          this.form.pengajuan_jadwal_absensi.create.data.nama_karyawan = res.data.container.karyawan.nama_karyawan
          this.form.pengajuan_jadwal_absensi.create.data.kode_cabang = res.data.container.cabang.kode_cabang
          this.form.pengajuan_jadwal_absensi.create.data.nama_cabang = res.data.container.cabang.cabang
          Promise.all([
            this.$axios.get('/ajax/v1/master/tipe_absensi/' + this.query.absensi.tipe_absensi_id)
          ])
            .then(res => {
              this.form.pengajuan_jadwal_absensi.create.data.tipe_absensi = res[0].data.container.tipe_absensi
              $('[data-entity="pengajuanJadwalAbsensi"][data-method="create"]').modal('show')
            })
            .catch(err => {})
        })
        .catch(err => {

        })
    },
    showAcceptPengajuanModal(id) {
      this.$axios.get('/ajax/v1/pengajuan_jadwal_absensi/' + id)
        .then(res => {
          this.form.pengajuan_jadwal_absensi.accept.data.id = id
          $('[data-entity="pengajuanJadwalAbsensi"][data-method="accept"]').modal('show')
        })
    },
    showDestroyPengajuanModal(id) {
      this.$axios.get('/ajax/v1/pengajuan_jadwal_absensi/' + id)
        .then(res => {
          this.form.pengajuan_jadwal_absensi.destroy.data.id = id
          $('[data-entity="pengajuanJadwalAbsensi"][data-method="destroy"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/absensi', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            tugas_karyawan_id: null,
            tipe_absensi_id: null,
            tanggal_absensi: '',
            jam_jadwal: '',
            jam_absen: '',

            nip: '',
            nama_karyawan: '',
            kode_cabang: '',
            nama_cabang: '',
            tipe_absensi: ''
          }
          this.queryData(false)
          $('[data-entity="absensi"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/absensi', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            tanggal_absensi: '',
            jam_jadwal: '',
            jam_absen: '',

            nip: '',
            nama_karyawan: '',
            kode_cabang: '',
            nama_cabang: '',
            tipe_absensi: ''
          }
          this.queryData(false)
          $('[data-entity="absensi"][data-method="update"]').modal('hide')
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
      this.$axios.delete('/ajax/v1/absensi/', { data: this.form.destroy.data })
        .then(res => {
          this.form.destroy.data.id = null
          this.queryData(false)
          $('[data-entity="absensi"][data-method="destroy"]').modal('hide')
        })
        .catch(err => console.log(err.response))
        .finally(() => {
          this.form.destroy.loading = false
        })
    },
    createPengajuan() {
      this.form.pengajuan_jadwal_absensi.create.loading = true
      this.form.pengajuan_jadwal_absensi.create.errors = {}
      this.$axios.post('/ajax/v1/pengajuan_jadwal_absensi', this.form.pengajuan_jadwal_absensi.create.data)
        .then(res => {
          this.form.pengajuan_jadwal_absensi.create.data = {
            tugas_karyawan_id: null,
            tipe_absensi_id: null,
            tanggal_absensi: '',
            jam_jadwal: '',
            jam_absen: '',

            nip: '',
            nama_karyawan: '',
            kode_cabang: '',
            nama_cabang: '',
            tipe_absensi: ''
          }
          this.queryData(false)
          $('[data-entity="pengajuanJadwalAbsensi"][data-method="create"]').modal('hide')
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.pengajuan_jadwal_absensi.create.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.pengajuan_jadwal_absensi.create.loading = false
        })
    },
    acceptPengajuan() {
      this.form.pengajuan_jadwal_absensi.accept.loading = true
      this.form.pengajuan_jadwal_absensi.accept.errors = {}
      this.$axios.put('/ajax/v1/pengajuan_jadwal_absensi/approve', this.form.pengajuan_jadwal_absensi.accept.data)
        .then(res => {
          this.form.pengajuan_jadwal_absensi.accept.data.id = null
          this.queryData(false)
          $('[data-entity="pengajuanJadwalAbsensi"][data-method="accept"]').modal('hide')
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.pengajuan_jadwal_absensi.accept.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.pengajuan_jadwal_absensi.accept.loading = false
        })
    },
    destroyPengajuan() {
      this.form.pengajuan_jadwal_absensi.destroy.loading = true
      this.form.pengajuan_jadwal_absensi.destroy.errors = {}
      this.$axios.delete('/ajax/v1/pengajuan_jadwal_absensi', { data: this.form.pengajuan_jadwal_absensi.destroy.data })
        .then(res => {
          this.form.pengajuan_jadwal_absensi.destroy.data.id = null
          this.queryData(false)
          $('[data-entity="pengajuanJadwalAbsensi"][data-method="destroy"]').modal('hide')
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.pengajuan_jadwal_absensi.destroy.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.pengajuan_jadwal_absensi.destroy.loading = false
        })
    }
  }
}
</script>