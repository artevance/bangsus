<template>
  <div class="row mt-5">
    <div class="col">
      <div class="card">
        <div class="card-body">
          <router-link :to="{ name: 'reportCenter' }">
            <i class="fas fa-backspace"></i> Kembali
          </router-link>
          <!-- If the user uses laptop or tablet -->
          <div class="row d-none d-lg-block mt-3">
            <div class="col-12">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Tanggal Awal
                    </span>
                  </div>
                  <input type="date"
                    class="form-control"
                    v-model="query.laporan_pembelian.tanggal_awal"
                    >
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Tanggal Akhir
                    </span>
                  </div>
                  <input type="date"
                    class="form-control"
                    v-model="query.laporan_pembelian.tanggal_akhir"
                    >
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Barang
                    </span>
                  </div>
                  <select class="form-control" v-model="query.laporan_pembelian.kode_barang">
                    <option value="*">Semua Barang</option>
                    <option v-for="(barang, i) in data.barang" :key="i" :value="barang.kode_barang">
                      {{ barang.kode_barang }} - {{ barang.nama_barang }}
                    </option>
                  </select>
                  <div class="input-group-append">
                    <button class="btn btn-primary" :disabled="state.table.loading" @click="queryData">
                      <spinner-component size="sm" color="light" v-if="state.table.loading"/>
                      <i class="far fa-search" v-else></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- else -->
          <div class="row d-lg-none mt-3">
            <div class="col-12">
              <div class="form-group">
                <label>Tanggal Awal</label>
                <input type="date"
                  class="form-control"
                  v-model="query.laporan_pembelian.tanggal_awal"
                  >
              </div>
              <div class="form-group">
                <label>Tanggal Akhir</label>
                <input type="date"
                  class="form-control"
                  v-model="query.laporan_pembelian.tanggal_akhir"
                  >
              </div>
              <div class="form-group">
                <label>Barang</label>
                <select class="form-control" v-model="query.laporan_pembelian.kode_barang">
                  <option value="*">Semua Barang</option>
                  <option v-for="(barang, i) in data.barang" :key="i" :value="barang.kode_barang">
                    {{ barang.kode_barang }} - {{ barang.nama_barang }}
                  </option>
                </select>
              </div>
              <button class="btn btn-primary btn-block mt-3" :disabled="state.table.loading" @click="queryData">
                <spinner-component size="sm" color="light" v-if="state.table.loading"/>
                <i class="far fa-search" v-else></i>
              </button>
            </div>
          </div>

          <transition name="fade" mode="out-in" v-if="state.table.show">
            <div class="row mt-5 justify-content-center" v-if="state.table.loading">
              <spinner-component></spinner-component>
            </div>
            <div v-else>

              <div class="col" v-for="(dt, i) in data.laporan_pembelian.data">
                <h5 class="mt-5">{{ dt.barang.nama_barang }}</h5>
                <p>Harga Terendah: {{ dt.barang.lowest_price }}</p>
                <p>Cabang Dengan harga terendah: <template v-for="cabang in dt.barang.cabang">{{ cabang.nama_cabang }} ({{ $moment(cabang.tanggal).format('L') }}),</template></p>
                <div class="row">
                  <div class="table-responsive mt-2">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Cabang</th>
                          <th v-for="(date, i) in data.laporan_pembelian.dates">
                            {{ date }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(prices, i) in dt.items">
                          <td>{{ i + 1 }}</td>
                          <td>{{ dt.branches[i] }}</td>
                          <td
                            v-for="(price, j) in prices"
                            :class="{
                              'table-warning': price.range == 1,
                              'table-orange': price.range == 2,
                              'table-pink': price.range == 3,
                              'table-red text-light': price.range == 4,
                              'table-purple text-light': price.range == 5,
                            }"
                          >
                            {{ price.price }}
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
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      query: {
        laporan_pembelian: {
          tanggal_awal: this.$route.query.tanggal || this.$moment(this.$route.query.tanggal).format('YYYY-MM-DD'),
          tanggal_akhir: this.$route.query.tanggal || this.$moment(this.$route.query.tanggal).format('YYYY-MM-DD'),
        }
      },
      state: {
        table: {
          show: true,
          loading: false,
        },
        page: {
          loading: true
        },
      },
      data: {
        laporan_pembelian: {
          data: [],
          dates: [],
        },
        barang: [],
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
        this.fetchBarang()
      ])
        .then(res => {
          this.data.barang = res[0].data
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
      this.state.table.show = true
      if (withSpinner) this.state.table.loading = true
      this.fetchMainData()
        .then(res => {
          if ( ! this.$_.isEqual(this.$route.query, this.query.laporan_pembelian) && this.$route.name === 'reportCenter.laporanFormKhusus.laporanPembelianBarang') {
            this.$router.push({
              name: 'reportCenter.laporanFormKhusus.laporanPembelianBarang',
              query: this.query.laporan_pembelian
            })
          }
          this.data.laporan_pembelian = res.data
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/report_center/laporan_khusus/laporan_pembelian/by_range', { params: this.query.laporan_pembelian })
    },
    fetchBarang() {
      return this.$axios.get('/ajax/v1/report_center/laporan_khusus/laporan_pembelian/barang')
    },
  }
}
</script>

<style lang="css">
  .table-pink {
    background-color: pink;
  }
  .table-red {
    background-color: red;
  }
  .table-orange {
    background-color: orange;
  }
  .table-purple {
    background-color: purple;
  }
</style>