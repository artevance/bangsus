<template>
  <div class="row mt-5">
    <div class="col col-xl-12 stretch-card">
      <div class="card">
        <div class="card-body">
          <router-link :to="{ name: 'formOperasional.stokOpname' }">
            <i class="fas fa-backspace"></i>
            Kembali
          </router-link>
          <div class="card-title">Stok Opname Baru</div>
          <form>
            <div class="row">
              <div class="col col-md-6">
                <div class="form-group">
                  <label>Tanggal Opname</label>
                  <input type="date" v-model="form.create.data.tanggal_opname" class="form-control">
                </div>
                <div class="form-group">
                  <label>Jam Opname</label>
                  <input type="time" v-model="form.create.data.jam_opname" class="form-control">
                </div>
                <div class="form-group">
                  <label>Cabang</label>
                  <select class="form-control" v-model="form.create.data.cabang_id" @change="getBarang" :disabled="form.create.state.cabangReadonly">
                    <option v-for="cabang in data.cabang" :value="cabang.id">
                      {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Tipe Stok Opname</label>
                  <select class="form-control" v-model="form.create.data.tipe_stok_opname_id" @change="getBarang" :disabled="form.create.state.tipeStokOpnameReadonly">
                    <option v-for="tipe_stok_opname in data.tipe_stok_opname" :value="tipe_stok_opname.id">
                      {{ tipe_stok_opname.tipe_stok_opname }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="col col-md-6">
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea class="form-control" v-model="form.create.data.keterangan"></textarea>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <th>#</th>
                      <th>Barang</th>
                      <th style="min-width: 200px;">Satuan</th>
                      <th style="min-width: 200px;">Qty</th>
                      <th style="min-width: 200px;">Harga Barang</th>
                      <th style="min-width: 200px;">Keterangan</th>
                      <th>Gambar</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(detail, i) in form.create.data.d">
                        <td>{{ i + 1 }}</td>
                        <td>
                          {{ detail.nama_barang }}
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
                          <input type="number" class="form-control" v-model="detail.harga_barang">
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="detail.keterangan">
                        </td>
                        <td>
                          <webcam-component v-model="detail.gambar" ref="webcam"></webcam-component>
                        </td>
                        <td>
                          
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- <button class="btn btn-secondary btn-sm mt-3" type="button" @click.prevent="addDetail">+ Tambah</button> -->
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
            tanggal_opname: this.$moment().format('YYYY-MM-DD'),
            jam_opname: this.$moment().format('HH:mm:ss'),
            cabang_id: null,
            tipe_stok_opname_id: null,
            keterangan: '',
            d: [
              
            ]
          },
          errors: [],
          loading: false,
          error: false,
          state: {
            cabangReadonly: false,
            tipeStokOpnameReadonly: false,
          }
        }
      },
      data: {
        supplier: [],
        cabang: [],
        tipe_stok_opname: [],
      }
    }
  },
  created() {
    this.prepare()
  },
  mounted() {
    window.onbeforeunload = function (e) {
      return 'Apakah anda yakin?'
    }
  },

  methods: {
    prepare() {
      Promise.all([
        this.fetchCabang(),
        this.fetchTipeStokOpname(),
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.tipe_stok_opname = res[1].data.container
        })
    },
    selectCabang(cabangId) {
      
    },
    selectTipeStokOpname(tipeStokOpnameId) {
      
    },
    getBarang() {
      let cabang = _.find(this.data.cabang, { 'id': this.form.create.data.cabang_id })
      let tipeCabangId = cabang ? cabang.tipe_cabang_id : null
      let tipeStokOpnameId = this.form.create.data.tipe_stok_opname_id

      console.log([tipeCabangId, tipeStokOpnameId])
      if (tipeCabangId != null && tipeStokOpnameId != null) {
        this.$axios.get('/ajax/v1/master/barang/opname/' + tipeCabangId + '/' + tipeStokOpnameId)
          .then(res => {
            this.form.create.state.cabangReadonly = true
            this.form.create.state.tipeStokOpnameReadonly = true
            let barang = res.data.container

            barang.forEach(brg => {
              this.addDetail(brg)
            })
          })
      }
    },
    addDetail(brg) {
      this.form.create.data.d.push({
        barang_id: brg.id,
        nama_barang: brg.nama_barang,
        level_satuan: 1,
        qty: 0,
        keterangan: '',
        harga_barang: 0,
        satuan: brg.satuan,
        satuan_dua: brg.satuan_dua,
        satuan_tiga: brg.satuan_tiga,
        satuan_empat: brg.satuan_empat,
        satuan_lima: brg.satuan_lima,
      })
    },
    removeDetail(i) {
      this.form.create.data.d.splice(i, 1)
    },
    reloadSatuan(i) {
      this.$axios.get('/ajax/v1/master/barang/' + this.form.create.data.d[i].barang_id)
        .then(res => {
          let barang = res.data.container
          this.form.create.data.d[i].level_satuan = 1
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
    fetchTipeStokOpname() {
      return this.$axios.get('/ajax/v1/master/tipe_stok_opname')
    },
    create() {
      this.form.create.error = false
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/form_operasional/stok_opname', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            tanggal_opname: null,
            jam_opname: null,
            cabang_id: null,
            keterangan: '',
            d: [
              {
                barang_id: null,
                level_satuan: null,
                qty: null,
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
      this.$router.push({ name: 'formOperasional.stokOpname' })
      this.$parent.queryData()
    },
  }
}
</script>