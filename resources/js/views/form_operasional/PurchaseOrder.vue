<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name === 'formOperasional.purchaseOrder'">
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
                            Cabang
                          </span>
                        </div>
                        <select class="form-control" v-model="query.purchase_order.cabang_id" @change="queryData">
                          <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                            {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                          </option>
                        </select>
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            Tanggal Form
                          </span>
                        </div>
                        <input type="date"
                          class="form-control"
                          v-model="query.purchase_order.tanggal_form"
                          @keyup="queryData" @change="queryData"
                          :min="
                            $access('formOperasional.purchaseOrder.read', 'timeFree')
                              ? false
                              : $moment().subtract($access('formOperasional.purchaseOrder.read', 'minDate')).format('YYYY-MM-DD')
                          "
                          :max="
                            $access('formOperasional.purchaseOrder.read', 'timeFree')
                              ? false
                              : $moment().subtract($access('formOperasional.purchaseOrder.read', 'maxDate')).format('YYYY-MM-DD')
                          "
                          >
                      </div>
                    </div>
                  </div>
                </div>
                <!-- else -->
                <div class="row d-md-none">
                  <div class="col-12">
                    <div class="form-group">
                      <label>Cabang</label>
                      <select class="form-control" v-model="query.purchase_order.cabang_id" @change="queryData">
                        <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                          {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                        </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Tanggal Form</label>
                      <input type="date"
                        class="form-control"
                        v-model="query.purchase_order.tanggal_form"
                        @keyup="queryData" @change="queryData"
                        :min="
                          $access('formOperasional.purchaseOrder.read', 'timeFree')
                            ? false
                            : $moment().subtract($access('formOperasional.purchaseOrder.read', 'minDate')).format('YYYY-MM-DD')
                        "
                        :max="
                          $access('formOperasional.purchaseOrder.read', 'timeFree')
                            ? false
                            : $moment().subtract($access('formOperasional.purchaseOrder.read', 'maxDate')).format('YYYY-MM-DD')
                        "
                        >
                    </div>
                  </div>
                </div>
                <router-link class="btn btn-primary"
                  :to="{ name: 'formOperasional.purchaseOrder.create' }"
                  v-if="
                    $access('formOperasional.purchaseOrder', 'create') && (
                      $access('formOperasional.purchaseOrder.create', 'timeFree') ||
                      $moment($moment(query.purchase_order.tanggal_form)).isBetween(
                        $moment(utils.date).subtract($access('formOperasional.purchaseOrder.create', 'dateMin')).format('YYYY-MM-DD'),
                        $moment(utils.date).add($access('formOperasional.purchaseOrder.create', 'dateMax')).format('YYYY-MM-DD'),
                        undefined,
                        '[]'
                      )
                    )
                  ">
                  Tambah
                </router-link>
                <div class="table-responsive mt-2">
                  <table class="table table-hover" v-if="$access('formOperasional.purchaseOrder', 'read')">
                    <thead>
                      <th>#</th>
                      <th>Jam</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(purchase_order, i) in data.purchase_order">
                        <td>{{ i + 1 }}</td>
                        <td>{{ purchase_order.jam }}</td>
                        <td>{{ purchase_order.status || '' }}</td>
                        <td>
                          <router-link class="badge badge-primary"
                            :to="{ name: 'formOperasional.purchaseOrder.detail', params: { id: purchase_order.id } }"
                            v-if="
                              $access('formOperasional.purchaseOrder', 'detail')
                            ">
                            Detail
                          </router-link>
                          <router-link class="badge badge-warning"
                            :to="{ name: 'formOperasional.purchaseOrder.update', params: { id: purchase_order.id } }"
                            v-if="
                              $access('formOperasional.purchaseOrder', 'update') && (
                                $access('formOperasional.purchaseOrder.update', 'timeFree') ||
                                $moment($moment(query.purchase_order.tanggal_form)).isBetween(
                                  $moment(utils.date).subtract($access('formOperasional.purchaseOrder.update', 'dateMin')).format('YYYY-MM-DD'),
                                  $moment(utils.date).add($access('formOperasional.purchaseOrder.update', 'dateMax')).format('YYYY-MM-DD'),
                                  undefined,
                                  '[]'
                                )
                              ) && !purchase_order.approve
                            ">
                            Ubah
                          </router-link>
                          <a class="badge badge-success"
                            @click="showApproveModal(purchase_order.id)"
                            href="#"
                            v-if="
                              $access('formOperasional.purchaseOrder', 'update') && (
                                $access('formOperasional.purchaseOrder.update', 'timeFree') ||
                                $moment($moment(query.purchase_order.tanggal_form)).isBetween(
                                  $moment(utils.date).subtract($access('formOperasional.purchaseOrder.update', 'dateMin')).format('YYYY-MM-DD'),
                                  $moment(utils.date).add($access('formOperasional.purchaseOrder.update', 'dateMax')).format('YYYY-MM-DD'),
                                  undefined,
                                  '[]'
                                )
                              ) && !purchase_order.approve
                            ">
                            Approve
                          </a>
                          <a class="badge badge-danger"
                            @click="showDestroyModal(purchase_order.id)"
                            href="#"
                            v-if="
                              $access('formOperasional.purchaseOrder', 'destroy') && (
                                $access('formOperasional.purchaseOrder.destroy', 'timeFree') ||
                                $moment($moment(query.purchase_order.tanggal_form)).isBetween(
                                  $moment(utils.date).subtract($access('formOperasional.purchaseOrder.destroy', 'dateMin')).format('YYYY-MM-DD'),
                                  $moment(utils.date).add($access('formOperasional.purchaseOrder.destroy', 'dateMax')).format('YYYY-MM-DD'),
                                  undefined,
                                  '[]'
                                )
                              ) && !purchase_order.approve
                            ">
                            Hapus
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

      <div class="modal fade"
        data-entity="purchaseOrder"
        data-method="approve"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        v-if="
          $access('formOperasional.purchaseOrder', 'approve') && (
            $access('formOperasional.purchaseOrder.approve', 'timeFree') ||
            $moment($moment(query.purchase_order.tanggal_form)).isBetween(
              $moment().subtract($access('formOperasional.purchaseOrder.approve', 'dateMin')).format('YYYY-MM-DD'),
              $moment().add($access('formOperasional.purchaseOrder.approve', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          )
        ">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="approve">
              <div class="modal-header">
                <h5 class="modal-title">Approve Purchase Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Apakah anda yakin?</p>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.approve.loading">
                  <spinner-component size="sm" color="light" v-if="form.approve.loading"/>
                  Approve
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade"
        data-entity="purchaseOrder"
        data-method="destroy"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        v-if="
          $access('formOperasional.purchaseOrder', 'destroy') && (
            $access('formOperasional.purchaseOrder.destroy', 'timeFree') ||
            $moment($moment(query.purchase_order.tanggal_form)).isBetween(
              $moment().subtract($access('formOperasional.purchaseOrder.destroy', 'dateMin')).format('YYYY-MM-DD'),
              $moment().add($access('formOperasional.purchaseOrder.destroy', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          )
        ">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="destroy">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Purchase Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p>Apakah anda yakin?</p>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.destroy.loading">
                  <spinner-component size="sm" color="light" v-if="form.destroy.loading"/>
                  Hapus
                </button>
              </div>
            </form>
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
          cabang_id: this.$route.query.cabang_id || null,
          tanggal_form: this.$access('formOperasional.purchaseOrder.read', 'timeFree')
            ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
            : (
              this.$moment(this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')).isBetween(
                this.$moment().subtract(this.$access('formOperasional.purchaseOrder.read', 'dateMin')).format('YYYY-MM-DD'),
                this.$moment().add(this.$access('formOperasional.purchaseOrder.read', 'dateMax')).format('YYYY-MM-DD'),
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

    if (this.$access('formOperasional.purchaseOrder', 'create') && this.$access('formOperasional.purchaseOrder.create', 'automatedTime')) {
      this.setCreateClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.create.data.jam)
  },

  watch: {
    'query.purchase_order.tanggal_form'(n, o) {
      if (this.$access('formOperasional.purchaseOrder.read', 'timeFree')) {
        this.query.purchase_order.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.purchaseOrder.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.purchaseOrder.read', 'dateMax')).format('YYYY-MM-DD'),
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

          if (this.query.purchase_order.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.purchase_order.cabang_id }))) {
            this.query.purchase_order.cabang_id = this.data.cabang[0].id || null
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.purchase_order) && this.$route.name === 'formOperasional.purchaseOrder') {
            this.$router.push({
              name: 'formOperasional.purchaseOrder',
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
      return this.$axios.get('/ajax/v1/form_operasional/purchase_order/cabang_harian', { params: this.query.purchase_order })
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
      $('[data-entity="purchaseOrder"][data-method="approve"]').modal('show')
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="purchaseOrder"][data-method="destroy"]').modal('show')
    },
    hideApproveModal() {
      $('[data-entity="purchaseOrder"][data-method="approve"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="purchaseOrder"][data-method="destroy"]').modal('hide')
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
      if (this.$access('formOperasional.purchaseOrder', 'read')) {
        if (this.$access('formOperasional.purchaseOrder.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.purchaseOrder.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.purchaseOrder.read', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.purchaseOrder', 'create')) {
        if (this.$access('formOperasional.purchaseOrder.create', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.purchaseOrder.create', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.purchaseOrder.create', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.purchaseOrder', 'update')) {
        if (this.$access('formOperasional.purchaseOrder.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.purchaseOrder.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.purchaseOrder.update', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.purchaseOrder', 'destroy')) {
        if (this.$access('formOperasional.purchaseOrder.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.purchaseOrder.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.purchaseOrder.destroy', 'dateMax')).format('YYYY-MM-DD'),
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
  }
}
</script>