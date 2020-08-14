@extends('layouts.app')

@section('content')
<h3>Dashboard</h3>
@include('dashboards.' . $role)
@endsection