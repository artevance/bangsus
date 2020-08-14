@extends('layouts.app')

@section('content')
<h3>Divisi</h3>
@include('master.divisi.' . $role)
@endsection