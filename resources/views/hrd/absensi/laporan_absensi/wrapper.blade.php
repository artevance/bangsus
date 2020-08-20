@extends('layouts.app')

@section('content')
<h3>Laporan Absensi</h3>
@include('hrd.absensi.laporan_absensi.' . $role)
@endsection