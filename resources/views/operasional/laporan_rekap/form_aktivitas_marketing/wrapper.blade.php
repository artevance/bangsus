@extends('layouts.app')

@section('content')
<h3>Laporan Rekap Form Aktivitas Marketing</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        @include('operasional.laporan_rekap.form_aktivitas_marketing.' . $role)
      </div>
    </div>
  </div>
</div>
@endsection