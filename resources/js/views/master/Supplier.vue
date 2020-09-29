<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.supplier', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.supplier.q" @keyup="queryData" v-if="$access('master.supplier', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.supplier', 'read')">
                <thead>
                  <th>#</th>
                  <th>Supplier</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(supplier, i) in data.supplier">
                    <td>{{ i + 1 }}</td>
                    <td>{{ supplier.supplier }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(supplier.id)" href="#" v-if="$access('master.supplier', 'update')">
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
    <div class="modal fade" data-entity="supplier" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.supplier', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Supplier</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Supplier</label>
                <input type="text" class="form-control" v-model="form.create.data.supplier">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.supplier" :key="index">
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
    <div class="modal fade" data-entity="supplier" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.supplier', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Supplier</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Supplier</label>
                <input type="text" class="form-control" v-model="form.update.data.supplier">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.supplier" :key="index">
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
        supplier: []
      },
      form: {
        create: {
          data: {
            supplier: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            supplier: ''
          },
          errors: {},
          loading: false
        }
      },
      query: {
        supplier: {
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
          this.data.supplier = res[0].data.container
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
          this.data.supplier = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/supplier?q=' + this.query.supplier.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="supplier"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/supplier/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            supplier: res.data.container.supplier
          }
          $('[data-entity="supplier"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/supplier', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            supplier: ''
          }
          this.prepare()
          $('[data-entity="supplier"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/supplier', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            supplier: ''
          }
          this.prepare()
          $('[data-entity="supplier"][data-method="update"]').modal('hide')
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