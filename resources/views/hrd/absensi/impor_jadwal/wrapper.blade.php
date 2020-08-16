@extends('layouts.app')

@section('content')
<h3>Impor Jadwal</h3>
@include('hrd.absensi.impor_jadwal.' . $role)
@endsection