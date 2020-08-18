@extends('layouts.app')

@section('content')
<h3>Form C3</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        @include('operasional.form_c.atribut_karyawan.' . $role)
      </div>
    </div>
  </div>
</div>
@endsection