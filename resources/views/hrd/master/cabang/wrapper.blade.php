@extends('layouts.app')

@section('content')
<h3>Cabang</h3>
@include('hrd.master.cabang.' . $role)
@endsection