@extends('layouts.app')

@section('content')
<h3>Tipe Foto Karyawan</h3>
@include('hrd.master.tipe_foto_karyawan.' . $role)
@endsection