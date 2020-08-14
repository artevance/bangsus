@extends('layouts.app')

@section('content')
<h3>Tipe Alamat</h3>
@include('master.tipe_alamat.' . $role)
@endsection