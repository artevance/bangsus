@extends('operasional.master.form_foto.wrapper')

@section('innerContent')
<div class="card-title">Kelompok Foto</div>
@include('operasional.master.form_foto.kelompok_foto.' . $role)
@endsection