<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.tipeProsesMargarin', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.tipe_proses_margarin.q" @keyup="queryData" v-if="$access('master.tipeProsesMargarin', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.tipeProsesMargarin', 'read')">
                <thead>
                  <th>#</th>
                  <th>Tipe Proses Margarin</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(tipe_proses_margarin, i) in data.tipe_proses_margarin">
                    <td>{{ i + 1 }}</td>
                    <td>{{ tipe_proses_margarin.tipe_proses_margarin }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(tipe_proses_margarin.id)" href="#" v-if="$access('master.tipeProsesMargarin', 'update')">
                        Ubah
                      </a>
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
    <div class="modal fade" data-entity="tipeProsesMargarin" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.tipeProsesMargarin', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Tipe Proses Margarin</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Tipe Proses Margarin</label>
                <input type="text" class="form-control" v-model="form.create.data.tipe_proses_margarin">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.tipe_proses_margarin" :key="index">
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
    <div class="modal fade" data-entity="tipeProsesMargarin" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.tipeProsesMargarin', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Tipe Proses Margarin</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Tipe Proses Margarin</label>
                <input type="text" class="form-control" v-model="form.update.data.tipe_proses_margarin">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.tipe_proses_margarin" :key="index">
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
</template>

<script>
export default {
  data() {
    return {
      state: { page: { loading: true } },
      data: {
        tipe_proses_margarin: []
      },
      form: {
        create: {
          data: {
            tipe_proses_margarin: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            tipe_proses_margarin: ''
          },
          errors: {},
          loading: false
        }
      },
      query: {
        tipe_proses_margarin: {
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
        this.fetchMainData()
      ])
        .then(res => {
          this.data.tipe_proses_margarin = res[0].data.container
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
          this.data.tipe_proses_margarin = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/tipe_proses_margarin?q=' + this.query.tipe_proses_margarin.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="tipeProsesMargarin"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/tipe_proses_margarin/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            tipe_proses_margarin: res.data.container.tipe_proses_margarin
          }
          $('[data-entity="tipeProsesMargarin"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/tipe_proses_margarin', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            tipe_proses_margarin: ''
          }
          this.prepare()
          $('[data-entity="tipeProsesMargarin"][data-method="create"]').modal('hide')
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
      this.form.update.errors = {}
      this.$axios.put('/ajax/v1/master/tipe_proses_margarin', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            tipe_proses_margarin: ''
          }
          this.prepare()
          $('[data-entity="tipeProsesMargarin"][data-method="update"]').modal('hide')
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