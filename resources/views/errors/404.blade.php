@extends('layouts/plain')

@section('title', 'Error')

@section('content')
<div class="row w-100 mx-0 auth-page">
  <div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
    <h1 class="font-weight-bold mb-22 mt-2 tx-80 text-danger">404</h1>
    <h4 class="mb-2 text-danger">Page Not Found</h4>
    <h6 class="text-muted mb-3 text-center">Halaman yang anda cari tidak dapat kami temukan</h6>
    <a href="{{ url('/dashboard') }}" class="btn btn-primary">
      Kembali ke Dashboard
    </a>
  </div>
</div>
@endsection