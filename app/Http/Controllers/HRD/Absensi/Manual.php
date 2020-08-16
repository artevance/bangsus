<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\Absensi as AbsensiModel;

class Manual extends Controller
{
  public function index(Request $request)
  {
    $this->title('Absensi Manual | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query([
        'cabang_id' => $request->query('cabang_id', 1),
        'tanggal_absensi' => $request->query('tanggal_absensi', date('Y-m-d'))
      ]);
    return view('hrd.absensi.manual.wrapper', $this->passParams(['cabangs' => CabangModel::all()]));
  }

  public function cabangHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_absensi' => 'required|date'
    ]);

    return ['data' => AbsensiModel::cabangHarian($request->query('cabang_id'), $request->query('tanggal_absensi'))];
  }
}