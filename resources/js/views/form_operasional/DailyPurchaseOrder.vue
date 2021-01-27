<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'formOperasional.dailyPurchaseOrder'">
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
                          v-model="query.purchase_order.tanggal_form"
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
                        v-model="query.purchase_order.tanggal_form"
                        @keyup="queryData" @change="queryData"
                        >
                    </div>
                  </div>
                </div>
                <div class="table-responsive mt-2">
                  <table class="table table-hover" v-if="$access('formOperasional.dailyPurchaseOrder', 'read')">
                    <thead>
                      <th>#</th>
                      <th>Jam</th>
                      <th>Cabang</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(purchase_order, i) in data.purchase_order">
                        <td>{{ i + 1 }}</td>
                        <td>{{ purchase_order.jam }}</td>
                        <td>{{ purchase_order.cabang.kode_cabang }} - {{ purchase_order.cabang.cabang }}</td>
                        <td>{{ purchase_order.status || '' }}</td>
                        <td>
                          <router-link class="badge badge-primary"
                            :to="{ name: 'formOperasional.dailyPurchaseOrder.detail', params: { id: purchase_order.id } }"
                            v-if="
                              $access('formOperasional.dailyPurchaseOrder', 'detail')
                            ">
                            Detail
                          </router-link>
                          <router-link class="badge badge-info"
                            :to="{ name: 'formOperasional.dailyPurchaseOrder.accept', params: { id: purchase_order.id } }"
                            v-if="
                              $access('formOperasional.dailyPurchaseOrder', 'accept') && (
                                $access('formOperasional.dailyPurchaseOrder.accept', 'timeFree') ||
                                $moment($moment(query.purchase_order.tanggal_form)).isBetween(
                                  $moment(utils.date).subtract($access('formOperasional.dailyPurchaseOrder.accept', 'dateMin')).format('YYYY-MM-DD'),
                                  $moment(utils.date).add($access('formOperasional.dailyPurchaseOrder.accept', 'dateMax')).format('YYYY-MM-DD'),
                                  undefined,
                                  '[]'
                                )
                              ) && purchase_order.accepted == 0
                            ">
                            Accept
                          </router-link>
                          <a class="badge badge-dark" href="#" @click.prevent="exportData(purchase_order.id)">
                            Ekspor
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
        cabang: [],
        purchase_order: [],
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
        purchase_order: {
          tanggal_form: this.$access('formOperasional.dailyPurchaseOrder.read', 'timeFree')
            ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
            : (
              this.$moment(this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')).isBetween(
                this.$moment().subtract(this.$access('formOperasional.dailyPurchaseOrder.read', 'dateMin')).format('YYYY-MM-DD'),
                this.$moment().add(this.$access('formOperasional.dailyPurchaseOrder.read', 'dateMax')).format('YYYY-MM-DD'),
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

    if (this.$access('formOperasional.dailyPurchaseOrder', 'create') && this.$access('formOperasional.dailyPurchaseOrder.create', 'automatedTime')) {
      this.setCreateClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.create.data.jam)
  },

  watch: {
    'query.purchase_order.tanggal_form'(n, o) {
      if (this.$access('formOperasional.dailyPurchaseOrder.read', 'timeFree')) {
        this.query.purchase_order.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.dailyPurchaseOrder.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.dailyPurchaseOrder.read', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        ) {
          this.query.purchase_order.tanggal_form = n
        } else {
          this.query.purchase_order.tanggal_form = o
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.purchase_order) && this.$route.name === 'formOperasional.dailyPurchaseOrder') {
            this.$router.push({
              name: 'formOperasional.dailyPurchaseOrder',
              query: this.query.purchase_order
            })
          }
          this.data.purchase_order = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/purchase_order/harian', { params: this.query.purchase_order })
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
      $('[data-entity="dailyPurchaseOrder"][data-method="approve"]').modal('show')
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="dailyPurchaseOrder"][data-method="destroy"]').modal('show')
    },
    hideApproveModal() {
      $('[data-entity="dailyPurchaseOrder"][data-method="approve"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="dailyPurchaseOrder"][data-method="destroy"]').modal('hide')
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
      if (this.$access('formOperasional.dailyPurchaseOrder', 'read')) {
        if (this.$access('formOperasional.dailyPurchaseOrder.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyPurchaseOrder.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyPurchaseOrder.read', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.query.purchase_order.tanggal_form = this.$moment().format('YYYY-MM-DD')
            this.queryData()
          }
        }
      }
      // Handle date change on create action
      if (this.$access('formOperasional.dailyPurchaseOrder', 'create')) {
        if (this.$access('formOperasional.dailyPurchaseOrder.create', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyPurchaseOrder.create', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyPurchaseOrder.create', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.dailyPurchaseOrder', 'update')) {
        if (this.$access('formOperasional.dailyPurchaseOrder.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyPurchaseOrder.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyPurchaseOrder.update', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.dailyPurchaseOrder', 'destroy')) {
        if (this.$access('formOperasional.dailyPurchaseOrder.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.dailyPurchaseOrder.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.dailyPurchaseOrder.destroy', 'dateMax')).format('YYYY-MM-DD'),
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
      this.$axios.put('/ajax/v1/form_operasional/purchase_order/approve', this.form.approve.data)
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
      this.$axios.delete('/ajax/v1/form_operasional/purchase_order', { data: this.form.destroy.data })
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
    exportData(id) {
      this.$axios.get('/ajax/v1/form_operasional/purchase_order/report/' + id, { responseType: 'blob' })
        .then(res => {
          let type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'

          const url = URL.createObjectURL(new Blob([res.data], {
            type: type
          }))
          let a = document.createElement('a')
          a.href = url
          a.click()
          window.URL.revokeObjectURL(url)
        })
    },
  }
}
</script>