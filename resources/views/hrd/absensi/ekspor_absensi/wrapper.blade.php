@extends('layouts.app')

@section('content')
<h3>Ekspor Absensi</h3>
@include('hrd.absensi.ekspor_absensi.' . $role)
@endsection