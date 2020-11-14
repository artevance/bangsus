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
                  <select class="form-control" v-model="form.create.data.cabang_id">
                    <option v-for="cabang in data.cabang" :value="cabang.id">
                      {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Cabang Asal</label>
                  <select class="form-control" v-model="form.create.data.cabang_asal_id">
                    <option v-for="cabang in data.allCabang" :value="cabang.id">
                      {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                    </option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Supplier Mutasi</label>
                  <select class="form-control" v-model="form.create.data.supplier_mutasi_id">
                    <option v-for="supplierMutasi in data.supplierMutasi" :value="supplierMutasi.id">
                      {{ supplierMutasi.supplier_mutasi }}
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
                      <th>Keterangan</th>
                      <th>Gambar</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(detail, i) in form.create.data.d">
                        <td>{{ i + 1 }}</td>
                        <td>
                          <barang-component v-model="detail.barang_id" :component-id="i" @input="reloadSatuan(i)"/>
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
          loading: false
        }
      },
      data: {
        supplier: [],
        cabang: [],
        allCabang: [],
        supplierMutasi: [],
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
        this.fetchSupplierMutasi()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.allCabang = res[1].data.container
          this.data.supplierMutasi = res[2].data.container
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
          this.form.create.data.d[i].qty = null
          this.form.create.data.d[i].harga_barang = null
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
    fetchSupplierMutasi() {
      return this.$axios.get('/ajax/v1/master/supplier_mutasi')
    },
    create() {
      console.log(this.form.create.data)
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/form_operasional/incoming_mutation', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            cabang_id: null,
            cabang_asal_id: null,
            supplier_mutasi_id: null,
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
          this.$router.push({ name: 'formOperasional.incomingMutation' })
          this.$parent.queryData()
        })
        .catch(err => {
          this.form.create.data.errors = err.response.data.errors
        })
        .finally(() => {
          this.form.create.loading = false
        })
    }
  }
}
</script>