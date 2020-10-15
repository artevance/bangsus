<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\TugasKaryawan;
use App\Http\Models\Absensi;

class LaporanAbsensi
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
      $penaltyCount = 0;
      $penaltyTotal = 0;
      foreach ($dates as $date) {
        $d = Absensi::where('tugas_karyawan_id', $tugasKaryawan['id'])
          ->where('tipe_absensi_id', $request->input('tipe_absensi_id'))
          ->where('tanggal_absensi', $date)
          ->whereNotNull('jam_absen')
          ->first();

        if ( ! is_null($d)) $d = $d->toArray();

        if (is_array($d)) {
          $latetimestamp = strtotime($d['jam_absen']) >= strtotime($d['jam_jadwal'])
            ? strtotime($d['jam_absen']) - strtotime($d['jam_jadwal'])
            : 0;
          $d['jam_keterlambatan'] = $latetimestamp > 0
            ? gmdate('H:i:s', $latetimestamp)
            : '';

          $d['denda'] = floor($latetimestamp / 60) * 5000;
          $d['denda'] = $d['denda'] > 25000 ? 25000 : $d['denda'];

          $sum += $latetimestamp;
          $count++;
          $penaltyTotal += $d['denda'];
          $penaltyCount += strtotime($d['jam_absen']) >= strtotime($d['jam_jadwal']) ? 1 : 0;
        }

        $absensi[] = $d;
      }
      $tugasKaryawan['absensi'] = $absensi;
      $tugasKaryawan['total_presensi'] = $count;
      $tugasKaryawan['total_hari_terlambat'] = $penaltyCount;
      $tugasKaryawan['total_keterlambatan'] = gmdate('H:i:s', $sum);
      $tugasKaryawan['total_denda'] = $penaltyTotal;

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