@extends('operasional.master.form_aktivitas_marketing.wrapper')

@section('innerContent')
@include('operasional.master.form_aktivitas_marketing.item_marketing.' . $role)
@endsection