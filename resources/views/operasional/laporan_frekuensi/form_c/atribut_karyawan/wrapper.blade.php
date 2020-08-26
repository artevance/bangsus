@extends('layouts.app')

@section('content')
<h3>Laporan Rekap Form C3</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        @include('operasional.laporan_frekuensi.form_c.atribut_karyawan.' . $role)
      </div>
    </div>
  </div>
</div>
@endsection