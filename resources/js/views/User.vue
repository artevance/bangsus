<template>
  <div class="row mt-5">
    <transition name="fade" mode="out-in">
      <preloader-component v-if="state.page.loading"/>
      <div class="col-12 col-xl-12 stretch-card" v-else>
        <div class="card">
          <div class="card-body">
            <button class="btn btn-primary" @click="showCreateModal" v-if="$access('user', 'create')">Tambah</button>
            <div class="row mt-5">
              <div class="col-12 col-md-6">
                <input type="text" class="form-control" placeholder="Cari sesuatu ..." v-model="query.user.q" @keyup="queryData" v-if="$access('user', 'read')">
              </div>
            </div>
            <div class="table-responsive mt-2">
              <table class="table table-hover" v-if="$access('user', 'read')">
                <thead>
                  <th>#</th>
                  <th>Username</th>
                  <th>Role</th>
                  <th>Aksi</th>
                </thead>
                <tbody>
                  <tr v-for="(user, i) in data.user">
                    <td>{{ i + 1 }}</td>
                    <td>{{ user.username }}</td>
                    <td>{{ user.role.role_name }}</td>
                    <td>
                      <a class="badge badge-warning" @click="showUpdateModal(user.id)" href="#" v-if="$access('user', 'update')">
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
    <div class="modal fade" data-entity="user" data-method="create" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('user', 'create')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="create">
            <div class="modal-header">
              <h5 class="modal-title">Tambah User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" v-model="form.create.data.username" autocomplete="nope">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.username" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" v-model="form.create.data.password" autocomplete="new-password">
                <small class="text-danger" v-for="(msg, index) in form.create.errors.password" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Role</label>
                <select class="form-control" v-model="form.create.data.role_id">
                  <option value="null">-- Pilih Role --</option>
                  <option v-for="role in data.role" :value="role.id">
                    {{ role.role_name }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.create.errors.role_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              {{ (typeof $_.findWhere(data.role, { id: form.create.data.role_id, akses_semua_cabang: 0 })) }}
              <div class="form-group" v-if="$_.findWhere(data.role, { id: form.create.data.role_id, akses_semua_cabang: 0 }) !== undefined">
                <label>Akses Cabang</label>
                <div class="form-check" v-for="(cabang, i) in data.cabang">
                  <input class="form-check-input m-0" type="checkbox" :value="cabang.id" v-model="form.create.data.cabang_id">
                  <label class="form-check-label">
                    {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                  </label>
                </div>
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
    <div class="modal fade" data-entity="user" data-method="update" data-backdrop="static" data-keyboard="false" tabindex="-1" v-if="$access('user', 'update')">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="update">
            <div class="modal-header">
              <h5 class="modal-title">Ubah User</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" v-model="form.update.data.username" autocomplete="nope" readonly>
                <small class="text-danger" v-for="(msg, index) in form.update.errors.username" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group">
                <label>Role</label>
                <select class="form-control" v-model="form.update.data.role_id">
                  <option value="null">-- Pilih Role --</option>
                  <option v-for="role in data.role" :value="role.id">
                    {{ role.role_name }}
                  </option>
                </select>
                <small class="text-danger" v-for="(msg, index) in form.update.errors.role_id" :key="index">
                  {{ msg }}
                </small>
              </div>
              <div class="form-group" v-if="$_.findWhere(data.role, { id: form.update.data.role_id, akses_semua_cabang: 0 }) != null">
                <label>Akses Cabang</label>
                <div class="form-check" v-for="(cabang, i) in data.cabang">
                  <input class="form-check-input m-0" type="checkbox" :value="cabang.id" v-model="form.update.data.cabang_id">
                  <label class="form-check-label">
                    {{ cabang.kode_cabang }} - {{ cabang.cabang }}
                  </label>
                </div>
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
        user: [],
        role: []
      },
      form: {
        create: {
          data: {
            username: '',
            password: '',
            role_id: '',
            cabang_id: [],
          },
          errors: {},
          loading: false
        },
        update: {
          data: {
            id: null,
            role_id: '',
            cabang_id: [],
          },
          errors: {},
          loading: false
        }
      },
      query: {
        user: {
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
          this.data.user = res[0].data.container
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
          this.data.user = res.data.container
        })
        .catch(err => {})
    },
    /**
     *  Fetch data
     */
    fetchMainData() {
      return this.$axios.get('/ajax/v1/user?q=' + this.query.user.q)
    },
    fetchRole() {
      return this.$axios.get('/ajax/v1/master/role')
    },
    fetchCabang() {
      return this.$axios.get('/ajax/v1/master/cabang')
    },

    /**
     *  Modal functionality
     */
    showCreateModal() {
      Promise.all([
        this.fetchRole(),
        this.fetchCabang()
      ])
        .then(res => {
          this.data.role = res[0].data.container
          this.data.cabang = res[1].data.container
          $('[data-entity="user"][data-method="create"]').modal('show')
        })
    },
    showUpdateModal(id) {
      this.form.update.data = {}
      this.$axios.get('/ajax/v1/user/' + id)
        .then(res => {
          this.form.update.data = {
            id: id,
            username: res.data.container.username,
            role_id: res.data.container.role_id,
            cabang_id: []
          }
          res.data.container.user_cabang.forEach((item, i) => {
            this.form.update.data.cabang_id.push(item.cabang_id)
          })
          Promise.all([
            this.fetchRole(),
            this.fetchCabang()
          ])
            .then(res => {
              this.data.role = res[0].data.container
              this.data.cabang = res[1].data.container
              $('[data-entity="user"][data-method="update"]').modal('show')
            })
        })
    },

    /**
     *  Form request handler
     */
    create() {
      this.form.create.loading = true
      this.form.create.errors = {}
      this.$axios.post('/ajax/v1/user', this.form.create.data)
        .then(res => {
          this.form.create.data = {
            user: ''
          }
          this.prepare()
          $('[data-entity="user"][data-method="create"]').modal('hide')
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
      this.$axios.put('/ajax/v1/user', this.form.update.data)
        .then(res => {
          this.form.update.data = {
            id: null,
            role_id: '',
            cabang_id: [],
          }
          this.prepare()
          $('[data-entity="user"][data-method="update"]').modal('hide')
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