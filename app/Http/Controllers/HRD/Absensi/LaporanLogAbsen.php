<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanLogAbsen extends Controller
{
  public function index(Request $request)
  {
    $this->title('Laporan Log Absensi | BangsusSys')
      ->role(
        $request
          ->user()->role->role_code
        );
    return view('hrd.absensi.laporan_log_absen.wrapper', $this->passParams());
  }
}