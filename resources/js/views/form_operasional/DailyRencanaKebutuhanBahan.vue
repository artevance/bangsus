<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'formOperasional.dailyRencanaKebutuhanBahan'">
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
                          v-model="query.rencana_kebutuhan_bahan.tanggal_form"
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
                        v-model="query.rencana_kebutuhan_bahan.tanggal_form"
                        @keyup="queryData" @change="queryData"
                        >
                    </div>
                  </div>
                </div>
                <div class="table-responsive mt-2">
                  <table class="table table-hover" v-if="$access('formOperasional.dailyRencanaKebutuhanBahan', 'read')">
                    <thead>
                      <th>#</th>
                      <th>Jam</th>
                      <th>Cabang</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(rencana_kebutuhan_bahan, i) in data.rencana_kebutuhan_bahan">
                        <td>{{ i + 1 }}</td>
                        <td>{{ rencana_kebutuhan_bahan.jam }}</td>
                        <td>{{ rencana_kebutuhan_bahan.cabang.kode_cabang }} - {{ rencana_kebutuhan_bahan.cabang.cabang }}</td>
                        <td>{{ rencana_kebutuhan_bahan.status || '' }}</td>
                        <td>
                          <router-link class="badge badge-primary"
                            :to="{ name: 'formOperasional.dailyRencanaKebutuhanBahan.detail', params: { id: rencana_kebutuhan_bahan.id } }"
                            v-if="
                              $access('formOperasional.dailyRencanaKebutuhanBahan', 'detail')
                            ">
                            Detail
                          </router-link>
                          <router-link class="badge badge-info"
                            :to="{ name: 'formOperasional.dailyRencanaKebutuhanBahan.accept', params: { id: rencana_kebutuhan_bahan.id } }"
                            v-if="
                              $access('formOperasional.dailyRencanaKebutuhanBahan', 'accept') && (
                                $access('formOperasional.dailyRencanaKebutuhanBahan.accept', 'timeFree') ||
                                $moment($moment(query.rencana_kebutuhan_bahan.tanggal_form)).isBetween(
                                  $moment(utils.date).subtract($access('formOperasional.dailyRencanaKebutuhanBahan.accept', 'dateMin')).format('YYYY-MM-DD'),
                                  $moment(utils.date).add($access('formOperasional.dailyRencanaKebutuhanBahan.accept', 'dateMax')).format('YYYY-MM-DD'),
                                  undefined,
                                  '[]'
                                )
                              ) && rencana_kebutuhan_bahan.accepted == 0
                            ">
                            Accept
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
        rencana_kebutuhan_bahan: [],
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
        rencana_kebutuhan_bahan: {
          tanggal_form: this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'timeFree')
            ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
            : (
              this.$moment(this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')).isBetween(
                this.$moment().subtract(this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'dateMin')).format('YYYY-MM-DD'),
                this.$moment().add(this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'dateMax')).format('YYYY-MM-DD'),
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

    if (this.$access('formOperasional.dailyRencanaKebutuhanBahan', 'create') && this.$access('formOperasional.dailyRencanaKebutuhanBahan.create', 'automatedTime')) {
      this.setCreateClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.create.data.jam)
  },

  watch: {
    'query.rencana_kebutuhan_bahan.tanggal_form'(n, o) {
      if (this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'timeFree')) {
        this.query.rencana_kebutuhan_bahan.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        ) {
          this.query.rencana_kebutuhan_bahan.tanggal_form = n
        } else {
          this.query.rencana_kebutuhan_bahan.tanggal_form = o
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.rencana_kebutuhan_bahan) && this.$route.name === 'formOperasional.dailyRencanaKebutuhanBahan') {
            this.$router.push({
              name: 'formOperasional.dailyRencanaKebutuhanBahan',
              query: this.query.rencana_kebutuhan_bahan
            })
          }
          this.data.rencana_kebutuhan_bahan = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/rencana_kebutuhan_bahan/harian', { params: this.query.rencana_kebutuhan_bahan })
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
      $('[data-entity="dailyRencanaKebutuhanBahan"][data-method="approve"]').modal('show')
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="dailyRencanaKebutuhanBahan"][data-method="destroy"]').modal('show')
    },
    hideApproveModal() {
      $('[data-entity="dailyRencanaKebutuhanBahan"][data-method="approve"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="dailyRencanaKebutuhanBahan"][data-method="destroy"]').modal('hide')
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
      if (this.$access('formOperasional.dailyRencanaKebutuhanBahan', 'read')) {
        if (this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyRencanaKebutuhanBahan.read', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.query.rencana_kebutuhan_bahan.tanggal_form = this.$moment().format('YYYY-MM-DD')
            this.queryData()
          }
        }
      }
      // Handle date change on create action
      if (this.$access('formOperasional.dailyRencanaKebutuhanBahan', 'create')) {
        if (this.$access('formOperasional.dailyRencanaKebutuhanBahan.create', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyRencanaKebutuhanBahan.create', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyRencanaKebutuhanBahan.create', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.dailyRencanaKebutuhanBahan', 'update')) {
        if (this.$access('formOperasional.dailyRencanaKebutuhanBahan.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyRencanaKebutuhanBahan.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyRencanaKebutuhanBahan.update', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.dailyRencanaKebutuhanBahan', 'destroy')) {
        if (this.$access('formOperasional.dailyRencanaKebutuhanBahan.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyRencanaKebutuhanBahan.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyRencanaKebutuhanBahan.destroy', 'dateMax')).format('YYYY-MM-DD'),
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
      this.$axios.put('/ajax/v1/form_operasional/rencana_kebutuhan_bahan/approve', this.form.approve.data)
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
      this.$axios.delete('/ajax/v1/form_operasional/rencana_kebutuhan_bahan', { data: this.form.destroy.data })
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