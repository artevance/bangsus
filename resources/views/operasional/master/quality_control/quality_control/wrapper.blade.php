@extends('operasional.master.quality_control.wrapper')

@section('innerContent')
<div class="card-title">Opsi Parameter Quality Control</div>
@include('operasional.master.quality_control.quality_control.' . $role)
@endsection