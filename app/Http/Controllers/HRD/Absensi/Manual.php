<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;
use App\Http\Models\Absensi as AbsensiModel;

class Manual extends Controller
{
  public function index(Request $request)
  {
    $this->title('Absensi Manual | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query([
        'cabang_id' => $request->query('cabang_id', 1),
        'tanggal_absensi' => $request->query('tanggal_absensi', date('Y-m-d')),
        'tipe_absensi_id' => $request->query('tipe_absensi_id', 1)
      ]);
    return view('hrd.absensi.manual.wrapper', $this->passParams(['cabangs' => CabangModel::all(), 'tipeAbsensis' => TipeAbsensiModel::all()]));
  }

  public function cabangTipeHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'tanggal_absensi' => 'required|date'
    ]);

    return ['data' => AbsensiModel::cabangTipeHarian($request->query('cabang_id'), $request->query('tipe_absensi_id'), $request->query('tanggal_absensi'))];
  }
}