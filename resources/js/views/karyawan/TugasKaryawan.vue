<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <router-link :to="{ name: 'karyawan' }">
              <i class="fas fa-backspace"></i> Kembali
            </router-link>
            <div v-if="$access('karyawan.tugasKaryawan', 'read')">
              <div class="card-title mt-5">Informasi Pribadi</div>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label>NIP</label>
                    <input type="text" class="form-control" v-model="data.karyawan.nip" readonly>
                  </div>
                  <div class="form-group">
                    <label>NIK</label>
                    <input type="text" class="form-control" v-model="data.karyawan.nik" readonly>
                  </div>
                  <div class="form-group">
                    <label>Nama Karyawan</label>
                    <input type="text" class="form-control" v-model="data.karyawan.nama_karyawan" readonly>
                  </div>
                </div>
                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" class="form-control" v-model="data.karyawan.tempat_lahir" readonly>
                  </div>
                  <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" class="form-control" v-model="data.karyawan.tanggal_lahir" readonly>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <label>Golongan Darah</label>
                      <input type="text" class="form-control" v-model="data.karyawan.golongan_darah.golongan_darah" readonly>
                    </div>
                    <div class="col-6">
                      <label>Jenis Kelamin</label>
                      <input type="text" class="form-control" v-model="data.karyawan.jenis_kelamin.jenis_kelamin" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-title mt-5">Penugasan Karyawan</div>
              <button class="btn btn-primary mt-2" @click="showCreateModal" v-if="$access('karyawan.tugasKaryawan', 'create')">Tambah</button>
              <div class="row mt-5">
                <div class="col-12 col-md-6">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.tugas_karyawan.q" @keyup="queryData">
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table table-hover">
                  <thead>
                    <th>#</th>
                    <th>Cabang</th>
                    <th>Divisi</th>
                    <th>Jabatan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>No. Finger</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(tugas_karyawan, i) in data.tugas_karyawan">
                      <td>{{ i + 1 }}</td>
                      <td>{{ tugas_karyawan.cabang.kode_cabang }} - {{ tugas_karyawan.cabang.cabang }}</td>
                      <td>{{ tugas_karyawan.divisi.divisi }}</td>
                      <td>{{ tugas_karyawan.jabatan.jabatan }}</td>
                      <td>{{ tugas_karyawan.tanggal_mulai }}</td>
                      <td>{{ tugas_karyawan.tanggal_selesai }}</td>
                      <td>{{ tugas_karyawan.no_finger }}</td>
                      <td>
                        <a class="badge badge-warning" @click="showUpdateModal(tugas_karyawan.id)" href="#" v-if="$access('karyawan.tugasKaryawan', 'update')">
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
      </div>
    </transition>

    <!-- Modal -->
    <div class="modal fade" data-entity="tugasKaryawan" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('karyawan.tugasKaryawan', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Tugas Karyawan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>NIP</label>
                <input type="number" class="form-control" v-model="data.karyawan.nip" readonly>
              </div>
              <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" class="form-control" v-model="data.karyawan.nama_karyawan" readonly>
              </div>
              <div class="form-group">
                <label>Cabang</label>
                <select class="form-control" v-model="form.create.data.cabang_id">
                  <option value="null">-- Pilih Cabang --</option>
                  <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                    {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.create.errors.cabang_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Divisi</label>
                <select class="form-control" v-model="form.create.data.divisi_id">
                  <option value="null">-- Pilih Divisi --</option>
                  <option v-for="(divisi, i) in data.divisi" :key="i" :value="divisi.id">
                    {{ divisi.divisi }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.create.errors.divisi_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Jabatan</label>
                <select class="form-control" v-model="form.create.data.jabatan_id">
                  <option value="null">-- Pilih Jabatan --</option>
                  <option v-for="(jabatan, i) in data.jabatan" :key="i" :value="jabatan.id">
                    {{ jabatan.jabatan }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.create.errors.jabatan_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" class="form-control" v-model="form.create.data.tanggal_mulai">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.tanggal_mulai" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" class="form-control" v-model="form.create.data.tanggal_selesai">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.tanggal_selesai" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>No Finger</label>
                <input type="number" class="form-control" v-model="form.create.data.no_finger">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.no_finger" :key="index">
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
    <div class="modal fade" data-entity="tugasKaryawan" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('karyawan.tugasKaryawan', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Tugas Karyawan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>NIP</label>
                <input type="number" class="form-control" v-model="data.karyawan.nip" readonly>
              </div>
              <div class="form-group">
                <label>Nama Karyawan</label>
                <input type="text" class="form-control" v-model="data.karyawan.nama_karyawan" readonly>
              </div>
              <div class="form-group">
                <label>Cabang</label>
                <select class="form-control" v-model="form.update.data.cabang_id">
                  <option value="null">-- Pilih Cabang --</option>
                  <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                    {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.update.errors.cabang_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Divisi</label>
                <select class="form-control" v-model="form.update.data.divisi_id">
                  <option value="null">-- Pilih Divisi --</option>
                  <option v-for="(divisi, i) in data.divisi" :key="i" :value="divisi.id">
                    {{ divisi.divisi }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.update.errors.divisi_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Jabatan</label>
                <select class="form-control" v-model="form.update.data.jabatan_id">
                  <option value="null">-- Pilih Jabatan --</option>
                  <option v-for="(jabatan, i) in data.jabatan" :key="i" :value="jabatan.id">
                    {{ jabatan.jabatan }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.update.errors.jabatan_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" class="form-control" v-model="form.update.data.tanggal_mulai">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.tanggal_mulai" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" class="form-control" v-model="form.update.data.tanggal_selesai">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.tanggal_selesai" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>No Finger</label>
                <input type="number" class="form-control" v-model="form.update.data.no_finger">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.no_finger" :key="index">
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
        karyawan: {},
        tugas_karyawan: [],
        cabang: [],
        divisi: [],
        jabatan: []
      },
      form: {
        create: {
          data: {
            karyawan_id: this.$route.params.id,
            cabang_id: null,
            divisi_id: null,
            jabatan_id: null,
            tanggal_mulai: '',
            tanggal_selesai: '',
            no_finger: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            cabang_id: null,
            divisi_id: null,
            jabatan_id: null,
            tanggal_mulai: '',
            tanggal_selesai: '',
            no_finger: ''
          },
          errors: {},
          loading: false
        }
      },
      query: {
        tugas_karyawan: {
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
        this.fetchKaryawan()
      ])
        .then(res => {
          this.data.tugas_karyawan = res[0].data.container
          this.data.karyawan = res[1].data.container
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
          this.data.tugas_karyawan = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/tugas_karyawan/parent/' + this.$route.params.id + '?q=' + this.query.tugas_karyawan.q)
    },
    fetchKaryawan() {
      return this.$axios.get('/ajax/v1/karyawan/' + this.$route.params.id)
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang')
    },
    fetchDivisi() {
      return this.$axios.get('/ajax/v1/master/divisi')
    },
    fetchJabatan() {
      return this.$axios.get('/ajax/v1/master/jabatan')
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      Promise.all([
        this.fetchCabang(),
        this.fetchDivisi(),
        this.fetchJabatan()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.divisi = res[1].data.container
          this.data.jabatan = res[2].data.container
          $('[data-entity="tugasKaryawan"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/tugas_karyawan/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            cabang_id: res.data.container.cabang_id,
            divisi_id: res.data.container.divisi_id,
            jabatan_id: res.data.container.jabatan_id,
            tanggal_mulai: res.data.container.tanggal_mulai,
            tanggal_selesai: res.data.container.tanggal_selesai,
            no_finger: res.data.container.no_finger
          }
          Promise.all([
            this.fetchCabang(),
            this.fetchDivisi(),
            this.fetchJabatan()
          ])
            .then(res => {
              this.data.cabang = res[0].data.container
              this.data.divisi = res[1].data.container
              this.data.jabatan = res[2].data.container
              $('[data-entity="tugasKaryawan"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/tugas_karyawan', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            karyawan_id: this.$route.params.id,
            cabang_id: null,
            divisi_id: null,
            jabatan_id: null,
            tanggal_mulai: '',
            tanggal_selesai: '',
            no_finger: ''
          }
          this.prepare()
          $('[data-entity="tugasKaryawan"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/tugas_karyawan', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            cabang_id: null,
            divisi_id: null,
            jabatan_id: null,
            tanggal_mulai: '',
            tanggal_selesai: '',
            no_finger: ''
          }
          this.prepare()
          $('[data-entity="tugasKaryawan"][data-method="update"]').modal('hide')
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