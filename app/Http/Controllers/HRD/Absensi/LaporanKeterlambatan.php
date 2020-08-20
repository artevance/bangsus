<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;

class LaporanKeterlambatan extends Controller
{
  public function index(Request $request)
  {
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tipe_absensi_id' => $request->query('tipe_absensi_id', 1),
          'tanggal_awal' => $request->query('tanggal_awal', date('Y-m-d')),
          'tanggal_akhir' => $request->query('tanggal_akhir', date('Y-m-d'))
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
        'tipe_absensi_id' => $request->query('tipe_absensi_id', 1),
        'tanggal_awal' => $request->query('tanggal_awal', date('Y-m-d')),
        'tanggal_akhir' => $request->query('tanggal_akhir', date('Y-m-d'))
        ];
      break;
      default :
    }

    $this->title('Laporan Keterlambatan | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);

    $generated = false;
    $results = [];
    if ($request->has('submit')) {
      $generated = true;
      $results = TugasKaryawanModel::with([
        'absensi' => function ($q) use ($request) {
          $q->where('tanggal_absensi', '>=', $request->query('tanggal_awal'))
            ->where('tanggal_absensi', '<=', $request->query('tanggal_akhir'))
            ->where('tipe_absensi_id', '=', $request->query('tipe_absensi_id'))
            ->selectRaw('*,
                CASE
                  WHEN jam_jadwal < jam_absen THEN TIMEDIFF(jam_absen, jam_jadwal)
                  ELSE null
                END AS keterlambatan
              ');
        },
          'absensi.tipe_absensi',
        'cabang',
        'jabatan',
        'divisi',
        'karyawan'
      ])
      ->where('cabang_id', $request->query('cabang_id'))
      ->where('tanggal_mulai', '<=', $request->query('tanggal_akhir'))
      ->where(function ($query) use ($request) {
          $query->where('tanggal_selesai', '>=', $request->query('tanggal_awal'))
            ->orWhere('tanggal_selesai', null);
        })
      ->get();
    }

    return view(
      'hrd.absensi.laporan_keterlambatan.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'tipeAbsensis' => TipeAbsensiModel::all(),
        'generated' => $generated,
        'results' => $results
      ])
    );
  }
}