@extends('layouts.app')

@section('content')
<h3>Impor Absensi</h3>
@include('hrd.absensi.impor_absensi.' . $role)
@endsection