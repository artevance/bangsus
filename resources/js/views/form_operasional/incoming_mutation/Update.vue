<template>
  <div class="row mt-5 justify-content-center">
    <transition name="fade" mode="out-in">
      <div class="col col-xl-12 stretch-card" v-if="!state.page.loading">
        <div class="card">
          <div class="card-body">
            <router-link :to="{ name: 'formOperasional.incomingMutation' }">
              <i class="fas fa-backspace"></i>
              Kembali
            </router-link>
            <div class="card-title">Ubah Mutasi Masuk</div>
            <form>
              <div class="row">
                <div class="col col-md-6">
                  <div class="form-group">
                    <label>Tanggal Form</label>
                    <input class="form-control" v-model="form.update.data.tanggal_form" type="date" disabled>
                  </div>
                  <div class="form-group">
                    <label>Jam</label>
                    <input class="form-control" v-model="form.update.data.jam" type="time" disabled>
                  </div>
                  <div class="form-group">
                    <label>Cabang</label>
                    <select class="form-control" v-model="form.update.data.cabang_id" disabled>
                      <option v-for="cabang in data.cabang" :value="cabang.id">
                        {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Cabang Asal</label>
                    <select class="form-control" v-model="form.update.data.cabang_asal_id" disabled>
                      <option v-for="allCabang in data.allCabang" :value="allCabang.id">
                        {{ allCabang.kode_cabang }} - {{ allCabang.cabang }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Mutasi Keluar</label>
                    <select class="form-control" v-model="form.update.data.outgoing_mutation_id" disabled>
                      <option v-for="outgoingMutation in data.outgoingMutation" :value="outgoingMutation.id">
                        {{ outgoingMutation.tanggal_form }} - {{ outgoingMutation.jam }}
                      </option>
                    </select>
                  </div>
                </div>
                <div class="col col-md-6">
                  <div class="form-group">
                    <label>Keterangan</label>
                    <textarea class="form-control" v-model="form.update.data.keterangan"></textarea>
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
                        <tr v-for="(detail, i) in form.update.data.d">
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
              <button class="btn btn-primary mt-5" @click.prevent="update" :disabled="form.update.loading">
                <spinner-component size="sm" v-if="form.update.loading"></spinner-component>
                Ubah
              </button>
              <div class="alert alert-danger mt-3" v-if="form.update.error">
                Gagal memasukkan data.
              </div>
            </form>
          </div>
        </div>
      </div>
      <spinner-component v-else></spinner-component>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      state: { page: { loading: true } },
      form: {
        update: {
          data: {
            cabang_id: null,
            supplier_mutasi_id: null,
            keterangan: '',
            tanggal_form: '',
            jam: '',
            d: []
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
        outgoingMutation: [],
        outgoingMutationDetail: [],
      }
    }
  },
  created() {
    this.prepare()
  },

  methods: {
    prepare() {
      this.state.page.loading = true
      Promise.all([
        this.fetchMainData(),
        this.fetchCabang(),
        this.fetchAllCabang(),
      ])
        .then(res => {
          let mainData = res[0].data.container
          this.form.update.data = {
            id: mainData.id,
            cabang_id: mainData.cabang_id,
            cabang_asal_id: mainData.cabang_asal_id,
            outgoing_mutation_id: mainData.outgoing_mutation_id,
            keterangan: mainData.keterangan,
            tanggal_form: mainData.tanggal_form,
            jam: mainData.jam,
            d: []
          }

          let imageRequest = []
          mainData.d.forEach((item, i) => {
            let request = this.$axios.get('/ajax/v1/form_operasional/incoming_mutation/gambar/' + item.id, { responseType: 'blob' })
            imageRequest.push(request)
          })

          Promise.all(imageRequest)
            .then(res => {
              res.forEach((item, i) => {
                (new Promise((resolve, reject) => {
                  let reader = new window.FileReader()
                  reader.onload = () => resolve(reader.result)
                  reader.readAsDataURL(item.data)
                }))
                  .then(img => {
                    this.pushDetail(mainData.d[i], img)
                    this.reloadSatuan(i, false)
                  })
              })
              this.state.page.loading = false
            })
          this.data.cabang = res[1].data.container
          this.data.allCabang = res[2].data.container

          this.fetchOutgoingMutation()
          this.fetchOutgoingMutationDetail()
        })
    },
    pushDetail(item, gambar) {
      this.form.update.data.d.push({
        barang_id: item.barang_id,
        level_satuan: item.level_satuan,
        qty: item.qty,
        keterangan: item.keterangan,
        harga_barang: item.harga_barang,
        satuan: item.satuan,
        satuan_dua: item.satuan_dua,
        satuan_tiga: item.satuan_tiga,  
        satuan_empat: item.satuan_empat,
        satuan_lima: item.satuan_lima,
        gambar: gambar
      })
    },
    addDetail() {
      this.form.update.data.d.push({
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
      this.form.update.data.d.splice(i, 1)
    },
    reloadSatuan(i, resetOtherField = true) {
      if ( ! this.state.page.loading || ! this.resetOtherField) {
        this.$axios.get('/ajax/v1/master/barang/' + this.form.update.data.d[i].barang_id)
          .then(res => {
            let barang = res.data.container
            if (this.resetOtherField) {
              this.form.update.data.d[i].level_satuan = null
              this.form.update.data.d[i].qty = null
              this.form.update.data.d[i].harga_barang = null
            }
            this.form.update.data.d[i].satuan = barang.satuan
            this.form.update.data.d[i].satuan_dua = barang.satuan_dua
            this.form.update.data.d[i].satuan_tiga = barang.satuan_tiga
            this.form.update.data.d[i].satuan_empat = barang.satuan_empat
            this.form.update.data.d[i].satuan_lima = barang.satuan_lima
          })
      }
    },
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/incoming_mutation/' + this.$route.params.id)
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchAllCabang() {
      return this.$axios.get('/ajax/v1/master/cabang')
    },
    fetchOutgoingMutation() {
      this.$axios.get('/ajax/v1/form_operasional/outgoing_mutation/for_incoming_mutation_update', {
        params: {
          cabang_id: this.form.update.data.cabang_asal_id,
          cabang_tujuan_id: this.form.update.data.cabang_id,
        }
      })
        .then(res => {
          this.data.outgoingMutation = res.data.container
        })
    },
    fetchOutgoingMutationDetail() {
      this.$axios.get('/ajax/v1/form_operasional/outgoing_mutation/' + this.form.update.data.outgoing_mutation_id)
        .then(res => {
          this.data.outgoingMutationDetail = res.data.container
        })
    },
    update() {
      this.form.update.error = false
      this.form.update.loading = true
      this.$axios.put('/ajax/v1/form_operasional/incoming_mutation', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            cabang_id: null,
            supplier_mutasi_id: null,
            keterangan: '',
            tanggal_form: '',
            jam: '',
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
              }
            ]
          }
          this.$parent.queryData()
          this.$router.push({ name: 'formOperasional.incomingMutation' })
        })
        .catch(err => {
          this.form.update.error = true
          this.form.update.data.errors = err.response.data.errors
        })
        .finally(() => {
          this.form.update.loading = false
        })
    }
  }
}
</script>