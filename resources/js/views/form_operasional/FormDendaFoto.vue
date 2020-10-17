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
                      <select class="form-control" v-model="query.form_foto.cabang_id" @change="queryData">
                        <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                          {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Kelompok Foto
                        </span>
                      </div>
                      <select class="form-control" v-model="query.form_foto.kelompok_foto_id" @change="queryData">
                        <option v-for="(kelompok_foto, i) in data.kelompok_foto" :key="i" :value="kelompok_foto.id">
                          {{ kelompok_foto.kelompok_foto }}
                        </option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          Tanggal Form
                        </span>
                      </div>
                      <input type="date"
                        class="form-control"
                        v-model="query.form_foto.tanggal_form"
                        @keyup="queryData" @change="queryData"
                        :min="
                          $access('formOperasional.formDendaFoto.read', 'timeFree')
                            ? false
                            : $moment().subtract($access('formOperasional.formDendaFoto.read', 'minDate')).format('YYYY-MM-DD')
                        "
                        :max="
                          $access('formOperasional.formDendaFoto.read', 'timeFree')
                            ? false
                            : $moment().subtract($access('formOperasional.formDendaFoto.read', 'maxDate')).format('YYYY-MM-DD')
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
                    <select class="form-control" v-model="query.form_foto.cabang_id" @change="queryData">
                      <option v-for="(cabang, i) in data.cabang" :key="i" :value="cabang.id">
                        {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Kelompok Foto</label>
                    <select class="form-control" v-model="query.form_foto.kelompok_foto_id" @change="queryData">
                      <option v-for="(kelompok_foto, i) in data.kelompok_foto" :key="i" :value="kelompok_foto.id">
                        {{ kelompok_foto.kelompok_foto }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Tanggal Form</label>
                    <input type="date"
                      class="form-control"
                      v-model="query.form_foto.tanggal_form"
                      @keyup="queryData" @change="queryData"
                      :min="
                        $access('formOperasional.formDendaFoto.read', 'timeFree')
                          ? false
                          : $moment().subtract($access('formOperasional.formDendaFoto.read', 'minDate')).format('YYYY-MM-DD')
                      "
                      :max="
                        $access('formOperasional.formDendaFoto.read', 'timeFree')
                          ? false
                          : $moment().subtract($access('formOperasional.formDendaFoto.read', 'maxDate')).format('YYYY-MM-DD')
                      "
                      >
                  </div>
                </div>
              </div>
              <button class="btn btn-primary" @click="showGenerateModal">
                Generate Denda
              </button>
              <div class="mt-5">
                <div class="card" v-for="(form_foto, i) in data.form_foto">
                  <div class="card-header" data-toggle="collapse" :data-target="'#form_foto_' + form_foto.id" aria-expanded="true" aria-controls="collapseOne">
                    <div class="row">
                      <h5 class="d-flex align-items-center mx-3 text-muted"><i class="far fa-chevron-down"></i></h5>
                      <div class="col-6">
                        <h5><a href="#">#{{ i + 1 }} - {{ form_foto.kelompok_foto.kelompok_foto }} - {{ form_foto.tidak_kirim == 0 ? form_foto.jam : '' }}</a></h5>
                        <p v-if="form_foto.tidak_kirim == 0">
                          {{ form_foto.tugas_karyawan.karyawan.nip }} - <b>{{ form_foto.tugas_karyawan.karyawan.nama_karyawan }}</b>
                        </p>
                        <p v-else>TIDAK KIRIM</p>
                        <span class="text-danger" v-if="form_foto.form_denda_foto == null">
                          BELUM DIPERIKSA
                        </span>
                        <span class="text-success" v-else>
                          SUDAH DIPERIKSA
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="collapse" aria-labelledby="headingOne" :id="'form_foto_' + form_foto.id">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-xl-4">
                          <img :src="'/gambar/' + form_foto.gambar_id" v-if="form_foto.tidak_kirim" style="max-width: 100%; max-height: 100%;">
                        </div>
                        <div class="col-xl-6">
                          <div class="table-responsive mt-5 mt-xl-0">
                            <table class="table table-bordered">
                              <tbody>
                                <tr>
                                  <th>NIP</th>
                                  <td>
                                    <span v-if="form_foto.tugas_karyawan != null">{{ form_foto.tugas_karyawan.karyawan.nip }}</span>
                                    <span v-else>-</span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Nama Karyawan</th>
                                  <td>
                                    <span v-if="form_foto.tugas_karyawan != null">{{ form_foto.tugas_karyawan.karyawan.nama_karyawan }}</span>
                                    <span v-else>-</span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Jam</th>
                                  <td>
                                    <span v-if="form_foto.tidak_kirim == 0">{{ form_foto.jam }}</span>
                                    <span v-else>-</span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Kelompok Foto</th>
                                  <td>
                                    {{ form_foto.kelompok_foto.kelompok_foto }}
                                  </td>
                                </tr>
                                <tr>
                                  <th>Link Foto</th>
                                  <td>
                                    <a :href="'/gambar/' + form_foto.gambar_id" target="_blank">
                                      Link
                                    </a>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Sudah Diperiksa</th>
                                  <td>
                                    <span v-if="form_foto.form_denda_foto == null">BELUM</span>
                                    <span v-else>SUDAH</span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Kena Denda</th>
                                  <td>
                                    <span v-if="form_foto.form_denda_foto == null">-</span>
                                    <span v-else>
                                      <span v-if="form_foto.form_denda_foto.denda == 1">YA</span>
                                      <span v-else>TIDAK</span>
                                    </span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Total</th>
                                  <td>
                                    <span v-if="form_foto.form_denda_foto == null">-</span>
                                    <span v-else>
                                      <span v-if="form_foto.form_denda_foto.denda == 1">{{ form_foto.form_denda_foto.total }}</span>
                                      <span v-else>TIDAK</span>
                                    </span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>Aksi</th>
                                  <td>
                                    <template v-if="form_foto.form_denda_foto != null">
                                      <span v-if="form_foto.form_denda_foto.denda == 1">
                                        <a
                                          href="#"
                                          class="badge
                                          badge-info"
                                          data-toggle="modal"
                                          v-if="false"
                                          >
                                          Lihat Detail
                                        </a>
                                        <a
                                          href="#"
                                          class="badge
                                          badge-warning"
                                          data-toggle="modal"
                                          @click="showUpdateModal(form_foto.id)"
                                          v-if="
                                            $access('formOperasional.formDendaFoto', 'update') && (
                                              $access('formOperasional.formDendaFoto.update', 'timeFree') ||
                                              $moment($moment(query.form_c5.tanggal_form)).isBetween(
                                                $moment(utils.date).subtract($access('formOperasional.formDendaFoto.update', 'dateMin')).format('YYYY-MM-DD'),
                                                $moment(utils.date).add($access('formOperasional.formDendaFoto.update', 'dateMax')).format('YYYY-MM-DD'),
                                                undefined,
                                                '[]'
                                              )
                                            )
                                          ">
                                          Ubah
                                        </a>
                                      </span>
                                      <a href="#" class="badge badge-danger" data-toggle="modal" @click="showDestroyModal(form_foto.form_denda_foto.id)">Hapus</a>
                                    </template>
                                    <template v-else>
                                      <a href="#" class="badge badge-dark" data-toggle="modal" @click="showDendaModal(form_foto.id)">Denda</a>
                                      <a href="#" class="badge badge-light" data-toggle="modal" @click="showTidakDendaModal(form_foto.id)">Tidak Denda</a>
                                    </template>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade"
      data-entity="formDendaFoto"
      data-method="denda"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formDendaFoto', 'denda')
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="denda">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Form Denda Foto</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Foto</label>
                <div>
                  <img :src="'/gambar/' + form.denda.data.gambar_id">
                </div>
              </div>
              <div class="form-group">
                <label>Denda</label>
                <div class="table-responsive">
                  <table class="table" data-entity="denda">
                    <thead>
                      <th>Denda</th>
                      <th>Nominal</th>
                      <th>Keterangan</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(detail, i) in form.denda.data.d">
                        <td>
                          <select v-model="detail.denda_foto_id" class="form-control" @change="assignDendaNominalDenda(i)">
                            <option v-for="denda_foto in data.denda_foto" :value="denda_foto.id">
                              {{ denda_foto.denda_foto }}
                            </option>
                          </select>
                        </td>
                        <td>
                          <input type="number" class="form-control" v-model="detail.nominal">
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="detail.keterangan">
                        </td>
                        <td>
                          <button class="btn" type="button" @click="removeDendaRowDenda(i)">
                            <i class="fas fa-trash text-danger"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <button class="btn btn-sm btn-secondary mt-5" type="button" @click="addDendaRowDenda">
                  + Tambah Denda
                </button>
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
      data-entity="formDendaFoto"
      data-method="tidakDenda"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formDendaFoto', 'tidakDenda') && (
          $access('formOperasional.formDendaFoto.tidakDenda', 'timeFree') ||
          $moment($moment(query.form_foto.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formDendaFoto.tidakDenda', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formDendaFoto.tidakDenda', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="tidakDenda">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Form Denda Foto (Tidak Denda)</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Apakah anda yakin bahwa form ini tidak kena denda?</p>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" :disabled="form.tidakDenda.loading">
                <spinner-component size="sm" color="light" v-if="form.tidakDenda.loading"/>
                Ya
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade"
      data-entity="formDendaFoto"
      data-method="update"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formDendaFoto', 'update')
      ">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Form Denda Foto</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Foto</label>
                <div>
                  <img :src="'/gambar/' + form.update.data.gambar_id">
                </div>
              </div>
              <div class="form-group">
                <label>Denda</label>
                <div class="table-responsive">
                  <table class="table" data-entity="update">
                    <thead>
                      <th>Denda</th>
                      <th>Nominal</th>
                      <th>Keterangan</th>
                      <th>Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(updateDetail, i) in form.update.data.d">
                        <td>
                          <select v-model="updateDetail.denda_foto_id" class="form-control" @change="assignUpdateNominalDenda(i)">
                            <option v-for="denda_foto in data.denda_foto" :value="denda_foto.id">
                              {{ denda_foto.denda_foto }}
                            </option>
                          </select>
                        </td>
                        <td>
                          <input type="number" class="form-control" v-model="updateDetail.nominal">
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="updateDetail.keterangan">
                        </td>
                        <td>
                          <button class="btn" type="button" @click="removeUpdateRowDenda(i)">
                            <i class="fas fa-trash text-danger"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <button class="btn btn-sm btn-secondary mt-5" type="button" @click="addUpdateRowDenda">
                  + Tambah Denda
                </button>
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
      data-entity="formDendaFoto"
      data-method="destroy"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1"
      v-if="
        $access('formOperasional.formDendaFoto', 'destroy') && (
          $access('formOperasional.formDendaFoto.destroy', 'timeFree') ||
          $moment($moment(query.form_foto.tanggal_form)).isBetween(
            $moment().subtract($access('formOperasional.formDendaFoto.destroy', 'dateMin')).format('YYYY-MM-DD'),
            $moment().add($access('formOperasional.formDendaFoto.destroy', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        )
      ">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="destroy">
            <div class="modal-header">
              <h5 class="modal-title">Hapus Form Denda Foto</h5>
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
    <div class="modal fade"
      data-entity="formDendaFoto"
      data-method="generate"
      data-backdrop="static"
      data-keyboard="false"
      tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="generate">
            <div class="modal-header">
              <h5 class="modal-title">Generate Form Denda Foto</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <table class="table table-hover">
                <thead>
                  <th>Kelompok Foto</th>
                  <th>Qty Min</th>
                  <th>Qty Toleransi</th>
                </thead>
                <tbody>
                  <template v-for="(detail, i) in form.generate.data.detail">
                    <tr v-if="detail.denda_tidak_kirim">
                      <td>{{ detail.kelompok_foto }}</td>
                      <td>{{ detail.pengaturan_kelompok_foto.qty_minimum_form }}</td>
                      <td>
                        <input type="number" class="form-control" v-model="detail.qty_toleransi">
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" :disabled="form.generate.loading">
                <spinner-component size="sm" color="light" v-if="form.generate.loading"/>
                Generate
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
        form_foto: [],
        tugas_karyawan: [],
        kelompok_foto: [],
        denda_foto: []
      },
      form: {
        denda: {
          data: {
            form_foto_id: null,
            kelompok_foto_id: null,
            kelompok_foto: '',
            keterangan: '',
            gambar_id: null,
            d: []
          },
          errors: {},
          loading: false,
        },
        tidakDenda: {
          data: {
            form_foto_id: null
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            form_foto_id: null,
            kelompok_foto_id: null,
            kelompok_foto: '',
            keterangan: '',
            gambar_id: null,
            d: []
          },
          errors: {},
          loading: false,
        },
        destroy: {
          data: {
            id: null
          },
          errors: {},
          loading: false
        },
        generate: {
          data: {
            tanggal_form: '',
            detail: []
          },
          errors: {},
          loading: false
        }
      },
      query: {
        form_foto: {
          cabang_id: this.$route.query.cabang_id || null,
          tanggal_form: this.$access('formOperasional.formDendaFoto.read', 'timeFree')
            ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
            : (
              this.$moment(this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')).isBetween(
                this.$moment().subtract(this.$access('formOperasional.formDendaFoto.read', 'dateMin')).format('YYYY-MM-DD'),
                this.$moment().add(this.$access('formOperasional.formDendaFoto.read', 'dateMax')).format('YYYY-MM-DD'),
                undefined,
                '[]'
              )
                ? this.$moment(this.$route.query.tanggal_form).format('YYYY-MM-DD')
                : this.$moment().format('YYYY-MM-DD')
            ),
          kelompok_foto_id: this.$route.query.kelompok_foto_id || null
        }
      },
      interval: {
        form: {
          denda: {
            data: {
              jam: null
            }
          },
          tidakDenda: {
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

    if (this.$access('formOperasional.formDendaFoto', 'denda') && this.$access('formOperasional.formDendaFoto.denda', 'automatedTime')) {
      this.setDendaClockInterval()
    }
    if (this.$access('formOperasional.formDendaFoto', 'tidakDenda') && this.$access('formOperasional.formDendaFoto.tidakDenda', 'automatedTime')) {
      this.setDendaClockInterval()
    }
  },
  beforeDestroy() {
    clearInterval(this.interval.utils.date)
    clearInterval(this.interval.form.denda.data.jam)
    clearInterval(this.interval.form.tidakDenda.data.jam)
  },

  watch: {
    'query.form_foto.tanggal_form'(n, o) {
      if (this.$access('formOperasional.formDendaFoto.read', 'timeFree')) {
        this.query.form_foto.tanggal_form = n
      } else {
        if (
          this.$moment(this.$moment(n)).isBetween(
            this.$moment().subtract(this.$access('formOperasional.formDendaFoto.read', 'dateMin')).format('YYYY-MM-DD'),
            this.$moment().add(this.$access('formOperasional.formDendaFoto.read', 'dateMax')).format('YYYY-MM-DD'),
            undefined,
            '[]'
          )
        ) {
          this.query.form_foto.tanggal_form = n
        } else {
          this.query.form_foto.tanggal_form = o
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
        this.fetchKelompokFoto()
      ])
        .then(res => {
          this.data.cabang = res[0].data.container
          this.data.kelompok_foto = res[1].data.container

          if (this.data.cabang.length <= 0 || this.data.tipe_absensi <= 0) {
            this.$router.go(-1)
          }

          if (this.query.form_foto.cabang_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.cabang, { id: this.query.form_foto.cabang_id }))) {
            this.query.form_foto.cabang_id = this.data.cabang[0].id || null
          }
          if (this.query.form_foto.kelompok_foto_id == null || ! this.$_.isUndefined(this.$_.findWhere(this.data.kelompok_foto, { id: this.query.form_foto.kelompok_foto_id }))) {
            this.query.form_foto.kelompok_foto_id = this.data.kelompok_foto[0].id || null
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
          if ( ! this.$_.isEqual(this.$route.query, this.query.form_foto) && this.$route.name === 'formOperasional.formDendaFoto') {
            this.$router.push({
              name: 'formOperasional.formDendaFoto',
              query: this.query.form_foto
            })
          }
          this.data.form_foto = res.data.container
          if (withSpinner) this.state.table.loading = false
        })
        .catch(err => {})
    },

    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/form_operasional/form_foto/cabang_tipe_harian', { params: this.query.form_foto })
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang/terotorisasi')
    },
    fetchTugasKaryawan(id, tanggal_penugasan) {
      return this.$axios.get('/ajax/v1/tugas_karyawan/cabang/?cabang_id=' + id + '&tanggal_penugasan=' + tanggal_penugasan)
    },
    fetchKelompokFoto() {
      return this.$axios.get('/ajax/v1/master/kelompok_foto')
    },
    fetchDendaFoto(id) {
      return this.$axios.get('/ajax/v1/master/denda_foto/parent/' + id)
    },

    /**
     *  Modal functionality & utils
     */
    showDendaModal(id) {
      this.$axios.get('/ajax/v1/form_operasional/form_foto/' + id)
        .then(res => {
          let data = res.data.container
          this.form.denda.data = {
            form_foto_id: data.id,
            kelompok_foto: data.kelompok_foto.kelompok_foto,
            kelompok_foto_id: data.kelompok_foto_id,
            gambar_id: data.gambar_id,
            d: []
          }
          Promise.all([
            this.fetchDendaFoto(this.form.denda.data.kelompok_foto_id)
          ])
            .then(res => {
              this.data.denda_foto = res[0].data.container

              $('[data-entity="formDendaFoto"][data-method="denda"]').modal('show')
            })
        })
    },
    showTidakDendaModal(id) {
      this.$axios.get('/ajax/v1/form_operasional/form_foto/' + id)
        .then(res => {
          let data = res.data.container
          this.form.tidakDenda.data = {
            form_foto_id: data.id
          }
          $('[data-entity="formDendaFoto"][data-method="tidakDenda"]').modal('show')
        })
    },
    showUpdateModal(id) {
      this.$axios.get('/ajax/v1/form_operasional/form_foto/' + id)
        .then(res => {
          let data = res.data.container
          this.form.update.data = {
            id: data.form_denda_foto.id,
            form_foto_id: data.id,
            kelompok_foto: data.kelompok_foto.kelompok_foto,
            kelompok_foto_id: data.kelompok_foto_id,
            gambar_id: data.gambar_id,
            d: data.form_denda_foto.d
          }
          console.log(data.form_denda_foto.d)
          Promise.all([
            this.fetchDendaFoto(this.form.update.data.kelompok_foto_id)
          ])
            .then(res => {
              this.data.denda_foto = res[0].data.container

              $('[data-entity="formDendaFoto"][data-method="update"]').modal('show')
            })
        })
    },
    showDestroyModal(id) {
      this.form.destroy.data.id = id
      $('[data-entity="formDendaFoto"][data-method="destroy"]').modal('show')
    },
    showGenerateModal() {
      Promise.all([
        this.fetchKelompokFoto()
      ])
        .then(res => {
          this.data.kelompok_foto = res[0].data.container

          this.form.generate.data = {
            cabang_id: this.$route.query.cabang_id,
            tanggal_form: this.$route.query.tanggal_form,
            detail: this.data.kelompok_foto
          }

          this.form.generate.data.detail.forEach((item, i) => {
            this.form.generate.data.detail[i].qty_toleransi = 0
          })

          $('[data-entity="formDendaFoto"][data-method="generate"]').modal('show')
        })
    },
    hideDendaModal() {
      $('[data-entity="formDendaFoto"][data-method="denda"]').modal('hide')
    },
    hideTidakDendaModal() {
      $('[data-entity="formDendaFoto"][data-method="tidakDenda"]').modal('hide')
    },
    hideUpdateModal() {
      $('[data-entity="formDendaFoto"][data-method="update"]').modal('hide')
    },
    hideDestroyModal() {
      $('[data-entity="formDendaFoto"][data-method="destroy"]').modal('hide')
    },
    hideGenerateModal() {
      $('[data-entity="formDendaFoto"][data-method="generate"]').modal('hide')
    },
    setDendaClockInterval() {
      this.interval.form.denda.data.jam = setInterval(function () {
        this.form.denda.data.jam = this.$moment().format('HH:mm:ss')
      }.bind(this), 1000)
    },
    setTidakDendaClockInterval() {
      this.interval.form.tidakDenda.data.jam = setInterval(function () {
        this.form.denda.data.jam = this.$moment().format('HH:mm:ss')
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
      if (this.$access('formOperasional.formDendaFoto', 'read')) {
        if (this.$access('formOperasional.formDendaFoto.read', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formDendaFoto.read', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formDendaFoto.read', 'dateMax')).format('YYYY-MM-DD'),
              undefined,
              '[]'
            )
          ) {

          } else {
            this.query.form_foto.tanggal_form = this.$moment().format('YYYY-MM-DD')
            this.queryData()
          }
        }
      }
      // Handle date change on denda action
      if (this.$access('formOperasional.formDendaFoto', 'denda')) {
        if (this.$access('formOperasional.formDendaFoto.denda', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formDendaFoto.denda', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formDendaFoto.denda', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.formDendaFoto', 'update')) {
        if (this.$access('formOperasional.formDendaFoto.update', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formDendaFoto.update', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formDendaFoto.update', 'dateMax')).format('YYYY-MM-DD'),
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
      if (this.$access('formOperasional.formDendaFoto', 'destroy')) {
        if (this.$access('formOperasional.formDendaFoto.destroy', 'timeFree')) {

        } else {
          if (
            this.$moment(this.utils.date).isBetween(
              this.$moment().subtract(this.$access('formOperasional.formDendaFoto.destroy', 'dateMin')).format('YYYY-MM-DD'),
              this.$moment().add(this.$access('formOperasional.formDendaFoto.destroy', 'dateMax')).format('YYYY-MM-DD'),
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
    addDendaRowDenda() {
      this.form.denda.data.d.push({
        denda_id: null,
        nominal: 0,
        keterangan: ''
      })
    },
    removeDendaRowDenda(i) {
      this.form.denda.data.d.splice(i, 1)
    },
    assignDendaNominalDenda(i) {
      let denda_foto_id = this.form.denda.data.d[i].denda_foto_id
      let selected = this.$_.findWhere(this.data.denda_foto, {id: denda_foto_id})

      this.form.denda.data.d[i].nominal = selected.nominal
    },
    addUpdateRowDenda() {
      this.form.update.data.d.push({
        denda_id: null,
        nominal: 0,
        keterangan: ''
      })
    },
    removeUpdateRowDenda(i) {
      this.form.update.data.d.splice(i, 1)
    },
    assignUpdateNominalDenda(i) {
      let denda_foto_id = this.form.update.data.d[i].denda_foto_id
      let selected = this.$_.findWhere(this.data.denda_foto, {id: denda_foto_id})

      this.form.update.data.d[i].nominal = selected.nominal
    },

    /**
     *  Form request handler
     */
    denda() {
      this.form.denda.loading = true
      this.form.denda.errors = {}
      this.$axios.post('/ajax/v1/form_operasional/form_denda_foto/denda', this.form.denda.data)
        .then(res => {
          this.queryData(false)
          this.hideDendaModal()
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.denda.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.denda.loading = false
        })
    },
    tidakDenda() {
      this.form.tidakDenda.loading = true
      this.form.tidakDenda.errors = {}
      this.$axios.post('/ajax/v1/form_operasional/form_denda_foto/tidak_denda', this.form.tidakDenda.data)
        .then(res => {
          this.queryData(false)
          this.hideTidakDendaModal()
        })
        .catch(err => {
          if (err.response.status == 422) {
            this.form.tidakDenda.errors = err.response.data.errors
          }
        })
        .finally(() => {
          this.form.tidakDenda.loading = false
        })
    },
    update() {
      this.form.update.loading = true
      this.form.update.errors = {}
      this.$axios.put('/ajax/v1/form_operasional/form_denda_foto', this.form.update.data)
        .then(res => {
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
      this.$axios.delete('/ajax/v1/form_operasional/form_denda_foto', { data: this.form.destroy.data })
        .then(res => {
          this.queryData(false)
          this.hideDestroyModal()
        })
        .catch(err => {})
        .finally(() => {
          this.form.destroy.loading = false
        })
    },
    generate() {
      this.form.generate.loading = true
      this.form.generate.errors = {}
      this.$axios.put('/ajax/v1/form_operasional/form_denda_foto/generate', this.form.generate.data)
        .then(res => {
          this.queryData(false)
          this.hideGenerateModal()
        })
        .catch(err => {})
        .finally(() => {
          this.form.generate.loading = false
        })
    }
  }
}
</script>