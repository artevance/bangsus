@extends('operasional.master.quality_control.wrapper')

@section('innerContent')
<div class="card-title">Parameter Quality Control</div>
@include('operasional.master.quality_control.parameter_quality_control.' . $role)
@endsection