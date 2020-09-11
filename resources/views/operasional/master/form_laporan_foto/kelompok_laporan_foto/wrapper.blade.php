@extends('operasional.master.form_laporan_foto.wrapper')

@section('innerContent')
<div class="card-title">Kelompok Laporan Foto</div>
@include('operasional.master.form_laporan_foto.kelompok_laporan_foto.' . $role)
@endsection