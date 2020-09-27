<template>
  <div class="page-wrapper full-page">
    <div class="page-content d-flex align-items-center justify-content-center">
      <div class="row w-100 mx-0 auth-page">
        <div class="col-12 col-md-8 col-lg-6 col-xl-4 mx-auto">
          <div class="card">
            <div class="row">
              <div class="col-md-12">
                <div class="auth-form-wrapper p-5">
                  <a href="#" class="noble-ui-logo d-block mb-2">Bangsus<span>.Sys</span></a>
                  <h5 class="text-muted font-weight-normal mb-4">Selamat Datang Kembali! Silahkan login untuk melanjutkan.</h5>
                  <div class="alert alert-warning" v-if="form.login.errors.length > 0">
                    Login Gagal
                  </div>
                  <form class="forms-sample" @submit.prevent="login">
                    <div class="form-group">
                      <label>Username</label>
                      <input type="text" class="form-control" v-model="form.login.data.username">
                      <small class="text-danger" v-for="(msg, index) in form.login.errors.username" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" v-model="form.login.data.password">
                      <small class="text-danger" v-for="(msg, index) in form.login.errors.password" :key="index">
                        {{ msg }}
                      </small>
                    </div>
                    <div class="mt-5">
                      <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </div>
                    <div class="mt-5">
                      Belum punya akun? <a href="#" class="mt-3">Daftar disini.</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        login: {
          data: {
            username: '',
            password: ''
          },
          errors: {},
          loading: false
        }
      }
    }
  },

  methods: {
    login() {
      this.$axios.get('/sanctum/csrf-cookie').then(res => {
        this.form.login.loading = true;
        this.form.login.errors = {};
        this.$axios.post('/ajax/v1/login', this.form.login.data)
          .then(res => {
            this.$store.dispatch('setLoggedIn', true)
            this.$router.push({ name: 'dashboard' })
          })
          .catch(err => { console.log(err.response)
            this.form.login.response = false
            if (err.response.status == 422) this.form.login.errors = err.response.data.errors
          })
          .finally(() => {
            this.form.login.loading = false
          })
      })
    }
  }
}
</script>