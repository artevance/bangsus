@extends('layouts.app')

@section('content')
<h3>Tipe Proses Produksi</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <a class="nav-link @if($nav == 'sambal') active @endif" href="{{ url('/operasional/master/tipe_proses_produksi/sambal') }}">Sambal</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'tepung') active @endif" href="{{ url('/operasional/master/tipe_proses_produksi/tepung') }}">Tepung</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'minyak') active @endif" href="{{ url('/operasional/master/tipe_proses_produksi/minyak') }}">Minyak</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'margarin') active @endif" href="{{ url('/operasional/master/tipe_proses_produksi/margarin') }}">Margarin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'lpg') active @endif" href="{{ url('/operasional/master/tipe_proses_produksi/lpg') }}">LPG</a>
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