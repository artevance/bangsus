<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\QualityControl;
use App\Http\Models\FormQualityControl;
use App\Http\Models\Cabang;

class LaporanFormC2
{
  public static function frequency($request)
  {
    $container = [];

    $cabangs = ! is_null(Cabang::find($request->input('cabang_id')))
      ? Cabang::where('id', $request->input('cabang_id'))->get()
      : Cabang::all();

    $qualityControls = QualityControl::all();

    foreach ($cabangs as $cabang) {
      $data = [];
      $data['cabang']['kode_cabang'] = $cabang->kode_cabang;
      $data['cabang']['cabang'] = $cabang->cabang;

      $d = [];
      $sumCount = 0;
      foreach ($qualityControls as $qualityControl) {
        $count = FormQualityControl::whereHas('tugas_karyawan', function ($q) use ($cabang, $qualityControl, $request) {
          $q->where('quality_control_id', $qualityControl->id)
            ->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
              $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
                ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
            });
        })->count();

        $d[] = $count;
        $sumCount += $count;
      }
      $data['graph'] = $d;
      $data['frequency'] = (($sumCount / (6 * $qualityControls->count())) * 100) . '%';

      $container[] = $data;
    }

    return [
      'meta' => [
        'quality_control' => $qualityControls
      ],
      'data' => $container
    ];
  }
}