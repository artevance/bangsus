<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('master.itemGoreng', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.item_goreng.q" @keyup="queryData" v-if="$access('master.itemGoreng', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('master.itemGoreng', 'read')">
                <thead>
                  <th>#</th>
                  <th>Item Goreng</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(item_goreng, i) in data.item_goreng">
                    <td>{{ i + 1 }}</td>
                    <td>{{ item_goreng.item_goreng }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(item_goreng.id)" href="#" v-if="$access('master.itemGoreng', 'update')">
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
    <div class="modal fade" data-entity="itemGoreng" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.itemGoreng', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Item Goreng</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Item Goreng</label>
                <input type="text" class="form-control" v-model="form.create.data.item_goreng">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.item_goreng" :key="index">
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
    <div class="modal fade" data-entity="itemGoreng" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('master.itemGoreng', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Item Goreng</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Item Goreng</label>
                <input type="text" class="form-control" v-model="form.update.data.item_goreng">
                <small class="text-danger" v-for="(msg, index) in form.update.errors.item_goreng" :key="index">
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
        item_goreng: []
      },
      form: {
        create: {
          data: {
            item_goreng: ''
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            item_goreng: ''
          },
          errors: {},
          loading: false
        }
      },
      query: {
        item_goreng: {
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
          this.data.item_goreng = res[0].data.container
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
          this.data.item_goreng = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/master/item_goreng?q=' + this.query.item_goreng.q)
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      $('[data-entity="itemGoreng"][data-method="create"]').modal('show')
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/master/item_goreng/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            item_goreng: res.data.container.item_goreng
          }
          $('[data-entity="itemGoreng"][data-method="update"]').modal('show')
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/master/item_goreng', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            item_goreng: ''
          }
          this.prepare()
          $('[data-entity="itemGoreng"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/master/item_goreng', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            item_goreng: ''
          }
          this.prepare()
          $('[data-entity="itemGoreng"][data-method="update"]').modal('hide')
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