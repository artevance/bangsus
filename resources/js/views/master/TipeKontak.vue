<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.tipeKontak', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.tipe_kontak.q" @keyup="queryData" v-if="$access('master.tipeKontak', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.tipeKontak', 'read')">
                <thead>
                  <th>#</th>
                  <th>Tipe Kontak</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(tipe_kontak, i) in data.tipe_kontak">
                    <td>{{ i + 1 }}</td>
                    <td>{{ tipe_kontak.tipe_kontak }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(tipe_kontak.id)" href="#" v-if="$access('master.tipeKontak', 'update')">
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
    <div class="modal fade" data-entity="tipeKontak" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.tipeKontak', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Tipe Kontak</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Tipe Kontak</label>
                <input type="text" class="form-control" v-model="form.create.data.tipe_kontak">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.tipe_kontak" :key="index">
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
    <div class="modal fade" data-entity="tipeKontak" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.tipeKontak', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Tipe Kontak</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Tipe Kontak</label>
                <input type="text" class="form-control" v-model="form.update.data.tipe_kontak">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.tipe_kontak" :key="index">
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
        tipe_kontak: []
      },
      form: {
        create: {
          data: {
            tipe_kontak: ''
          },
          errors: {}
        },
        update: {
          data: {
            id: null,
            tipe_kontak: ''
          },
          errors: {}
        }
      },
      query: {
        tipe_kontak: {
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
          this.data.tipe_kontak = res[0].data.container
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
          this.data.tipe_kontak = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/tipe_kontak?q=' + this.query.tipe_kontak.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="tipeKontak"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/tipe_kontak/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            tipe_kontak: res.data.container.tipe_kontak
          }
          $('[data-entity="tipeKontak"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/tipe_kontak', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            tipe_kontak: ''
          }
          this.prepare()
          $('[data-entity="tipeKontak"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/tipe_kontak', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            tipe_kontak: ''
          }
          this.prepare()
          $('[data-entity="tipeKontak"][data-method="update"]').modal('hide')
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