<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'karyawan'">
      <transition name="fade" mode="out-in">
        <preloader-component v-if="state.page.loading"/>
        <div class="col-12 col-xl-12 stretch-card" v-else>
          <div class="card">
            <div class="card-body">
              <button class="btn btn-primary" @click="showCreateModal" v-if="$access('karyawan', 'create')">Tambah</button>
              <div class="row mt-5">
                <div class="col-12 col-md-6">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.karyawan.q" @keyup="queryData" v-if="$access('karyawan', 'read')">
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table table-hover" v-if="$access('karyawan', 'read')">
                  <thead>
                    <th>#</th>
                    <th>NIP</th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Golongan Darah</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(karyawan, i) in data.karyawan">
                      <td>{{ i + 1 }}</td>
                      <td>{{ karyawan.nip }}</td>
                      <td>{{ karyawan.nik }}</td>
                      <td>{{ karyawan.nama_karyawan }}</td>
                      <td>{{ karyawan.tempat_lahir }}</td>
                      <td>{{ karyawan.tanggal_lahir }}</td>
                      <td>{{ karyawan.golongan_darah.golongan_darah }}</td>
                      <td>{{ karyawan.jenis_kelamin.jenis_kelamin }}</td>
                      <td>
                        <a class="badge badge-warning" @click="showUpdateModal(karyawan.id)" href="#" v-if="$access('karyawan', 'update')">
                          Ubah
                        </a>
                        <router-link class="badge badge-info" :to="{ name: 'karyawan.tugasKaryawan', params: { id: karyawan.id } }" v-if="$access('karyawan.tugasKaryawan', 'access')">
                          Lihat Penugasan
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
      <div class="modal fade" data-entity="karyawan" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('karyawan', 'create')">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12 col-lg-4">
                    <div class="form-group">
                      <label>Tanggal Mulai Bekerja</label>
                      <input type="date" class="form-control" v-model="form.create.data.tanggal_mulai">
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.tanggal_mulai" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-4">
                    <div class="form-group">
                      <label>Cabang Penerima</label>
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
                  </div>
                  <div class="col-12 col-lg-4">
                    <div class="form-group">
                      <label>Divisi Saat Diterima</label>
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
                  </div>
                  <div class="col-12 col-lg-4">
                    <div class="form-group">
                      <label>Jabatan Pertama</label>
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
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-4">
                    <div class="form-group">
                      <label>NIK</label>
                      <input type="number" class="form-control" v-model="form.create.data.nik">
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.nik" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-8">
                    <div class="form-group">
                      <label>Nama Karyawan</label>
                      <input type="text" class="form-control" v-model="form.create.data.nama_karyawan">
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.nama_karyawan" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Tempat Lahir</label>
                      <input type="text" class="form-control" v-model="form.create.data.tempat_lahir">
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.tempat_lahir" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Tanggal Lahir</label>
                      <input type="date" class="form-control" v-model="form.create.data.tanggal_lahir">
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.tanggal_lahir" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Golongan Darah</label>
                      <select class="form-control" v-model="form.create.data.golongan_darah_id">
                        <option value="null">-- Pilih Golongan Darah --</option>
                        <option v-for="(golongan_darah, i) in data.golongan_darah" :key="i" :value="golongan_darah.id">
                          {{ golongan_darah.golongan_darah }}
                        </option>
                      </select>
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.golongan_darah_id" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Jenis Kelamin</label>
                      <select class="form-control" v-model="form.create.data.jenis_kelamin_id">
                        <option value="null">-- Pilih Jenis Kelamin --</option>
                        <option v-for="(jenis_kelamin, i) in data.jenis_kelamin" :key="i" :value="jenis_kelamin.id">
                          {{ jenis_kelamin.jenis_kelamin }}
                        </option>
                      </select>
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.jenis_kelamin_id" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>No. Finger</label>
                      <input type="number" class="form-control" v-model="form.create.data.no_finger">
                      <small class="text-danger" v-for="(msg, index) in form.create.errors.no_finger" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
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
      <div class="modal fade" data-entity="karyawan" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('karyawan', 'update')">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <form @submit.prevent="update">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12 col-lg-4">
                    <div class="form-group">
                      <label>NIK</label>
                      <input type="number" class="form-control" v-model="form.update.data.nik">
                      <small class="text-danger" v-for="(msg, index) in form.update.errors.nik" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-8">
                    <div class="form-group">
                      <label>Nama Karyawan</label>
                      <input type="text" class="form-control" v-model="form.update.data.nama_karyawan">
                      <small class="text-danger" v-for="(msg, index) in form.update.errors.nama_karyawan" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Tempat Lahir</label>
                      <input type="text" class="form-control" v-model="form.update.data.tempat_lahir">
                      <small class="text-danger" v-for="(msg, index) in form.update.errors.tempat_lahir" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Tanggal Lahir</label>
                      <input type="date" class="form-control" v-model="form.update.data.tanggal_lahir">
                      <small class="text-danger" v-for="(msg, index) in form.update.errors.tanggal_lahir" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Golongan Darah</label>
                      <select class="form-control" v-model="form.update.data.golongan_darah_id">
                        <option value="null">-- Pilih Golongan Darah --</option>
                        <option v-for="(golongan_darah, i) in data.golongan_darah" :key="i" :value="golongan_darah.id">
                          {{ golongan_darah.golongan_darah }}
                        </option>
                      </select>
                      <small class="text-danger" v-for="(msg, index) in form.update.errors.golongan_darah_id" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3">
                    <div class="form-group">
                      <label>Jenis Kelamin</label>
                      <select class="form-control" v-model="form.update.data.jenis_kelamin_id">
                        <option value="null">-- Pilih Jenis Kelamin --</option>
                        <option v-for="(jenis_kelamin, i) in data.jenis_kelamin" :key="i" :value="jenis_kelamin.id">
                          {{ jenis_kelamin.jenis_kelamin }}
                        </option>
                      </select>
                      <small class="text-danger" v-for="(msg, index) in form.update.errors.jenis_kelamin_id" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                  </div>
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
        karyawan: [],
        cabang: [],
        divisi: [],
        jabatan: [],
        golongan_darah: [],
        jenis_kelamin: []
      },
      form: {
        create: {
          data: {
            tanggal_mulai: '',
            cabang_id: null,
            divisi_id: null,
            jabatan_id: null,
            nik: '',
            nama_karyawan: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            golongan_darah_id: null,
            jenis_kelamin_id: null,
            no_finger: ''
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            nik: '',
            nama_karyawan: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            golongan_darah_id: null,
            jenis_kelamin_id: null
          },
          errors: {}
        }
      },
      query: {
        karyawan: {
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
          this.data.karyawan = res[0].data.container
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
          this.data.karyawan = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/karyawan?q=' + this.query.karyawan.q)
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
    fetchGolonganDarah() {
      return this.$axios.get('/ajax/v1/master/golongan_darah')
    },
    fetchJenisKelamin() {
      return this.$axios.get('/ajax/v1/master/jenis_kelamin')
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      Promise.all([
        this.fetchCabang(),
        this.fetchDivisi(),
        this.fetchJabatan(),
        this.fetchGolonganDarah(),
        this.fetchJenisKelamin()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.divisi = res[1].data.container
          this.data.jabatan = res[2].data.container
          this.data.golongan_darah = res[3].data.container
          this.data.jenis_kelamin = res[4].data.container
          $('[data-entity="karyawan"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/karyawan/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            nik: res.data.container.nik,
            nama_karyawan: res.data.container.nama_karyawan,
            tempat_lahir: res.data.container.tempat_lahir,
            tanggal_lahir: res.data.container.tanggal_lahir,
            golongan_darah_id: res.data.container.golongan_darah_id,
            jenis_kelamin_id: res.data.container.jenis_kelamin_id
          }
          Promise.all([
            this.fetchCabang(),
            this.fetchDivisi(),
            this.fetchJabatan(),
            this.fetchGolonganDarah(),
            this.fetchJenisKelamin()
          ])
            .then(res => {
              this.data.cabang = res[0].data.container
              this.data.divisi = res[1].data.container
              this.data.jabatan = res[2].data.container
              this.data.golongan_darah = res[3].data.container
              this.data.jenis_kelamin = res[4].data.container
              $('[data-entity="karyawan"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/karyawan', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            karyawan: ''
          }
          this.prepare()
          $('[data-entity="karyawan"][data-method="create"]').modal('hide')
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
    update() {
      this.form.update.loading = true
      this.$axios.put('/ajax/v1/karyawan', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            karyawan: ''
          }
          this.prepare()
          $('[data-entity="karyawan"][data-method="update"]').modal('hide')
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