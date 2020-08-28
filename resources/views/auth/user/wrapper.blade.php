@extends('layouts.app')

@section('content')
<h3>User</h3>
@include('auth.user.' . $role)
@endsection