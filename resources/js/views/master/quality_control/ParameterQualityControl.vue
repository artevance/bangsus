<template>
  <transition name="fade" mode="out-in">
    <div class="row mt-5" v-if="$route.name == 'master.qualityControl.parameterQualityControl'">
      <transition name="fade" mode="out-in">
        <preloader-component v-if="state.page.loading"/>
        <div class="col-12 col-xl-12 stretch-card" v-else>
          <div class="card">
            <div class="card-body">
              <router-link :to="{ name: 'master.qualityControl' }">
                <i class="fas fa-backspace"></i> Kembali
              </router-link>
              <div class="row">
                <div class="col">
                  <div class="card-title">{{ data.parent.quality_control }}</div>
                </div>
              </div>
              <button class="btn btn-primary mt-3" @click="showCreateModal" v-if="$access('master.qualityControl.parameterQualityControl', 'create')">Tambah</button>
              <div class="row mt-5">
                <div class="col-12 col-md-6">
                  <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.parameter_quality_control.q" @keyup="queryData" v-if="$access('master.qualityControl.parameterQualityControl', 'read')">
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table table-hover" v-if="$access('master.qualityControl.parameterQualityControl', 'read')">
                  <thead>
                    <th>#</th>
                    <th>Aktivitas Karyawan</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <tr v-for="(parameter_quality_control, i) in data.parameter_quality_control">
                      <td>{{ i + 1 }}</td>
                      <td>{{ parameter_quality_control.parameter_quality_control }}</td>
                      <td>
                        <a class="badge badge-warning" @click="showUpdateModal(parameter_quality_control.id)" href="#" v-if="$access('master.qualityControl.parameterQualityControl', 'update')">
                          Ubah
                        </a>
                        <router-link class="badge badge-info"
                          :to="{ name: 'master.qualityControl.parameterQualityControl.opsiParameterQualityControl', params: { cid: parameter_quality_control.id } }"
                          v-if="$access('master.qualityControl.parameterQualityControl.opsiParameterQualityControl', 'access')"
                          >
                          Lihat Opsi Parameter
                        </router-link>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </transition>

      <!-- Modal -->
      <div class="modal fade" data-entity="parameterQualityControl" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.qualityControl.parameterQualityControl', 'create')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="create">
              <div class="modal-header">
                <h5 class="modal-title">Tambah Aktivitas Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Aktivitas Karyawan</label>
                  <input type="text" class="form-control" v-model="form.create.data.parameter_quality_control">
                  <small class="text-danger" v-for="(msg, index) in form.create.errors.parameter_quality_control" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.create.loading">
                  <spinner-component size="sm" color="light" v-if="form.create.loading"/>
                  Tambah
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" data-entity="parameterQualityControl" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.qualityControl.parameterQualityControl', 'update')">
        <div class="modal-dialog">
          <div class="modal-content">
            <form @submit.prevent="update">
              <div class="modal-header">
                <h5 class="modal-title">Ubah Aktivitas Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Aktivitas Karyawan</label>
                  <input type="text" class="form-control" v-model="form.update.data.parameter_quality_control">
                  <small class="text-danger" v-for="(msg, index) in form.update.errors.parameter_quality_control" :key="index">
                    {{ msg }}
                  </small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" :disabled="form.update.loading">
                  <spinner-component size="sm" color="light" v-if="form.update.loading"/>
                  Ubah
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
      state: { page: { loading: true } },
      data: {
        parameter_quality_control: [],
        parent: []
      },
      form: {
        create: {
          data: {
            parameter_quality_control: '',
            quality_control_id: this.$route.params.id
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            parameter_quality_control: ''
          },
          errors: {}
        }
      },
      query: {
        parameter_quality_control: {
          q: ''
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
        this.fetchMainData(),
        this.fetchParentData()
      ])
        .then(res => {
          this.data.parameter_quality_control = res[0].data.container
          this.data.parent = res[1].data.container
          this.state.page.loading = false
        })
        .catch(err => {
          this.$router.go(-1)
        }) 
    },
    /**
     *  Query result.
     */
    queryData() {
      this.fetchMainData()
        .then(res => {
          this.data.parameter_quality_control = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/parameter_quality_control/parent/' + this.$route.params.id + '?q=' + this.query.parameter_quality_control.q)
    },
    /**
     *  Fetch parent data
     */
    fetchParentData() {
      return this.$axios.get('/ajax/v1/master/quality_control/' + this.$route.params.id)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="parameterQualityControl"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/parameter_quality_control/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            parameter_quality_control: res.data.container.parameter_quality_control
          }
          $('[data-entity="parameterQualityControl"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.$axios.post('/ajax/v1/master/parameter_quality_control', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            parameter_quality_control: '',
            quality_control_id: this.$route.params.id
          }
          this.prepare()
          $('[data-entity="parameterQualityControl"][data-method="create"]').modal('hide')
        })
        .catch(err => { console.log(err.response.data)
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
      this.$axios.put('/ajax/v1/master/parameter_quality_control', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            parameter_quality_control: ''
          }
          this.prepare()
          $('[data-entity="parameterQualityControl"][data-method="update"]').modal('hide')
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
  }
}
</script>