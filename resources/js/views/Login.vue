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
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label>Username</label>
                      <input type="username" class="form-control">
                    </div>
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control">
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
  created() {
    this.$axios.get('/sanctum/csrf-cookie').then(res => console.log(res))
  }
}
</script>