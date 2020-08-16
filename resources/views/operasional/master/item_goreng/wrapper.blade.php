@extends('layouts.app')

@section('content')
<h3>Item Goreng</h3>
@include('operasional.master.item_goreng.' . $role)
@endsection