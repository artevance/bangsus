<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\TugasKaryawan;
use App\Http\Models\Absensi;

class LaporanKehadiran
{
  public static function index($request)
  {
    $tugasKaryawans = TugasKaryawan::with(['karyawan', 'cabang', 'divisi', 'jabatan'])
      ->where('cabang_id', $request->input('cabang_id'))
      ->where(function ($q) use ($request) {
        $q->whereDate('tanggal_mulai', '<=', $request->input('tanggal_akhir'))
          ->where(function ($q) use ($request) {
            $q->whereDate('tanggal_selesai', '>=', $request->input('tanggal_awal'))
              ->orWhere('tanggal_selesai', null);
          });
      })
      ->get();

    $dates = [];
    for ($i = strtotime($request->input('tanggal_awal')); $i <= strtotime($request->input('tanggal_akhir')); $i += 86400) {
      $dates[] = date('Y-m-d', $i);
    }

    foreach ($tugasKaryawans as $i => $tugasKaryawan) {
      $absensi = [];

      $count = 0;
      foreach ($dates as $date) {
        $d = Absensi::where('tugas_karyawan_id', $tugasKaryawan['id'])
          ->where('tipe_absensi_id', $request->input('tipe_absensi_id'))
          ->where('tanggal_absensi', $date)
          ->first();

        if ( ! is_null($d)) $d = $d->toArray();

        if (is_array($d)) {
          $d['kehadiran'] = ! is_null($d['jam_absen']);
          $count += $d['kehadiran'];
        } else {
          $d = [];
          $d['kehadiran'] = 0;
        }

        $absensi[] = $d;
      }
      $tugasKaryawan['absensi'] = $absensi;
      $tugasKaryawan['total_kehadiran'] = $count;

      $tugasKaryawans[$i] = $tugasKaryawan;
    }

    return [
      'meta' => [
        'dates' => $dates
      ],
      'data' => $tugasKaryawans
    ];
  }
}