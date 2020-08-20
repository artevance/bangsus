@extends('layouts.app')

@section('content')
<h3>Laporan Log Absen</h3>
@include('hrd.absensi.laporan_log_absen.' . $role)
@endsection