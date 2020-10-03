<template>
  <div>
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
                <select class="form-control" v-model="query.form_thawing_ayam.cabang_id" @change="queryData">
                  <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                    {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                  </option>
                </select>
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    Tanggal Form
                  </span>
                </div>
                <input type="date" class="form-control" v-model="query.form_thawing_ayam.tanggal_form" @keyup="queryData">
              </div>
            </div>
          </div>
        </div>
        <!-- else -->
        <div class="row d-md-none">
          <div class="col-12">
            <div class="form-group">
              <label>Cabang</label>
              <select class="form-control" v-model="query.form_thawing_ayam.cabang_id" @change="queryData">
                <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                  {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label>Tanggal Form</label>
              <input type="date" class="form-control" v-model="query.form_thawing_ayam.tanggal_form" :readonly="!$access('formOperasional.formC1.formThawingAyam.read', 'changeDate')" @keyup="queryData">
            </div>
          </div>
        </div>
        <button class="btn btn-primary" @click="showCreateModal" v-if="$access('formOperasional.formC1.formThawingAyam', 'create')">Tambah</button>
        <div class="table-responsive mt-2">
          <table class="table table-hover" v-if="$access('formOperasional.formC1.formThawingAyam', 'read')">
            <thead>
              <th>#</th>
              <th>NIP</th>
              <th>Nama Karyawan</th>
              <th>Jam</th>
              <th>Qty</th>
              <th>Satuan</th>
              <th>Supplier</th>
              <th>Aksi</th>
            </thead>
            <tbody>
              <tr v-for="(form_thawing_ayam, i) in data.form_thawing_ayam">
                <td>{{ i + 1 }}</td>
                <td>{{ form_thawing_ayam.tugas_karyawan.karyawan.nip }}</td>
                <td>{{ form_thawing_ayam.tugas_karyawan.karyawan.nama_karyawan }}</td>
                <td>{{ form_thawing_ayam.jam }}</td>
                <td>{{ form_thawing_ayam.qty }}</td>
                <td>{{ form_thawing_ayam.satuan.satuan }}</td>
                <td>{{ form_thawing_ayam.supplier.supplier }}</td>
                <td>
                  
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </transition>

    <!-- Modal -->
    <div class="modal fade" data-entity="formThawingAyam" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Form Thawing Ayam</h5>
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
                  <input type="time" class="form-control">
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.jam">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Karyawan</label>
                <select class="form-control">
                  <option value="null">-- Pilih Karyawan --</option>
                  <option v-for="(tugas_karyawan, i) in data.tugas_karyawan" :value="tugas_karyawan.id">
                    {{ tugas_karyawan.karyawan.nip }} - {{ tugas_karyawan.karyawan.nama_karyawan }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.tugas_karyawan_id">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group row">
                <div class="col-12 col-lg-3">
                  <label>Supplier</label>
                  <select class="form-control">
                    <option value="null">-- Pilih Supplier --</option>
                    <option v-for="(supplier, i) in data.supplier" :value="supplier.id">
                      {{ supplier.supplier }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.supplier_id">
                    {{ msg }}
                  </small>
                </div>
                <div class="col-12 col-lg-3">
                  <label>Qty</label>
                  <input type="number" class="form-control" step="any">
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.qty">
                    {{ msg }}
                  </small>
                </div>
                <div class="col-12 col-lg-3">
                  <label>Satuan</label>
                  <select class="form-control">
                    <option value="null">-- Pilih Satuan --</option>
                    <option v-for="(satuan, i) in data.satuan" :value="satuan.id">
                      {{ satuan.satuan }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.satuan_id">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Gambar</label>
                <input type="hidden" v-model="form.create.data.gambar">
                <webcam-component></webcam-component>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control form-control-sm"></textarea>
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
        form_thawing_ayam: [],
        tugas_karyawan: [],
        supplier: [],
        satuan: []
      },
      form: {
        create: {
          data: {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            supplier_id: null,
            qty: null,
            satuan_id: null,
            gambar: '',
            keterangan: '',
            gambar: ''
          },
          errors: {},
          loading: false
        }
      },
      query: {
        form_thawing_ayam: {
          cabang_id: this.$route.query.cabang_id || null,
          tanggal_form: this.$route.query.tanggal_form || this.$moment().format('YYYY-MM-DD')
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

          if (this.data.cabang.length <= 0 || this.data.tipe_absensi <= 0) {
            this.$router.go(-1)
          }

          if (this.query.form_thawing_ayam.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.form_thawing_ayam.cabang_id }))) {
            this.query.form_thawing_ayam.cabang_id = this.data.cabang[0].id || null
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.form_thawing_ayam) && this.$route.name === 'formOperasional.formC1.formThawingAyam') {
            this.$router.push({
              name: 'formOperasional.formC1.formThawingAyam',
              query: this.query.form_thawing_ayam
            })
          }
          this.data.form_thawing_ayam = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_c1/form_thawing_ayam/cabang_harian', { params: this.query.form_thawing_ayam })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchTugasKaryawan(id, tanggal_penugasan) {
      return this.$axios.get('/ajax/v1/tugas_karyawan/cabang/?cabang_id=' + id + '&tanggal_penugasan=' + tanggal_penugasan)
    },
    fetchSupplier() {
      return this.$axios.get('/ajax/v1/master/supplier')
    },
    fetchSatuan() {
      return this.$axios.get('/ajax/v1/master/satuan')
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      Promise.all([
        this.fetchTugasKaryawan(this.query.form_thawing_ayam.cabang_id, this.query.form_thawing_ayam.tanggal_penugasan),
        this.fetchSupplier(),
        this.fetchSatuan()
      ])
        .then(res => {
          this.data.tugas_karyawan = res[0].data.container
          this.data.supplier = res[1].data.container
          this.data.satuan = res[2].data.container

          this.form.create.data.tanggal_form = this.query.form_thawing_ayam.tanggal_form
          let currentCabang = this.$_.findWhere(this.data.cabang, {id: parseInt(this.query.form_thawing_ayam.cabang_id)})
          this.form.create.data.kode_cabang = currentCabang.kode_cabang
          this.form.create.data.nama_cabang = currentCabang.cabang

          $('[data-entity="formThawingAyam"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showUpdateModal(id) { return
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/aktivitas_karyawan/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            aktivitas_karyawan: res.data.container.aktivitas_karyawan
          }
          $('[data-entity="formThawingAyam"][data-method="update"]').modal('show')
        })
        .catch(err => {})
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/form_operasional/form_c1/form_thawing_ayam', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            supplier_id: null,
            qty: null,
            satuan_id: null,
            keterangan: '',
            gambar: ''
          }
          this.queryData(false)
          $('[data-entity="formThawingAyam"][data-method="create"]').modal('hide')
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
  }
}
</script>