@extends('operasional.master.tipe_proses_produksi.wrapper')

@section('innerContent')
@include('operasional.master.tipe_proses_produksi.tepung.' . $role)
@endsection