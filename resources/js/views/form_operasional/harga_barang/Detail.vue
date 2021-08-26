<template>
  <div class="row mt-5 justify-content-center">
    <transition name="fade" mode="out-in">
      <div class="col col-xl-12 stretch-card" v-if="!state.page.loading">
        <div class="card">
          <div class="card-body">
            <router-link :to="{ name: 'formOperasional.hargaBarang' }">
              <i class="fas fa-backspace"></i>
              Kembali
            </router-link>
            <div class="card-title">Detail Harga Barang</div>
            <form>
              <div class="row">
                <div class="col col-md-6">
                  <div class="form-group">
                    <label>Tanggal Form</label>
                    <input class="form-control" v-model="form.detail.data.tanggal_form" type="date" disabled>
                  </div>
                  <div class="form-group">
                    <label>Jam</label>
                    <input class="form-control" v-model="form.detail.data.jam" type="time" disabled>
                  </div>
                </div>
                <div class="col col-md-6">
                  <div class="form-group">
                    <label>Keterangan</label>
                    <textarea class="form-control" v-model="form.detail.data.keterangan" disabled></textarea>
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
                        <th style="min-width: 200px;">Harga Lama</th>
                        <th style="min-width: 200px;">Harga Barang</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                      </thead>
                      <tbody>
                        <tr v-for="(detail, i) in form.detail.data.d">
                          <td>{{ i + 1 }}</td>
                          <td>
                            <barang-component v-model="detail.barang_id" :component-id="i" @input="reloadSatuan(i)" no-edit="true"/>
                          </td>
                          <td>
                            <select class="form-control" v-model="detail.level_satuan" disabled>
                              <option value="null">-- Pilih Satuan --</option>
                              <option value="1" v-if="detail.satuan != null">{{ detail.satuan.satuan }}</option>
                              <option value="2" v-if="detail.satuan_dua != null">{{ detail.satuan_dua.satuan }}</option>
                              <option value="3" v-if="detail.satuan_tiga != null">{{ detail.satuan_tiga.satuan }}</option>
                              <option value="4" v-if="detail.satuan_empat != null">{{ detail.satuan_empat.satuan }}</option>
                              <option value="5" v-if="detail.satuan_lima != null">{{ detail.satuan_lima.satuan }}</option>
                            </select>
                          </td>
                          <td>
                            <input type="number" class="form-control" v-model="detail.harga_lama" disabled>
                          </td>
                          <td>
                            <input type="number" class="form-control" v-model="detail.harga_barang" disabled>
                          </td>
                          <td>
                            <input type="text" class="form-control" v-model="detail.keterangan" disabled>
                          </td>
                          <td>
                            
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
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
        detail: {
          data: {
            cabang_id: null,
            supplier_id: null,
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
        this.fetchSupplier()
      ])
        .then(res => {
          let mainData = res[0].data.container
          this.form.detail.data = {
            id: mainData.id,
            cabang_id: mainData.cabang_id,
            supplier_id: mainData.supplier_id,
            keterangan: mainData.keterangan,
            tanggal_form: mainData.tanggal_form,
            jam: mainData.jam,
            d: []
          }
          mainData.d.forEach((item, i) => {
            this.form.detail.data.d.push({
              barang_id: item.barang_id,
              level_satuan: item.level_satuan,
              keterangan: item.keterangan,
              harga_lama: item.harga_lama,
              harga_barang: item.harga_barang,
              satuan: item.satuan,
              satuan_dua: item.satuan_dua,
              satuan_tiga: item.satuan_tiga,  
              satuan_empat: item.satuan_empat,
              satuan_lima: item.satuan_lima,
            })
            this.reloadSatuan(i, false)
          })
          this.data.cabang = res[1].data.container
          this.data.supplier = res[2].data.container

          this.state.page.loading = false
        })
    },
    addDetail() {
      this.form.detail.data.d.push({
        barang_id: null,
        level_satuan: null,
        keterangan: '',
        harga_lama: 0,
        harga_barang: 0,
        satuan: null,
        satuan_dua: null,
        satuan_tiga: null,
        satuan_empat: null,
        satuan_lima: null,
      })
    },
    removeDetail(i) {
      this.form.detail.data.d.splice(i, 1)
    },
    reloadSatuan(i, resetOtherField = true) {
      if ( ! this.state.page.loading || ! this.resetOtherField) {
        this.$axios.get('/ajax/v1/master/barang/' + this.form.detail.data.d[i].barang_id)
          .then(res => {
            let barang = res.data.container
            if (this.resetOtherField) {
              this.form.detail.data.d[i].level_satuan = null
              this.form.detail.data.d[i].harga_lama = null
              this.form.detail.data.d[i].harga_barang = null
            }
            this.form.detail.data.d[i].satuan = barang.satuan
            this.form.detail.data.d[i].satuan_dua = barang.satuan_dua
            this.form.detail.data.d[i].satuan_tiga = barang.satuan_tiga
            this.form.detail.data.d[i].satuan_empat = barang.satuan_empat
            this.form.detail.data.d[i].satuan_lima = barang.satuan_lima
          })
      }
    },
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/harga_barang/' + this.$route.params.id)
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchSupplier() {
      return this.$axios.get('/ajax/v1/master/supplier')
    }
  }
}
</script>