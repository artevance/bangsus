@extends('layouts.app')

@section('content')
<h3>Form C1</h3>
<div class="row mt-5">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="card">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <a class="nav-link @if($nav == 'thawingAyam') active @endif" href="{{ url('/operasional/form_c/produksi/thawing_ayam') }}">Thawing Ayam</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'goreng') active @endif" href="{{ url('/operasional/form_c/produksi/goreng') }}">Goreng</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'masakNasi') active @endif" href="{{ url('/operasional/form_c/produksi/masak_nasi') }}">Masak Nasi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'sambal') active @endif" href="{{ url('/operasional/form_c/produksi/sambal') }}">Sambal</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'tepung') active @endif" href="{{ url('/operasional/form_c/produksi/tepung') }}">Tepung</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'minyak') active @endif" href="{{ url('/operasional/form_c/produksi/minyak') }}">Minyak</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'margarin') active @endif" href="{{ url('/operasional/form_c/produksi/margarin') }}">Margarin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link @if($nav == 'lpg') active @endif" href="{{ url('/operasional/form_c/produksi/lpg') }}">LPG</a>
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