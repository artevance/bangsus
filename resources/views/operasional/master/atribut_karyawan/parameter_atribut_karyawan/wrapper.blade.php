@extends('operasional.master.atribut_karyawan.wrapper')

@section('innerContent')
<div class="card-title">Parameter Atribut Karyawan</div>
@include('operasional.master.atribut_karyawan.parameter_atribut_karyawan.' . $role)
@endsection