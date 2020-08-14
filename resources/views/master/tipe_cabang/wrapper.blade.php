@extends('layouts.app')

@section('content')
<h3>Tipe Cabang</h3>
@include('master.tipe_cabang.' . $role)
@endsection