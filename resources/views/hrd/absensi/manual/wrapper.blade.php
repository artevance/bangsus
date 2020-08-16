@extends('layouts.app')

@section('content')
<h3>Absensi Manual</h3>
@include('hrd.absensi.manual.' . $role)
@endsection