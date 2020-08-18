@extends('layouts.app')

@section('content')
<h3>General Cleaning</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-body">
        @yield('innerContent')
      </div>
    </div>
  </div>
</div>
@endsection