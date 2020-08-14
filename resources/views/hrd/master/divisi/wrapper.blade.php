@extends('layouts.app')

@section('content')
<h3>Divisi</h3>
@include('hrd.master.divisi.' . $role)
@endsection