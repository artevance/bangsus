@extends('layouts.app')

@section('content')
<h3>Satuan</h3>
@include('operasional.master.satuan.' . $role)
@endsection