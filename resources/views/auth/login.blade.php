@extends('layouts.plain')

@section('content')
<div class="row w-100 mx-0 auth-page">
  <div class="col-md-8 col-xl-3 mx-auto">
    <div class="card">
      <div class="row">
        <div class="col-md-12">
          <div class="auth-form-wrapper p-5">
            <a href="#" class="noble-ui-logo d-block mb-2">Bangsus<span>.Sys</span></a>
            <h5 class="text-muted font-weight-normal mb-4">Selamat Datang Kembali! Silahkan login untuk melanjutkan.</h5>
            @if ($errors->any())
              <div class="alert alert-danger" role="alert">
                Username atau Password anda salah
              </div>
            @endif
            <form class="forms-sample" method="post" action="{{ url('/authenticate') }}">
              @csrf
              <div class="form-group">
                <label>Username</label>
                <input type="username" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Password">
              </div>
              <div class="mt-5">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
              </div>
              <div class="mt-5">
                Belum punya akun? <a href="{{ url('/register') }}" class="mt-3">Daftar disini.</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection