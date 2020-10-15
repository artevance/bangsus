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
                      Cabang
                    </span>
                  </div>
                  <select class="form-control" v-model="query.laporan_form_c4.cabang_id">
                    <option value="null" v-if="$access('reportCenter.laporanFormOperasional.laporanFormC4', 'readAllBranch')">Semua Cabang</option>
                    <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                      {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                    </option>
                  </select>
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Jenis Laporan
                    </span>
                  </div>
                  <select class="form-control" v-model="query.laporan_form_c4.report_type">
                    <option value="frequency">
                      Laporan Frekuensi
                    </option>
                  </select>
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Tanggal Awal
                    </span>
                  </div>
                  <input type="date"
                    class="form-control"
                    v-model="query.laporan_form_c4.tanggal_awal"
                    >
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      Tanggal Akhir
                    </span>
                  </div>
                  <input type="date"
                    class="form-control"
                    v-model="query.laporan_form_c4.tanggal_akhir"
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
                <label>Cabang</label>
                <select class="form-control" v-model="query.laporan_form_c4.cabang_id">
                  <option value="null" v-if="$access('reportCenter.laporanFormOperasional.laporanFormC4', 'readAllBranch')">Semua Cabang</option>
                  <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                    {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label>Tipe Absensi</label>
                <select class="form-control" v-model="query.laporan_form_c4.report_type">
                  <option value="frequency">
                    Laporan Frekuensi
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label>Tanggal Awal</label>
                <input type="date"
                  class="form-control"
                  v-model="query.laporan_form_c4.tanggal_awal"
                  >
              </div>
              <div class="form-group">
                <label>Tanggal Akhir</label>
                <input type="date"
                  class="form-control"
                  v-model="query.laporan_form_c4.tanggal_akhir"
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
              <button class="btn btn-primary mt-5" :disabled="state.export.loading" @click="exportData('xlsx')">
                <spinner-component size="sm" color="light" v-if="state.export.loading"/>
                <i class="far fa-file-excel" v-else></i> Ekspor
              </button>
              <div class="table-responsive mt-2">
                <table class="table table-bordered" v-if="query.laporan_form_c4.report_type == 'frequency'">
                  <thead>
                    <th>#</th>
                    <th>Cabang</th>
                    <th v-for="(kegiatan_kebersihan, i) in data.laporan_form_c4.meta.kegiatan_kebersihan">
                      {{ i + 1 }}
                    </th>
                    <th>Frekuensi</th>
                    <th>Total Skor</th>
                    <th>Skor Rata-Rata</th>
                  </thead>
                  <tbody>
                    <tr v-for="(data, i) in data.laporan_form_c4.data">
                      <td class="text-center">{{ i + 1 }}</td>
                      <td>{{ data.cabang.kode_cabang }} - {{ data.cabang.cabang }}</td>
                      <template v-for="graph in data.graph">
                        <td :class="{ 'table-danger': graph == 0 }">{{ graph }}</td>
                      </template>
                      <td>{{ data.frequency }}</td>
                      <td>{{ data.total_skor }}</td>
                      <td>{{ data.skor_rr }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <ol>
                <li v-for="(kegiatan_kebersihan, i) in data.laporan_form_c4.meta.kegiatan_kebersihan">
                  {{ kegiatan_kebersihan.kegiatan_kebersihan }}
                </li>
              </ol>
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
      state: {
        page: {
          loading: true
        },
        table: {
          loading: false,
          show: false
        },
        export: {
          loading: false
        }
      },
      data: {
        cabang: [],
        tipe_absensi: []
      },
      query: {
        laporan_form_c4: {
          cabang_id: this.$route.query.cabang_id || null,
          report_type: this.$route.query.report_type || 'frequency',
          tanggal_awal: this.$route.query.tanggal_awal || this.$moment(this.$route.query.tanggal_awal).format('YYYY-MM-DD'),
          tanggal_akhir: this.$route.query.tanggal_akhir || this.$moment(this.$route.query.tanggal_akhir).format('YYYY-MM-DD')
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

          if (this.data.cabang.length <= 0) {
            this.$router.go(-1)
          }

          if (this.query.laporan_form_c4.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.laporan_form_c4.cabang_id }))) {
            this.query.laporan_form_c4.cabang_id = this.data.cabang[0].id || null
          }

          // this.queryData()
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.laporan_form_c4) && this.$route.name === 'reportCenter.laporanFormOperasional.laporanFormC4') {
            this.$router.push({
              name: 'reportCenter.laporanFormOperasional.laporanFormC4',
              query: this.query.laporan_form_c4
            })
          }
          this.data.laporan_form_c4 = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },
    exportData(format) {
      this.state.export.loading = true
      this.fetchMainDataExport(format)
        .then(res => {
          let type;
          switch (format) {
            case 'xlsx' :
              type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
          }

          const url = URL.createObjectURL(new Blob([res.data], {
            type: type
          }))
          let a = document.createElement('a')
          a.href = url
          a.click()
          window.URL.revokeObjectURL(url)
        })
        .finally(() => {
          this.state.export.loading = false
        })
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/report_center/laporan_form_operasional/laporan_form_c4', { params: this.query.laporan_form_c4 })
    },
    fetchMainDataExport(format) {
      let params = {
        cabang_id: this.query.laporan_form_c4.cabang_id,
        report_type: this.query.laporan_form_c4.report_type,
        tanggal_awal: this.query.laporan_form_c4.tanggal_awal,
        tanggal_akhir: this.query.laporan_form_c4.tanggal_akhir,
        export: format
      }
      return this.$axios.get('/ajax/v1/report_center/laporan_form_operasional/laporan_form_c4', { params: params, responseType: 'blob' })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
  }
}
</script>