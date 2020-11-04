<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name == 'formOperasional.laporanCabang'">
      <div class="col-12 col-xl-12 stretch-card">
        <div class="card">
          <div class="card-body">
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
                            Tanggal Laporan
                          </span>
                        </div>
                        <input class="form-control" type="date" v-model="query.form_laporan_cabang.tanggal_form" @input="queryData"/>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- else -->
                <div class="row d-md-none">
                  <div class="col-12">
                    <div class="form-group">
                      <label>Tanggal Laporan</label>
                      <input class="form-control" type="date" v-model="query.form_laporan_cabang.tanggal_form" @input="queryData"/>
                    </div>
                  </div>
                </div>
                <div class="table-responsive mt-2">
                  <table class="table table-hover" v-if="$access('formOperasional.laporanCabang', 'read')">
                    <thead>
                      <th>#</th>
                      <th>Cabang</th>
                      <th>Keterangan</th>
                      <th>File</th>
                    </thead>
                    <tbody>
                      <tr v-for="(form_laporan_cabang, i) in data.form_laporan_cabang">
                        <td>{{ i + 1 }}</td>
                        <td>{{ form_laporan_cabang.cabang.kode_cabang }} - {{ form_laporan_cabang.cabang.cabang }}</td>
                        <td>{{ form_laporan_cabang.keterangan }}</td>
                      <td>
                        <a :href="'/ajax/v1/form_operasional/form_laporan_cabang/file/' + form_laporan_cabang.id" target="_blank"
                        >
                          Download
                        </a>
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
    <router-view v-else></router-view>
  </transition>
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
        form_laporan_cabang: [],
        cabang: []
      },
      form: {
        create: {
          data: {
            cabang_id: null,
            keterangan: '',
            file: null
          },
          errors: {},
          loading: false,
        },
      },
      query: {
        form_laporan_cabang: {
          tanggal_form: this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD') || this.$moment().format('YYYY-MM-DD')
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.form_laporan_cabang) && this.$route.name === 'formOperasional.laporanCabang') {
            this.$router.push({
              name: 'formOperasional.laporanCabang',
              query: this.query.form_laporan_cabang
            })
          }
          this.data.form_laporan_cabang = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_laporan_cabang/tanggal_form', { params: this.query.form_laporan_cabang })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
  }
}
</script>