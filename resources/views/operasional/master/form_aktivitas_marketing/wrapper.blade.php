@extends('layouts.app')

@section('content')
<h3>Form Aktivitas Marketing</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <a class="nav-link @if($nav == 'aktivitasMarketing') active @endif" href="{{ url('/operasional/master/form_aktivitas_marketing/aktivitas_marketing') }}">Aktivitas Marketing</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'itemMarketing') active @endif" href="{{ url('/operasional/master/form_aktivitas_marketing/item_marketing') }}">Item Marketing</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        @yield('innerContent')
      </div>
    </div>
  </div>
</div>
@endsection