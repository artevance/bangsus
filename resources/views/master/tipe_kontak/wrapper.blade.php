@extends('layouts.app')

@section('content')
<h3>Tipe Kontak</h3>
@include('master.tipe_kontak.' . $role)
@endsection