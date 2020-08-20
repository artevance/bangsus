@extends('layouts.app')

@section('content')
<h3>Laporan Keterlambatan</h3>
@include('hrd.absensi.laporan_keterlambatan.' . $role)
@endsection