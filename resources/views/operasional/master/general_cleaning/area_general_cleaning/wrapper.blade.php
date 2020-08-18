@extends('operasional.master.general_cleaning.wrapper')

@section('innerContent')
<div class="card-title">Area General Cleaning</div>
@include('operasional.master.general_cleaning.area_general_cleaning.' . $role)
@endsection