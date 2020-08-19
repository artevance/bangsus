@extends('layouts.app')

@section('content')
<h3>Pengajuan Jadwal Absensi</h3>
@include('hrd.absensi.pengajuan_jadwal.' . $role)
@endsection