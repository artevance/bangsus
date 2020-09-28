<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('karyawan.tugasKaryawan', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.tugas_karyawan.q" @keyup="queryData" v-if="$access('karyawan.tugasKaryawan', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('karyawan.tugasKaryawan', 'read')">
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
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      state: { page: { loading: true } },
      data: {
        tugas_karyawan: []
      },
      form: {
        create: {
          data: {
            tugas_karyawan: ''
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            tugas_karyawan: ''
          },
          errors: {}
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
        this.fetchMainData()
      ])
        .then(res => {
          this.data.tugas_karyawan = res[0].data.container
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

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="tugas_karyawan"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/tugas_karyawan/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            tugas_karyawan: res.data.container.tugas_karyawan
          }
          $('[data-entity="tugas_karyawan"][data-method="update"]').modal('show')
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
            tugas_karyawan: ''
          }
          this.prepare()
          $('[data-entity="tugas_karyawan"][data-method="create"]').modal('hide')
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
            tugas_karyawan: ''
          }
          this.prepare()
          $('[data-entity="tugas_karyawan"][data-method="update"]').modal('hide')
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