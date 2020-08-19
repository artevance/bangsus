<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;
use App\Http\Models\Absensi as AbsensiModel;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;

class Manual extends Controller
{
  public function index(Request $request)
  {
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tanggal_absensi' => $request->query('tanggal_absensi', date('Y-m-d')),
          'tipe_absensi_id' => $request->query('tipe_absensi_id', 1)
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
          'tanggal_absensi' => date('Y-m-d'),
          'tipe_absensi_id' => $request->query('tipe_absensi_id', 1)
        ];
      break;
      default :
    }

    $this->title('Absensi Manual | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('hrd.absensi.manual.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'tipeAbsensis' => TipeAbsensiModel::all()
      ])
    );
  }

  public function cabangTipeHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'tanggal_absensi' => 'required|date'
    ]);

    return [
      'data' => 
        TugasKaryawanModel::with([
            'absensi' => function ($q) use ($request) {
              $q->where('tanggal_absensi', '=', $request->input('tanggal_absensi'))
                ->where('tipe_absensi_id', '=', $request->input('tipe_absensi_id'));
            },
              'absensi.tipe_absensi',
            'cabang',
            'jabatan',
            'divisi',
            'karyawan'
          ])
        ->where('cabang_id', $request->query('cabang_id'))
        ->where('tanggal_mulai', '<=', $request->input('tanggal_absensi'))
        ->where(function ($query) use ($request) {
            $query->where('tanggal_selesai', '>=', $request->input('tanggal_absensi'))
              ->orWhere('tanggal_selesai', null);
          })
        ->get()
    ];
  }
}