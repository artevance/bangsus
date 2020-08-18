@extends('operasional.master.atribut_karyawan.wrapper')

@section('innerContent')
<div class="card-title">Atribut Karyawan</div>
@include('operasional.master.atribut_karyawan.atribut_karyawan.' . $role)
@endsection