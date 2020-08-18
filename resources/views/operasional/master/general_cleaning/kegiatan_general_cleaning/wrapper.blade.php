@extends('operasional.master.general_cleaning.wrapper')

@section('innerContent')
<div class="card-title">Kegiatan General Cleaning</div>
@include('operasional.master.general_cleaning.kegiatan_general_cleaning.' . $role)
@endsection