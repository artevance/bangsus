<template>
  <div class="row mt-5">
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
                      <select class="form-control" v-model="query.form_c2.cabang_id" @change="queryData">
                        <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                          {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Quality Control
                        </span>
                      </div>
                      <select class="form-control" v-model="query.form_c2.quality_control_id" @change="queryData">
                        <option v-for="(quality_control, i) in data.quality_control" :key="i" :value="quality_control.id">
                          {{ quality_control.quality_control }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Tanggal Form
                        </span>
                      </div>
                      <input type="date"
                        class="form-control"
                        v-model="query.form_c2.tanggal_form"
                        @keyup="queryData" @change="queryData"
                        :min="
                          $access('formOperasional.formC2.read', 'timeFree')
                            ? false
                            : $moment().subtract($access('formOperasional.formC2.read', 'minDate')).format('YYYY-MM-DD')
                        "
                        :max="
                          $access('formOperasional.formC2.read', 'timeFree')
                            ? false
                            : $moment().subtract($access('formOperasional.formC2.read', 'maxDate')).format('YYYY-MM-DD')
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
                    <select class="form-control" v-model="query.form_c2.cabang_id" @change="queryData">
                      <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                        {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Quality Control</label>
                    <select class="form-control" v-model="query.form_c2.quality_control_id" @change="queryData">
                      <option v-for="(quality_control, i) in data.quality_control" :key="i" :value="quality_control.id">
                        {{ quality_control.quality_control }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Tanggal Form</label>
                    <input type="date"
                      class="form-control"
                      v-model="query.form_c2.tanggal_form"
                      @keyup="queryData" @change="queryData"
                      :min="
                        $access('formOperasional.formC2.read', 'timeFree')
                          ? false
                          : $moment().subtract($access('formOperasional.formC2.read', 'minDate')).format('YYYY-MM-DD')
                      "
                      :max="
                        $access('formOperasional.formC2.read', 'timeFree')
                          ? false
                          : $moment().subtract($access('formOperasional.formC2.read', 'maxDate')).format('YYYY-MM-DD')
                      "
                      >
                  </div>
                </div>
              </div>
              <button class="btn btn-primary"
                @click="showCreateModal"
                v-if="
                  $access('formOperasional.formC2', 'create') && (
                    $access('formOperasional.formC2.create', 'timeFree') ||
                    $moment($moment(query.form_c2.tanggal_form)).isBetween(
                      $moment(utils.date).subtract($access('formOperasional.formC2.create', 'dateMin')).format('YYYY-MM-DD'),
                      $moment(utils.date).add($access('formOperasional.formC2.create', 'dateMax')).format('YYYY-MM-DD'),
                      undefined,
                      '[]'
                    )
                  )
                ">
                Tambah
              </button>
              <div class="table-responsive mt-2">
                <table class="table table-hover" v-if="$access('formOperasional.formC2', 'read')">
                  <thead>
                    <th>#</th>
                    <th>NIP</th>
                    <th>Nama Karyawan</th>
                    <th>Jam</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(form_c2, i) in data.form_c2">
                      <td>{{ i + 1 }}</td>
                      <td>{{ form_c2.tugas_karyawan.karyawan.nip }}</td>
                      <td>{{ form_c2.tugas_karyawan.karyawan.nama_karyawan }}</td>
                      <td>{{ form_c2.jam }}</td>
                      <td>
                        <a class="badge badge-warning"
                          @click="showUpdateModal(form_c2.id)"
                          href="#"
                          v-if="
                            $access('formOperasional.formC2', 'update') && (
                              $access('formOperasional.formC2.update', 'timeFree') ||
                              $moment($moment(query.form_c2.tanggal_form)).isBetween(
                                $moment(utils.date).subtract($access('formOperasional.formC2.update', 'dateMin')).format('YYYY-MM-DD'),
                                $moment(utils.date).add($access('formOperasional.formC2.update', 'dateMax')).format('YYYY-MM-DD'),
                                undefined,
                                '[]'
                              )
                            )
                          ">
                          Ubah
                        </a>
                        <a class="badge badge-danger"
                          @click="showDestroyModal(form_c2.id)"
                          href="#"
                          v-if="
                            $access('formOperasional.formC2', 'destroy') && (
                              $access('formOperasional.formC2.destroy', 'timeFree') ||
                              $moment($moment(query.form_c2.tanggal_form)).isBetween(
                                $moment(utils.date).subtract($access('formOperasional.formC2.destroy', 'dateMin')).format('YYYY-MM-DD'),
                                $moment(utils.date).add($access('formOperasional.formC2.destroy', 'dateMax')).format('YYYY-MM-DD'),
                                undefined,
                                '[]'
                              )
                            )
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

    <!-- Modal -->
    <div class="modal fade"
      data-entity="formQualityControl"
      data-method="create"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formC2', 'create') && (
          $access('formOperasional.formC2.create', 'timeFree') ||
          $moment($moment(query.form_c2.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formC2.create', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formC2.create', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Form C2</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-12 col-lg-4">
                  <label>Kode Cabang</label>
                  <input type="text" class="form-control" v-model="form.create.data.kode_cabang" readonly>
                </div>
                <div class="col-12 col-lg-8">
                  <label>Nama Cabang</label>
                  <input type="text" class="form-control" v-model="form.create.data.nama_cabang" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-12 col-lg-6">
                  <label>Tanggal Form</label>
                  <input type="date" class="form-control" readonly v-model="form.create.data.tanggal_form">
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.tanggal_form">
                    {{ msg }}
                  </small>
                </div>
                <div class="col-12 col-lg-6">
                  <label>Jam</label>
                  <input
                    type="time"
                    class="form-control"
                    v-model="form.create.data.jam"
                    :readonly="$access('formOperasional.formC2.create', 'automatedTime')"
                    >
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.jam">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Karyawan</label>
                <select class="form-control" v-model="form.create.data.tugas_karyawan_id">
                  <option value="null">-- Pilih Karyawan --</option>
                  <option v-for="(tugas_karyawan, i) in data.tugas_karyawan" :value="tugas_karyawan.id">
                    {{ tugas_karyawan.karyawan.nip }} - {{ tugas_karyawan.karyawan.nama_karyawan }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.tugas_karyawan_id">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Quality Control</label>
                <input type="text" class="form-control" v-model="form.create.data.quality_control" readonly>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control form-control-sm" v-model="form.create.data.keterangan"></textarea>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.keterangan">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group" v-for="(parameter_quality_control, index) in data.parameter_quality_control">
                <label>
                  {{ parameter_quality_control.parameter_quality_control }}
                </label>
                <div class="form-check" v-for="(opsi_parameter_quality_control, i) in parameter_quality_control.opsi_parameter_quality_control">
                  <input class="form-check-input m-0" type="radio" required :value="opsi_parameter_quality_control.id" v-model="form.create.data.opsi_parameter_quality_control_id[index]">
                  <label class="form-check-label">
                    {{ opsi_parameter_quality_control.opsi_parameter_quality_control }}
                  </label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade"
      data-entity="formQualityControl"
      data-method="update"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formC2', 'update') && (
          $access('formOperasional.formC2.update', 'timeFree') ||
          $moment($moment(query.form_c2.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formC2.update', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formC2.update', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Form C2</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-12 col-lg-4">
                  <label>Kode Cabang</label>
                  <input type="text" class="form-control" v-model="form.update.data.kode_cabang" readonly>
                </div>
                <div class="col-12 col-lg-8">
                  <label>Nama Cabang</label>
                  <input type="text" class="form-control" v-model="form.update.data.nama_cabang" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-12 col-lg-6">
                  <label>Tanggal Form</label>
                  <input type="date" class="form-control" readonly v-model="form.update.data.tanggal_form">
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.tanggal_form">
                    {{ msg }}
                  </small>
                </div>
                <div class="col-12 col-lg-6">
                  <label>Jam</label>
                  <input
                    type="time"
                    class="form-control"
                    v-model="form.update.data.jam"
                    :readonly="$access('formOperasional.formC2.update', 'readonlyTime')"
                    >
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.jam">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="form-group">
                <label>Karyawan</label>
                <select class="form-control" v-model="form.update.data.tugas_karyawan_id">
                  <option value="null">-- Pilih Karyawan --</option>
                  <option v-for="(tugas_karyawan, i) in data.tugas_karyawan" :value="tugas_karyawan.id">
                    {{ tugas_karyawan.karyawan.nip }} - {{ tugas_karyawan.karyawan.nama_karyawan }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, i) in form.update.errors.tugas_karyawan_id">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Quality Control</label>
                <input type="text" class="form-control" v-model="form.update.data.quality_control" readonly>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control form-control-sm" v-model="form.update.data.keterangan"></textarea>
                <small class="text-danger" v-for="(msg, i) in form.update.errors.keterangan">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group" v-for="(parameter_quality_control, index) in data.parameter_quality_control">
                <label>
                  {{ parameter_quality_control.parameter_quality_control }}
                </label>
                <div class="form-check" v-for="(opsi_parameter_quality_control, i) in parameter_quality_control.opsi_parameter_quality_control">
                  <input class="form-check-input m-0" type="radio" required :value="opsi_parameter_quality_control.id" v-model="form.update.data.opsi_parameter_quality_control_id[index]">
                  <label class="form-check-label">
                    {{ opsi_parameter_quality_control.opsi_parameter_quality_control }}
                  </label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Ubah</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade"
      data-entity="formQualityControl"
      data-method="destroy"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formC2', 'destroy') && (
          $access('formOperasional.formC2.destroy', 'timeFree') ||
          $moment($moment(query.form_c2.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formC2.destroy', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formC2.destroy', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="destroy">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Form C2</h5>
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
        form_c2: [],
        quality_control: [],
        tugas_karyawan: [],
        supplier: [],
        satuan: [],
        item_goreng: []
      },
      form: {
        create: {
          data: {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            quality_control: '',
            quality_control_id: null,
            keterangan: '',
            opsi_parameter_quality_control_id: []
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            quality_control: '',
            quality_control_id: null,
            keterangan: '',
            form_quality_control_d_id: [],
            opsi_parameter_quality_control_id: []
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
        form_c2: {
          cabang_id: this.$route.query.cabang_id || null,
          quality_control_id: this.$route.query.quality_control_id || null,
          tanggal_form: this.$access('formOperasional.formC2.read', 'timeFree')
            ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
            : (
              this.$moment(this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')).isBetween(
                this.$moment().subtract(this.$access('formOperasional.formC2.read', 'dateMin')).format('YYYY-MM-DD'),
                this.$moment().add(this.$access('formOperasional.formC2.read', 'dateMax')).format('YYYY-MM-DD'),
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

    if (this.$access('formOperasional.formC2', 'create') && this.$access('formOperasional.formC2.create', 'automatedTime')) {
      this.setCreateClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.create.data.jam)
  },

  watch: {
    'query.form_c2.tanggal_form'(n, o) {
      if (this.$access('formOperasional.formC2.read', 'timeFree')) {
        this.query.form_c2.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.formC2.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.formC2.read', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        ) {
          this.query.form_c2.tanggal_form = n
        } else {
          this.query.form_c2.tanggal_form = o
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
        this.fetchCabang(),
        this.fetchQualityControl()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.quality_control = res[1].data.container

          if (this.data.cabang.length <= 0 || this.data.quality_control.length <= 0) {
            this.$router.go(-1)
          }

          if (this.query.form_c2.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.form_c2.cabang_id }))) {
            this.query.form_c2.cabang_id = this.data.cabang[0].id || null
          }
          if (this.query.form_c2.quality_control_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.quality_control, { id: this.query.form_c2.quality_control_id }))) {
            this.query.form_c2.quality_control_id = this.data.quality_control[0].id || null
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.form_c2) && this.$route.name === 'formOperasional.formC2') {
            this.$router.push({
              name: 'formOperasional.formC2',
              query: this.query.form_c2
            })
          }
          this.data.form_c2 = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => console.log(err))
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_c2/cabang_tipe_harian', { params: this.query.form_c2 })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchQualityControl() {
      return this.$axios.get('/ajax/v1/master/quality_control')
    },
    fetchParameterQualityControl(id) {
      return this.$axios.get('/ajax/v1/master/parameter_quality_control/parent/' + id)
    },
    fetchTugasKaryawan(id, tanggal_penugasan) {
      return this.$axios.get('/ajax/v1/tugas_karyawan/cabang/?cabang_id=' + id + '&tanggal_penugasan=' + tanggal_penugasan)
    },
    fetchSupplier() {
      return this.$axios.get('/ajax/v1/master/supplier')
    },
    fetchSatuan() {
      return this.$axios.get('/ajax/v1/master/satuan')
    },
    fetchItemGoreng() {
      return this.$axios.get('/ajax/v1/master/item_goreng')
    },

    /**
     *  Modal functionality & utils
     */
    showCreateModal() {
      Promise.all([
        this.fetchTugasKaryawan(this.query.form_c2.cabang_id, this.query.form_c2.tanggal_penugasan),
        this.fetchParameterQualityControl(this.query.form_c2.quality_control_id)
      ])
        .then(res => {
          this.data.tugas_karyawan = res[0].data.container
          this.data.parameter_quality_control = res[1].data.container

          this.form.create.data.tanggal_form = this.query.form_c2.tanggal_form
          let currentCabang = this.$_.findWhere(this.data.cabang, {id: parseInt(this.query.form_c2.cabang_id)})
          this.form.create.data.kode_cabang = currentCabang.kode_cabang
          this.form.create.data.nama_cabang = currentCabang.cabang
          let currentQualityControl = this.$_.findWhere(this.data.quality_control, {id: parseInt(this.query.form_c2.quality_control_id)})
          this.form.create.data.quality_control_id = currentQualityControl.id
          this.form.create.data.quality_control = currentQualityControl.quality_control

          $('[data-entity="formQualityControl"][data-method="create"]').modal('show')
        })
        .catch(err => console.log(err))
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/form_operasional/form_c2/' + id)
        .then(res => {
          let currentCabang = this.$_.findWhere(this.data.cabang, {id: parseInt(this.query.form_c2.cabang_id)})
          let currentQualityControl = this.$_.findWhere(this.data.quality_control, {id: parseInt(this.query.form_c2.quality_control_id)})
          this.form.update.data.quality_control_id = currentQualityControl.id
          this.form.update.data.quality_control = currentQualityControl.quality_control

          let form_quality_control_d_id = this.$_.chain(res.data.container.d).pluck('id').value()
          let opsi_parameter_quality_control_id = this.$_.chain(res.data.container.d).pluck('opsi_parameter_quality_control_id').value()

          this.form.update.data = {
            id: id,
            kode_cabang: currentCabang.kode_cabang,
            nama_cabang: currentCabang.cabang,
            tanggal_form: this.query.form_c2.tanggal_form,
            jam: res.data.container.jam,
            quality_control_id: currentQualityControl.id,
            quality_control: currentQualityControl.quality_control,
            tugas_karyawan_id: res.data.container.tugas_karyawan_id,
            keterangan: res.data.container.keterangan,
            form_quality_control_d_id: form_quality_control_d_id,
            opsi_parameter_quality_control_id: opsi_parameter_quality_control_id
          }
          Promise.all([
            this.fetchTugasKaryawan(this.query.form_c2.cabang_id, this.query.form_c2.tanggal_penugasan),
            this.fetchParameterQualityControl(this.query.form_c2.quality_control_id)
          ])
            .then(res => {
              this.data.tugas_karyawan = res[0].data.container
              this.data.parameter_quality_control = res[1].data.container

              $('[data-entity="formQualityControl"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
        .catch(err => console.log(err))
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="formQualityControl"][data-method="destroy"]').modal('show')
    },
    hideCreateModal() {
      $('[data-entity="formQualityControl"][data-method="create"]').modal('hide')
    },
    hideUpdateModal() {
      $('[data-entity="formQualityControl"][data-method="update"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="formQualityControl"][data-method="destroy"]').modal('hide')
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
      if (this.$access('formOperasional.formC2', 'read')) {
        if (this.$access('formOperasional.formC2.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC2.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC2.read', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.query.form_c2.tanggal_form = this.$moment().format('YYYY-MM-DD')
            this.queryData()
          }
        }
      }
      // Handle date change on create action
      if (this.$access('formOperasional.formC2', 'create')) {
        if (this.$access('formOperasional.formC2.create', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC2.create', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC2.create', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.formC2', 'update')) {
        if (this.$access('formOperasional.formC2.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC2.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC2.update', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.formC2', 'destroy')) {
        if (this.$access('formOperasional.formC2.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC2.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC2.destroy', 'dateMax')).format('YYYY-MM-DD'),
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
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/form_operasional/form_c2', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            quality_control: '',
            quality_control_id: null,
            keterangan: '',
            opsi_parameter_quality_control_id: []
          }
          this.queryData(false)
          this.hideCreateModal()
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.create.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.create.loading = false
        })
    },
    update() {
      this.form.update.loading = true
      this.form.update.errors = {}
      this.$axios.put('/ajax/v1/form_operasional/form_c2', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            quality_control: '',
            quality_control_id: null,
            keterangan: '',
            form_quality_control_d_id: [],
            opsi_parameter_quality_control_id: []
          }
          this.queryData(false)
          this.hideUpdateModal()
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.update.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.update.loading = false
        })
    },
    destroy() {
      this.form.destroy.loading = true
      this.form.destroy.errors = {}
      this.$axios.delete('/ajax/v1/form_operasional/form_c2', { data: this.form.destroy.data })
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