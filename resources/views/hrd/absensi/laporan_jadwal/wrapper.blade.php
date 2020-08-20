@extends('layouts.app')

@section('content')
<h3>Laporan Jadwal</h3>
@include('hrd.absensi.laporan_jadwal.' . $role)
@endsection