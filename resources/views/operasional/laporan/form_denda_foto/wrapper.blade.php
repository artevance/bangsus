@extends('layouts.app')

@section('content')
<h3>Laporan Form Denda Foto</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        @include('operasional.laporan.form_denda_foto.' . $role)
      </div>
    </div>
  </div>
</div>
@endsection