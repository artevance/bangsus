<template>
  <div class="row mt-5">
    <div class="col col-xl-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <router-link :to="{ name: 'formOperasional.incomingMutation' }">
            <i class="fas fa-backspace"></i>
            Kembali
          </router-link>
          <div class="card-title">Mutasi Masuk Baru</div>
          <form>
            <div class="row">
              <div class="col col-md-6">
                <div class="form-group">
                  <label>Cabang</label>
                  <select class="form-control" v-model="form.create.data.cabang_id" @change="fetchTugasKaryawan(form.create.data.cabang_id)">
                    <option v-for="cabang in data.cabang" :value="cabang.id">
                      {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Cabang Asal</label>
                  <select class="form-control" v-model="form.create.data.cabang_asal_id" @change="fetchOutgoingMutation()">
                    <option v-for="allCabang in data.allCabang" :value="allCabang.id">
                      {{ allCabang.kode_cabang }} - {{ allCabang.cabang }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Mutasi Keluar</label>
                  <select class="form-control" v-model="form.create.data.outgoing_mutation_id" @change="fetchOutgoingMutationDetail()">
                    <option v-for="outgoingMutation in data.outgoingMutation" :value="outgoingMutation.id">
                      {{ outgoingMutation.tanggal_form }} - {{ outgoingMutation.jam }}
                    </option>
                  </select>
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
              </div>
              <div class="col col-md-6">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" v-model="form.create.data.keterangan"></textarea>
                </div>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <th>#</th>
                      <th>Barang</th>
                      <th style="min-width: 200px;">Satuan</th>
                      <th style="min-width: 200px;">Qty</th>
                      <th style="min-width: 200px;">Qty (Kg)</th>
                      <th style="min-width: 200px;">Harga Barang</th>
                      <th>Keterangan</th>
                      <th>Gambar</th>
                    </thead>
                    <tbody>
                      <tr v-for="(detail, i) in data.outgoingMutationDetail.d">
                        <td>{{ i + 1 }}</td>
                        <td>
                          {{ detail.barang.kode_barang }} - {{ detail.barang.nama_barang }}
                        </td>
                        <td>
                          <span v-if="detail.level_satuan == 1">{{ detail.barang.satuan != null ? detail.barang.satuan.satuan : '' }}</span>
                          <span v-if="detail.level_satuan == 2">{{ detail.barang.satuan_dua != null ? detail.barang.satuan_dua.satuan : '' }}</span>
                          <span v-if="detail.level_satuan == 3">{{ detail.barang.satuan_tiga != null ? detail.barang.satuan_tiga.satuan : '' }}</span>
                          <span v-if="detail.level_satuan == 4">{{ detail.barang.satuan_empat != null ? detail.barang.satuan_empat.satuan : '' }}</span>
                          <span v-if="detail.level_satuan == 5">{{ detail.barang.satuan_lima != null ? detail.barang.satuan_lima.satuan : '' }}</span>
                        </td>
                        <td>
                          {{ detail.qty }}
                        </td>
                        <td>
                          {{ detail.qty_kg }} Kg
                        </td>
                        <td>
                          {{ detail.harga_barang }}
                        </td>
                        <td>
                          {{ detail.keterangan }}
                        </td>
                        <td>
                          <a :href="'/ajax/v1/form_operasional/outgoing_mutation/gambar/' + detail.id" target="_blank">Link Foto</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <th>#</th>
                      <th>Barang</th>
                      <th style="min-width: 200px;">Satuan</th>
                      <th style="min-width: 200px;">Qty</th>
                      <th style="min-width: 200px;">Harga Barang</th>
                      <th>Keterangan</th>
                      <th>Gambar</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(detail, i) in form.create.data.d">
                        <td>{{ i + 1 }}</td>
                        <td>
                          <barang-component link="/ajax/v1/master/barang/mutation" v-model="detail.barang_id" :component-id="i" @input="reloadSatuan(i)"/>
                        </td>
                        <td>
                          <select class="form-control" v-model="detail.level_satuan">
                            <option value="null">-- Pilih Satuan --</option>
                            <option value="1" v-if="detail.satuan != null">{{ detail.satuan.satuan }}</option>
                            <option value="2" v-if="detail.satuan_dua != null">{{ detail.satuan_dua.satuan }}</option>
                            <option value="3" v-if="detail.satuan_tiga != null">{{ detail.satuan_tiga.satuan }}</option>
                            <option value="4" v-if="detail.satuan_empat != null">{{ detail.satuan_empat.satuan }}</option>
                            <option value="5" v-if="detail.satuan_lima != null">{{ detail.satuan_lima.satuan }}</option>
                          </select>
                        </td>
                        <td>
                          <input type="number" class="form-control" v-model="detail.qty">
                        </td>
                        <td>
                          <input type="number" class="form-control" v-model="detail.harga_barang" readonly>
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="detail.keterangan">
                        </td>
                        <td>
                          <webcam-component v-model="detail.gambar" ref="webcam"></webcam-component>
                        </td>
                        <td>
                          <button class="btn btn-sm" type="button" @click.prevent="removeDetail(i)">
                            <i class="fas fa-trash text-danger"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <button class="btn btn-secondary btn-sm mt-3" type="button" @click.prevent="addDetail">+ Tambah</button>
              </div>
            </div>
            <button class="btn btn-primary mt-5" @click.prevent="create" :disabled="form.create.loading">
              <spinner-component size="sm" v-if="form.create.loading"></spinner-component>
              Submit
            </button>
            <div class="alert alert-danger mt-3" v-if="form.create.error">
              Gagal memasukkan data.
            </div>
            <div class="modal fade" id="successModal" data-backdrop="static" data-keyboard="false">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Berhasil</h5>
                  </div>
                  <div class="modal-body">
                    Berhasil memasukkan data.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="closeModal">Tutup</button>
                  </div>
                </div>
              </div>
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
      form: {
        create: {
          data: {
            cabang_id: null,
            cabang_asal_id: null,
            keterangan: '',
            d: [
              {
                barang_id: null,
                level_satuan: null,
                qty: null,
                keterangan: '',
                harga_barang: 0,
                satuan: null,
                satuan_dua: null,
                satuan_tiga: null,
                satuan_empat: null,
                satuan_lima: null,
                gambar: '',
              }
            ]
          },
          errors: [],
          loading: false,
          error: false,
          success: false,
        }
      },
      data: {
        supplier: [],
        cabang: [],
        allCabang: [],
        outgoingMutation: [],
        outgoingMutationDetail: [],
        tugas_karyawan: [],
      }
    }
  },
  created() {
    this.prepare()
  },

  methods: {
    prepare() {
      Promise.all([
        this.fetchCabang(),
        this.fetchAllCabang(),
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.allCabang = res[1].data.container
        })
    },
    addDetail() {
      this.form.create.data.d.push({
        barang_id: null,
        level_satuan: null,
        qty: null,
        keterangan: '',
        harga_barang: 0,
        satuan: null,
        satuan_dua: null,
        satuan_tiga: null,
        satuan_empat: null,
        satuan_lima: null,
      })
    },
    removeDetail(i) {
      this.form.create.data.d.splice(i, 1)
    },
    reloadSatuan(i) {
      this.$axios.get('/ajax/v1/master/barang/' + this.form.create.data.d[i].barang_id)
        .then(res => {
          let barang = res.data.container
          this.form.create.data.d[i].level_satuan = null
          this.form.create.data.d[i].qty = 0
          this.form.create.data.d[i].harga_barang = 0
          this.form.create.data.d[i].satuan = barang.satuan
          this.form.create.data.d[i].satuan_dua = barang.satuan_dua
          this.form.create.data.d[i].satuan_tiga = barang.satuan_tiga
          this.form.create.data.d[i].satuan_empat = barang.satuan_empat
          this.form.create.data.d[i].satuan_lima = barang.satuan_lima
        })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchAllCabang() {
      return this.$axios.get('/ajax/v1/master/cabang')
    },
    fetchOutgoingMutation() {
      this.$axios.get('/ajax/v1/form_operasional/outgoing_mutation/for_incoming_mutation', {
        params: {
          cabang_id: this.form.create.data.cabang_asal_id,
          cabang_tujuan_id: this.form.create.data.cabang_id,
        }
      })
        .then(res => {
          this.data.outgoingMutation = res.data.container
        })
    },
    fetchOutgoingMutationDetail() {
      this.$axios.get('/ajax/v1/form_operasional/outgoing_mutation/' + this.form.create.data.outgoing_mutation_id)
        .then(res => {
          this.data.outgoingMutationDetail = res.data.container
        })
    },
    create() {
      this.form.create.error = false
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/form_operasional/incoming_mutation', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            cabang_id: null,
            cabang_asal_id: null,
            keterangan: '',
            d: [
              {
                barang_id: null,
                level_satuan: null,
                qty: 0,
                keterangan: '',
                satuan: null,
                harga_barang: 0,
                satuan_dua: null,
                satuan_tiga: null,
                satuan_empat: null,
                satuan_lima: null,
                gambar: ''
              }
            ]
          }
          $('#successModal').modal('show')
        })
        .catch(err => {
          this.form.create.data.errors = err.response.data.errors
          this.form.create.error = true
        })
        .finally(() => {
          this.form.create.loading = false
        })
    },
    closeModal() {
      $('#successModal').modal('hide')
      this.$router.push({ name: 'formOperasional.incomingMutation' })
      this.$parent.queryData()
    },
    fetchTugasKaryawan(id) {
      this.$axios.get('/ajax/v1/tugas_karyawan/cabang/?cabang_id=' + id + '&tanggal_penugasan=' + this.$moment().format('YYYY-MM-DD HH:mm:ss'))
        .then(res => {
            this.data.tugas_karyawan = res.data.container
          })
    },
  }
}
</script>