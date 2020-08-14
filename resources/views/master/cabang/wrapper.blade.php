@extends('layouts.app')

@section('content')
<h3>Cabang</h3>
@include('master.cabang.' . $role)
@endsection