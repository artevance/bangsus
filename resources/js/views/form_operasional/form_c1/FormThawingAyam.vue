<template>
  <div>
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
                <select class="form-control" v-model="query.form_thawing_ayam.cabang_id" @change="queryData">
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
                  v-model="query.form_thawing_ayam.tanggal_form"
                  @keyup="queryData"
                  :min="
                    $access('formOperasional.formC1.formThawingAyam.read', 'timeFree')
                      ? false
                      : $moment().subtract($access('formOperasional.formC1.formThawingAyam.read', 'minDate')).format('YYYY-MM-DD')
                  "
                  :max="
                    $access('formOperasional.formC1.formThawingAyam.read', 'timeFree')
                      ? false
                      : $moment().subtract($access('formOperasional.formC1.formThawingAyam.read', 'maxDate')).format('YYYY-MM-DD')
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
              <select class="form-control" v-model="query.form_thawing_ayam.cabang_id" @change="queryData">
                <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                  {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <label>Tanggal Form</label>
              <input type="date"
                class="form-control"
                v-model="query.form_thawing_ayam.tanggal_form"
                @keyup="queryData"
                :min="
                  $access('formOperasional.formC1.formThawingAyam.read', 'timeFree')
                    ? false
                    : $moment().subtract($access('formOperasional.formC1.formThawingAyam.read', 'minDate')).format('YYYY-MM-DD')
                "
                :max="
                  $access('formOperasional.formC1.formThawingAyam.read', 'timeFree')
                    ? false
                    : $moment().subtract($access('formOperasional.formC1.formThawingAyam.read', 'maxDate')).format('YYYY-MM-DD')
                "
                >
            </div>
          </div>
        </div>
        <button class="btn btn-primary"
          @click="showCreateModal"
          v-if="
            $access('formOperasional.formC1.formThawingAyam', 'create') && (
              $access('formOperasional.formC1.formThawingAyam.create', 'timeFree') ||
              $moment($moment(query.form_thawing_ayam.tanggal_form)).isBetween(
                $moment(utils.date).subtract($access('formOperasional.formC1.formThawingAyam.create', 'dateMin')).format('YYYY-MM-DD'),
                $moment(utils.date).add($access('formOperasional.formC1.formThawingAyam.create', 'dateMax')).format('YYYY-MM-DD'),
                undefined,
                '[]'
              )
            )
          ">
          Tambah
        </button>
        <div class="table-responsive mt-2">
          <table class="table table-hover" v-if="$access('formOperasional.formC1.formThawingAyam', 'read')">
            <thead>
              <th>#</th>
              <th>NIP</th>
              <th>Nama Karyawan</th>
              <th>Jam</th>
              <th>Qty</th>
              <th>Satuan</th>
              <th>Supplier</th>
              <th>Aksi</th>
            </thead>
            <tbody>
              <tr v-for="(form_thawing_ayam, i) in data.form_thawing_ayam">
                <td>{{ i + 1 }}</td>
                <td>{{ form_thawing_ayam.tugas_karyawan.karyawan.nip }}</td>
                <td>{{ form_thawing_ayam.tugas_karyawan.karyawan.nama_karyawan }}</td>
                <td>{{ form_thawing_ayam.jam }}</td>
                <td>{{ form_thawing_ayam.qty }}</td>
                <td>{{ form_thawing_ayam.satuan.satuan }}</td>
                <td>{{ form_thawing_ayam.supplier.supplier }}</td>
                <td>
                  <a class="badge badge-warning"
                    @click="showUpdateModal(form_thawing_ayam.id)"
                    href="#"
                    v-if="
                      $access('formOperasional.formC1.formThawingAyam', 'update') && (
                        $access('formOperasional.formC1.formThawingAyam.update', 'timeFree') ||
                        $moment($moment(query.form_thawing_ayam.tanggal_form)).isBetween(
                          $moment(utils.date).subtract($access('formOperasional.formC1.formThawingAyam.update', 'dateMin')).format('YYYY-MM-DD'),
                          $moment(utils.date).add($access('formOperasional.formC1.formThawingAyam.update', 'dateMax')).format('YYYY-MM-DD'),
                          undefined,
                          '[]'
                        )
                      )
                    ">
                    Ubah
                  </a>
                  <a class="badge badge-danger"
                    @click="showDestroyModal(form_thawing_ayam.id)"
                    href="#"
                    v-if="
                      $access('formOperasional.formC1.formThawingAyam', 'destroy') && (
                        $access('formOperasional.formC1.formThawingAyam.destroy', 'timeFree') ||
                        $moment($moment(query.form_thawing_ayam.tanggal_form)).isBetween(
                          $moment(utils.date).subtract($access('formOperasional.formC1.formThawingAyam.destroy', 'dateMin')).format('YYYY-MM-DD'),
                          $moment(utils.date).add($access('formOperasional.formC1.formThawingAyam.destroy', 'dateMax')).format('YYYY-MM-DD'),
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

    <!-- Modal -->
    <div class="modal fade"
      data-entity="formThawingAyam"
      data-method="create"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formC1.formThawingAyam', 'create') && (
          $access('formOperasional.formC1.formThawingAyam.create', 'timeFree') ||
          $moment($moment(query.form_thawing_ayam.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formC1.formThawingAyam.create', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formC1.formThawingAyam.create', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Form Thawing Ayam</h5>
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
                    :readonly="$access('formOperasional.formC1.formThawingAyam.create', 'automatedTime')"
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
              <div class="form-group row">
                <div class="col-12 col-lg-3">
                  <label>Supplier</label>
                  <select class="form-control" v-model="form.create.data.supplier_id">
                    <option value="null">-- Pilih Supplier --</option>
                    <option v-for="(supplier, i) in data.supplier" :value="supplier.id">
                      {{ supplier.supplier }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, i) in form.create.errors.supplier_id">
                    {{ msg }}
                  </small>
                </div>
                <template v-if="$access('formOperasional.formC1.formThawingAyam.create', 'changeSatuan')">
                  <div class="col-12 col-lg-3">
                    <label>Qty</label>
                    <input type="number" class="form-control" step="any" v-model="form.create.data.qty">
                    <small class="text-danger" v-for="(msg, i) in form.create.errors.qty">
                      {{ msg }}
                    </small>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label>Satuan</label>
                    <select class="form-control" v-model="form.create.data.satuan_id">
                      <option value="null">-- Pilih Satuan --</option>
                      <option v-for="(satuan, i) in data.satuan" :value="satuan.id">
                        {{ satuan.satuan }}
                      </option>
                    </select>
                    <small class="text-danger" v-for="(msg, i) in form.create.errors.satuan_id">
                      {{ msg }}
                    </small>
                  </div>
                </template>
                <template v-else>
                  <div class="col-12 col-lg-3">
                    <label>Qty</label>
                    <div class="input-group">
                      <input type="number" class="form-control" step="any" v-model="form.create.data.qty">
                      <input type="hidden" value="2" v-model="form.create.data.satuan_id">
                      <div class="input-group-prepend">
                        <small class="input-group-text">
                          PACK
                        </small>
                      </div>  
                    </div>
                    <small class="text-danger" v-for="(msg, i) in form.create.errors.qty">
                      {{ msg }}
                    </small>
                  </div>
                </template>
              </div>
              <div class="form-group">
                <label>Gambar</label>
                <webcam-component v-model="form.create.data.gambar" ref="webcam"></webcam-component>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.gambar">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control form-control-sm" v-model="form.create.data.keterangan"></textarea>
                <small class="text-danger" v-for="(msg, i) in form.create.errors.keterangan">
                  {{ msg }}
                </small>
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
      data-entity="formThawingAyam"
      data-method="update"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formC1.formThawingAyam', 'update') && (
          $access('formOperasional.formC1.formThawingAyam.update', 'timeFree') ||
          $moment($moment(query.form_thawing_ayam.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formC1.formThawingAyam.update', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formC1.formThawingAyam.update', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Form Thawing Ayam</h5>
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
                    :readonly="$access('formOperasional.formC1.formThawingAyam.update', 'readonlyTime')"
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
              <div class="form-group row">
                <div class="col-12 col-lg-3">
                  <label>Supplier</label>
                  <select class="form-control" v-model="form.update.data.supplier_id">
                    <option value="null">-- Pilih Supplier --</option>
                    <option v-for="(supplier, i) in data.supplier" :value="supplier.id">
                      {{ supplier.supplier }}
                    </option>
                  </select>
                  <small class="text-danger" v-for="(msg, i) in form.update.errors.supplier_id">
                    {{ msg }}
                  </small>
                </div>
                <template v-if="$access('formOperasional.formC1.formThawingAyam.update', 'changeSatuan')">
                  <div class="col-12 col-lg-3">
                    <label>Qty</label>
                    <input type="number" class="form-control" step="any" v-model="form.update.data.qty">
                    <small class="text-danger" v-for="(msg, i) in form.update.errors.qty">
                      {{ msg }}
                    </small>
                  </div>
                  <div class="col-12 col-lg-3">
                    <label>Satuan</label>
                    <select class="form-control" v-model="form.update.data.satuan_id">
                      <option value="null">-- Pilih Satuan --</option>
                      <option v-for="(satuan, i) in data.satuan" :value="satuan.id">
                        {{ satuan.satuan }}
                      </option>
                    </select>
                    <small class="text-danger" v-for="(msg, i) in form.update.errors.satuan_id">
                      {{ msg }}
                    </small>
                  </div>
                </template>
                <template v-else>
                  <div class="col-12 col-lg-3">
                    <label>Qty</label>
                    <div class="input-group">
                      <input type="number" class="form-control" step="any" v-model="form.update.data.qty">
                      <input type="hidden" value="2" v-model="form.update.data.satuan_id">
                      <div class="input-group-prepend">
                        <small class="input-group-text">
                          PACK
                        </small>
                      </div>  
                    </div>
                    <small class="text-danger" v-for="(msg, i) in form.update.errors.qty">
                      {{ msg }}
                    </small>
                  </div>
                </template>
              </div>
              <div class="form-group" v-if="$access('formOperasional.formC1.formThawingAyam.update', 'takePhoto')">
                <label>Gambar</label>
                <webcam-component v-model="form.update.data.gambar" ref="webcam"></webcam-component>
                <small class="text-danger" v-for="(msg, i) in form.update.errors.gambar">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control form-control-sm" v-model="form.update.data.keterangan"></textarea>
                <small class="text-danger" v-for="(msg, i) in form.update.errors.keterangan">
                  {{ msg }}
                </small>
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
      data-entity="formThawingAyam"
      data-method="destroy"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formC1.formThawingAyam', 'destroy') && (
          $access('formOperasional.formC1.formThawingAyam.destroy', 'timeFree') ||
          $moment($moment(query.form_thawing_ayam.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formC1.formThawingAyam.destroy', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formC1.formThawingAyam.destroy', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="destroy">
              <div class="modal-header">
                <h5 class="modal-title">Hapus Form Thawing Ayam</h5>
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
        form_thawing_ayam: [],
        tugas_karyawan: [],
        supplier: [],
        satuan: []
      },
      form: {
        create: {
          data: {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            supplier_id: null,
            qty: null,
            satuan_id: 2,
            gambar: '',
            keterangan: ''
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
            supplier_id: null,
            qty: null,
            satuan_id: 2,
            gambar: null,
            keterangan: ''
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
        form_thawing_ayam: {
          cabang_id: this.$route.query.cabang_id || null,
          tanggal_form: this.$access('formOperasional.formC1.formThawingAyam.read', 'timeFree')
            ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
            : (
              this.$moment(this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')).isBetween(
                this.$moment().subtract(this.$access('formOperasional.formC1.formThawingAyam.read', 'dateMin')).format('YYYY-MM-DD'),
                this.$moment().add(this.$access('formOperasional.formC1.formThawingAyam.read', 'dateMax')).format('YYYY-MM-DD'),
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

    if (this.$access('formOperasional.formC1.formThawingAyam', 'create') && this.$access('formOperasional.formC1.formThawingAyam.create', 'automatedTime')) {
      this.setCreateClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.create.data.jam)
  },

  watch: {
    'query.form_thawing_ayam.tanggal_form'(n, o) {
      if (this.$access('formOperasional.formC1.formThawingAyam.read', 'timeFree')) {
        this.query.form_thawing_ayam.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.formC1.formThawingAyam.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.formC1.formThawingAyam.read', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        ) {
          this.query.form_thawing_ayam.tanggal_form = n
        } else {
          this.query.form_thawing_ayam.tanggal_form = o
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

          if (this.query.form_thawing_ayam.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.form_thawing_ayam.cabang_id }))) {
            this.query.form_thawing_ayam.cabang_id = this.data.cabang[0].id || null
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.form_thawing_ayam) && this.$route.name === 'formOperasional.formC1.formThawingAyam') {
            this.$router.push({
              name: 'formOperasional.formC1.formThawingAyam',
              query: this.query.form_thawing_ayam
            })
          }
          this.data.form_thawing_ayam = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_c1/form_thawing_ayam/cabang_harian', { params: this.query.form_thawing_ayam })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
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

    /**
     *  Modal functionality & utils
     */
    showCreateModal() {
      Promise.all([
        this.fetchTugasKaryawan(this.query.form_thawing_ayam.cabang_id, this.query.form_thawing_ayam.tanggal_penugasan),
        this.fetchSupplier(),
        this.fetchSatuan()
      ])
        .then(res => {
          this.data.tugas_karyawan = res[0].data.container
          this.data.supplier = res[1].data.container
          this.data.satuan = res[2].data.container

          this.form.create.data.tanggal_form = this.query.form_thawing_ayam.tanggal_form
          let currentCabang = this.$_.findWhere(this.data.cabang, {id: parseInt(this.query.form_thawing_ayam.cabang_id)})
          this.form.create.data.kode_cabang = currentCabang.kode_cabang
          this.form.create.data.nama_cabang = currentCabang.cabang

          $('[data-entity="formThawingAyam"][data-method="create"]').modal('show')
        })
        .catch(err => {})
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/form_operasional/form_c1/form_thawing_ayam/' + id)
        .then(res => {
          let currentCabang = this.$_.findWhere(this.data.cabang, {id: parseInt(this.query.form_thawing_ayam.cabang_id)})
          this.form.update.data = {
            id: id,
            kode_cabang: currentCabang.kode_cabang,
            nama_cabang: currentCabang.nama_cabang,
            tanggal_form: this.query.form_thawing_ayam.tanggal_form,
            jam: res.data.container.jam,
            tugas_karyawan_id: res.data.container.tugas_karyawan_id,
            supplier_id: res.data.container.supplier_id,
            qty: res.data.container.qty,
            satuan_id: res.data.container.satuan_id,
            keterangan: res.data.container.keterangan
          }
          Promise.all([
            this.fetchTugasKaryawan(this.query.form_thawing_ayam.cabang_id, this.query.form_thawing_ayam.tanggal_penugasan),
            this.fetchSupplier(),
            this.fetchSatuan()
          ])
            .then(res => {
              this.data.tugas_karyawan = res[0].data.container
              this.data.supplier = res[1].data.container
              this.data.satuan = res[2].data.container

              $('[data-entity="formThawingAyam"][data-method="update"]').modal('show')
            })
            .catch(err => {})
        })
        .catch(err => {})
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="formThawingAyam"][data-method="destroy"]').modal('show')
    },
    hideCreateModal() {
      $('[data-entity="formThawingAyam"][data-method="create"]').modal('hide')
    },
    hideUpdateModal() {
      $('[data-entity="formThawingAyam"][data-method="update"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="formThawingAyam"][data-method="destroy"]').modal('hide')
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
      if (this.$access('formOperasional.formC1.formThawingAyam', 'read')) {
        if (this.$access('formOperasional.formC1.formThawingAyam.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC1.formThawingAyam.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC1.formThawingAyam.read', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.query.form_thawing_ayam.tanggal_form = this.$moment().format('YYYY-MM-DD')
            this.queryData()
          }
        }
      }
      // Handle date change on create action
      if (this.$access('formOperasional.formC1.formThawingAyam', 'create')) {
        if (this.$access('formOperasional.formC1.formThawingAyam.create', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC1.formThawingAyam.create', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC1.formThawingAyam.create', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.formC1.formThawingAyam', 'update')) {
        if (this.$access('formOperasional.formC1.formThawingAyam.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC1.formThawingAyam.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC1.formThawingAyam.update', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.formC1.formThawingAyam', 'destroy')) {
        if (this.$access('formOperasional.formC1.formThawingAyam.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formC1.formThawingAyam.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formC1.formThawingAyam.destroy', 'dateMax')).format('YYYY-MM-DD'),
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
      this.$axios.post('/ajax/v1/form_operasional/form_c1/form_thawing_ayam', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            supplier_id: null,
            qty: null,
            satuan_id: 2,
            keterangan: '',
            gambar: ''
          }
          this.queryData(false)
          this.$refs.webcam.reset()
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
      this.$axios.put('/ajax/v1/form_operasional/form_c1/form_thawing_ayam', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            kode_cabang: '',
            nama_cabang: '',
            tanggal_form: '',
            jam: '',
            tugas_karyawan_id: null,
            supplier_id: null,
            qty: null,
            satuan_id: 2,
            gambar: null,
            keterangan: ''
          }
          this.queryData(false)
          this.$refs.webcam.reset()
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
      this.$axios.delete('/ajax/v1/form_operasional/form_c1/form_thawing_ayam', { data: this.form.destroy.data })
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