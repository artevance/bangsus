<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;
use App\Http\Models\Absensi as AbsensiModel;

class ImporJadwal extends Controller
{
  public function index(Request $request)
  {
    $this->title('Impor Jadwal | BangsusSys')->role($request->user()->role->role_code);
    return view('hrd.absensi.impor_jadwal.wrapper', $this->passParams());
  }
}