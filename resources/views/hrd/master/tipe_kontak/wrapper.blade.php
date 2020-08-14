@extends('layouts.app')

@section('content')
<h3>Tipe Kontak</h3>
@include('hrd.master.tipe_kontak.' . $role)
@endsection