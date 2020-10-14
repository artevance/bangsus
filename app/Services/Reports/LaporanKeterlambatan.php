<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\TugasKaryawan;
use App\Http\Models\Absensi;

class LaporanKeterlambatan
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
      $sum = 0;
      foreach ($dates as $date) {
        $d = Absensi::where('tugas_karyawan_id', $tugasKaryawan['id'])
          ->where('tipe_absensi_id', $request->input('tipe_absensi_id'))
          ->where('tanggal_absensi', $date)
          ->whereRaw('jam_absen > jam_jadwal')
          ->whereNotNull('jam_absen')
          ->whereNotNull('jam_jadwal')
          ->first();

        if ( ! is_null($d)) $d = $d->toArray();

        if (is_array($d)) {
          $latetimestamp = strtotime($d['jam_absen']) - strtotime($d['jam_jadwal']);
          $d['jam_keterlambatan'] = gmdate('H:i:s', $latetimestamp);

          $sum += $latetimestamp;
          $count++;
        }

        $absensi[] = $d;
      }
      $tugasKaryawan['absensi'] = $absensi;
      $tugasKaryawan['total_hari_terlambat'] = $count;
      $tugasKaryawan['total_keterlambatan'] = gmdate('H:i:s', $sum);

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