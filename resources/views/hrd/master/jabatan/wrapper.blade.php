@extends('layouts.app')

@section('content')
<h3>Jabatan</h3>
@include('hrd.master.jabatan.' . $role)
@endsection