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
                      Tanggal
                    </span>
                  </div>
                  <input type="date"
                    class="form-control"
                    v-model="query.laporan_pembelian.tanggal"
                    >
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
                <label>Tanggal</label>
                <input type="date"
                  class="form-control"
                  v-model="query.laporan_pembelian.tanggal"
                  >
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
              <div class="table-responsive mt-2">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Barang</th>
                      <th>Harga Terendah</th>
                      <th v-for="(branch, i) in data.laporan_pembelian.branches">
                        {{ branch }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(barang, i) in data.laporan_pembelian.items">
                      <td>{{ i + 1 }}</td>
                      <td>{{ barang.nama_barang }}</td>
                      <td>{{ barang.lowest_price }}</td>
                      <td
                        v-for="(price, j) in barang.prices"
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
          tanggal: this.$route.query.tanggal || this.$moment(this.$route.query.tanggal).format('YYYY-MM-DD'),
        }
      },
      state: {
        table: {
          show: true,
          loading: false,
        }
      },
      data: {
        laporan_pembelian: {
          items: [],
          branches: [],
        },
      }
    }
  },
  methods: {
    /**
     *  Query result.
     */
    queryData(withSpinner = true) {
      this.state.table.show = true
      if (withSpinner) this.state.table.loading = true
      this.fetchMainData()
        .then(res => {
          if ( ! this.$_.isEqual(this.$route.query, this.query.laporan_pembelian) && this.$route.name === 'reportCenter.laporanFormKhusus.laporanPembelian') {
            this.$router.push({
              name: 'reportCenter.laporanFormKhusus.laporanPembelian',
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
      return this.$axios.get('/ajax/v1/report_center/laporan_khusus/laporan_pembelian', { params: this.query.laporan_pembelian })
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