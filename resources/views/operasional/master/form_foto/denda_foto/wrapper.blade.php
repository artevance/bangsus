@extends('operasional.master.form_foto.wrapper')

@section('innerContent')
<div class="card-title">Denda Foto</div>
@include('operasional.master.form_foto.denda_foto.' . $role)
@endsection