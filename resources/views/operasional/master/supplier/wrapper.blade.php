@extends('layouts.app')

@section('content')
<h3>Supplier</h3>
@include('operasional.master.supplier.' . $role)
@endsection