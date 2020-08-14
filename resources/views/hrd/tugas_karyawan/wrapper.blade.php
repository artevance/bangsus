@extends('layouts.app')

@section('content')
<h3>Tugas Karyawan</h3>
@include('hrd.tugas_karyawan.' . $role)
@endsection