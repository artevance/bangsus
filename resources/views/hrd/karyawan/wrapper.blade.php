@extends('layouts.app')

@section('content')
<h3>Karyawan</h3>
@include('hrd.karyawan.' . $role)
@endsection