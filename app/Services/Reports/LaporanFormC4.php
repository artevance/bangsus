<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\KegiatanKebersihan;
use App\Http\Models\FormKebersihan;
use App\Http\Models\Cabang;

class LaporanFormC4
{
  public static function frequency($request)
  {
    $container = [];

    $cabangs = ! is_null(Cabang::find($request->input('cabang_id')))
      ? Cabang::where('id', $request->input('cabang_id'))->get()
      : Cabang::all();

    $kegiatanKebersihans = KegiatanKebersihan::all();

    foreach ($cabangs as $cabang) {
      $data = [];
      $data['cabang']['kode_cabang'] = $cabang->kode_cabang;
      $data['cabang']['cabang'] = $cabang->cabang;

      $d = [];
      $sumCount = 0;
      $scoreSum = 0;
      foreach ($kegiatanKebersihans as $kegiatanKebersihan) {
        $formData = FormKebersihan::whereHas('tugas_karyawan', function ($q) use ($cabang, $kegiatanKebersihan, $request) {
          $q->where('kegiatan_kebersihan_id', $kegiatanKebersihan->id)
            ->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
              $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
                ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
            });
        })->get();
        $count = $formData->count();

        $d[] = $count;
        $sumCount += $count;
        $scoreSum += $formData->sum('skor');
      }
      $data['graph'] = $d;
      $data['frequency'] = $sumCount . '%';
      $data['total_skor'] = $scoreSum;
      $data['skor_rr'] = $sumCount == 0 ? 0 : $scoreSum / $sumCount;

      $container[] = $data;
    }

    return [
      'meta' => [
        'kegiatan_kebersihan' => $kegiatanKebersihans
      ],
      'data' => $container
    ];
  }
}