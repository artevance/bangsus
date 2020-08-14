@extends('layouts.app')

@section('content')
<h3>Jabatan</h3>
@include('master.jabatan.' . $role)
@endsection