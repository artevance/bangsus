@extends('layouts.app')

@section('content')
<h3>Aktivitas Karyawan</h3>
@include('operasional.master.aktivitas_karyawan.' . $role)
@endsection