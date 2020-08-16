@extends('layouts.app')

@section('content')
<h3>Tipe Absensi</h3>
@include('hrd.master.tipe_absensi.' . $role)
@endsection