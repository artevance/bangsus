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
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tanggal_form' => $request->query('tanggal_form', date('Y-m-d'))
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
          'tanggal_form' => date('Y-m-d'),
        ];
      break;
      default :
    }

    $this->title('Absensi Manual | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
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