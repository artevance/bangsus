@extends('layouts.app')

@section('content')
<h3>Kegiatan Kebersihan</h3>
@include('operasional.master.kegiatan_kebersihan.' . $role)
@endsection