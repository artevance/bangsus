<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'formOperasional.dailyStokOpname'">
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
                            Tanggal Form
                          </span>
                        </div>
                        <input type="date"
                          class="form-control"
                          v-model="query.stok_opname.tanggal_form"
                          @keyup="queryData" @change="queryData"
                          >
                      </div>
                    </div>
                  </div>
                </div>
                <!-- else -->
                <div class="row d-md-none">
                  <div class="col-12">
                    <div class="form-group">
                      <label>Tanggal Form</label>
                      <input type="date"
                        class="form-control"
                        v-model="query.stok_opname.tanggal_form"
                        @keyup="queryData" @change="queryData"
                        >
                    </div>
                  </div>
                </div>
                <div class="table-responsive mt-2">
                  <table class="table table-hover" v-if="$access('formOperasional.stokOpname', 'read')">
                    <thead>
                      <th>#</th>
                      <th>Cabang</th>
                      <th>Jam</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(stok_opname, i) in data.stok_opname">
                        <td>{{ i + 1 }}</td>
                        <td>{{ stok_opname.cabang.kode_cabang }} - {{ stok_opname.cabang.cabang }}</td>
                        <td>{{ stok_opname.jam }}</td>
                        <td>{{ stok_opname.status || '' }}</td>
                        <td>
                          <router-link class="badge badge-primary"
                            :to="{ name: 'formOperasional.dailyStokOpname.detail', params: { id: stok_opname.id } }"
                            v-if="
                              $access('formOperasional.dailyStokOpname', 'detail')
                            ">
                            Detail
                          </router-link>
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
        cabang: [],
        stok_opname: [],
        tugas_karyawan: [],
        aktivitas_marketing: [],
        satuan: [],
        item_marketing: []
      },
      form: {
        approve: {
          data: {
            id: null
          },
          errors: {},
          loading: false
        },
        destroy: {
          data: {
            id: null
          },
          errors: {},
          loading: false
        }
      },
      query: {
        stok_opname: {
          tanggal_form: this.$access('formOperasional.stokOpname.read', 'timeFree')
            ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
            : (
              this.$moment(this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')).isBetween(
                this.$moment().subtract(this.$access('formOperasional.stokOpname.read', 'dateMin')).format('YYYY-MM-DD'),
                this.$moment().add(this.$access('formOperasional.stokOpname.read', 'dateMax')).format('YYYY-MM-DD'),
                undefined,
                '[]'
              )
                ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
                : this.$moment().format('YYYY-MM-DD')
            )
        }
      },
      interval: {
        form: {
          create: {
            data: {
              jam: null
            }
          },
          update: {
            data: {
              jam: null
            }
          }
        },
        utils: {
          date: null
        }
      },
      utils: {
        date: this.$moment().format('YYYY-MM-DD')
      }
    }
  },
  created() {
    this.prepare()
  },
  mounted() {
    this.setDateWatcher()

    if (this.$access('formOperasional.stokOpname', 'create') && this.$access('formOperasional.stokOpname.create', 'automatedTime')) {
      this.setCreateClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.create.data.jam)
  },

  watch: {
    'query.stok_opname.tanggal_form'(n, o) {
      if (this.$access('formOperasional.stokOpname.read', 'timeFree')) {
        this.query.stok_opname.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.stokOpname.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.stokOpname.read', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        ) {
          this.query.stok_opname.tanggal_form = n
        } else {
          this.query.stok_opname.tanggal_form = o
        }
      }
    }
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.stok_opname) && this.$route.name === 'formOperasional.dailyStokOpname') {
            this.$router.push({
              name: 'formOperasional.dailyStokOpname',
              query: this.query.stok_opname
            })
          }
          this.data.stok_opname = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/stok_opname/harian', { params: this.query.stok_opname })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchTugasKaryawan(id, tanggal_penugasan) {
      return this.$axios.get('/ajax/v1/tugas_karyawan/cabang/?cabang_id=' + id + '&tanggal_penugasan=' + tanggal_penugasan)
    },
    fetchAktivitasMarketing() {
      return this.$axios.get('/ajax/v1/master/aktivitas_marketing')
    },
    fetchSatuan() {
      return this.$axios.get('/ajax/v1/master/satuan')
    },
    fetchItemMarketing() {
      return this.$axios.get('/ajax/v1/master/item_marketing')
    },

    /**
     *  Modal functionality & utils
     */
    showApproveModal(id) {
      this.form.approve.data.id = id
      $('[data-entity="stokOpname"][data-method="approve"]').modal('show')
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="stokOpname"][data-method="destroy"]').modal('show')
    },
    hideApproveModal() {
      $('[data-entity="stokOpname"][data-method="approve"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="stokOpname"][data-method="destroy"]').modal('hide')
    },
    setCreateClockInterval() {
      this.interval.form.create.data.jam = setInterval(function () {
        this.form.create.data.jam = this.$moment().format('HH:mm:ss')
      }.bind(this), 1000)
    },
    setDateWatcher() {
      this.interval.utils.date = setInterval(function () {
        let o = this.utils.date
        let n = this.$moment().format('YYYY-MM-DD')

        if ( ! this.$moment(n).isSame(o)) {
          this.dateChangeHandler()
        }

        this.utils.date = n
      }.bind(this), 1000)
    },
    dateChangeHandler() {
      // Handle date change on read action
      if (this.$access('formOperasional.stokOpname', 'read')) {
        if (this.$access('formOperasional.stokOpname.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.stokOpname.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.stokOpname.read', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.query.stok_opname.tanggal_form = this.$moment().format('YYYY-MM-DD')
            this.queryData()
          }
        }
      }
      // Handle date change on create action
      if (this.$access('formOperasional.stokOpname', 'create')) {
        if (this.$access('formOperasional.stokOpname.create', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.stokOpname.create', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.stokOpname.create', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.hideCreateModal()
          }
        }
      }
      // Handle data change on update action
      if (this.$access('formOperasional.stokOpname', 'update')) {
        if (this.$access('formOperasional.stokOpname.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.stokOpname.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.stokOpname.update', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.hideUpdateModal()
          }
        }
      }
      // Handle data change on destroy action
      if (this.$access('formOperasional.stokOpname', 'destroy')) {
        if (this.$access('formOperasional.stokOpname.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.stokOpname.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.stokOpname.destroy', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.hideDestroyModal()
          }
        }
      } 
    },

    /**
     *  Form request handler
     */
    approve() {
      this.form.approve.loading = true
      this.form.approve.errors = {}
      this.$axios.put('/ajax/v1/form_operasional/stok_opname/approve', this.form.approve.data)
        .then(res => {
          this.form.approve.data = {
            id: null
          }
          this.queryData(false)
          this.hideApproveModal()
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.approve.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.approve.loading = false
        })
    },
    destroy() {
      this.form.destroy.loading = true
      this.form.destroy.errors = {}
      this.$axios.delete('/ajax/v1/form_operasional/stok_opname', { data: this.form.destroy.data })
        .then(res => {
          this.form.destroy.data.id = null
          this.queryData(false)
          this.hideDestroyModal()
        })
        .catch(err => {})
        .finally(() => {
          this.form.destroy.loading = false
        })
    },
  }
}
</script>