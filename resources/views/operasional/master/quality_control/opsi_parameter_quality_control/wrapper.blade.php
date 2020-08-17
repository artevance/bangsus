@extends('operasional.master.quality_control.wrapper')

@section('innerContent')
<div class="card-title">Opsi Parameter QC</div>
@include('operasional.master.quality_control.opsi_parameter_quality_control.' . $role)
@endsection