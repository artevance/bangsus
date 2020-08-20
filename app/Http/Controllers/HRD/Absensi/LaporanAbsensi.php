<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanAbsensi extends Controller
{
  public function index(Request $request)
  {
    $this->title('Laporan Absensi | BangsusSys')
      ->role(
        $request
          ->user()->role->role_code
        );
    return view('hrd.absensi.laporan_absensi.wrapper', $this->passParams());
  }
}