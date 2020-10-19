<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\KelompokFoto;
use App\Http\Models\FormFoto;
use App\Http\Models\Cabang;

class LaporanFormDendaFoto
{
  public static function recap($request)
  {
    $container = [];

    $cabangs = ! is_null(Cabang::find($request->input('cabang_id')))
      ? Cabang::where('id', $request->input('cabang_id'))->get()
      : Cabang::all();

    $kelompokFotos = KelompokFoto::all();

    $penaltyTotal = 0;
    foreach ($cabangs as $cabang) {
      $data = [];
      $data['cabang']['kode_cabang'] = $cabang->kode_cabang;
      $data['cabang']['cabang'] = $cabang->cabang;

      $d = [];
      $sumCount = 0;
      $scoreSum = 0;
      foreach ($kelompokFotos as $kelompokFoto) {
        $formData = FormFoto::with('form_denda_foto')
          ->whereHas('tugas_karyawan', function ($q) use ($cabang, $kelompokFoto, $request) {
            $q->where('kelompok_foto_id', $kelompokFoto->id)
              ->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
                $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
                  ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
              });
          })->get();
        $penaltySubtotal = $formData->sum('total');
      }
      $data['kelompok_foto'] = $kelompokFoto->toArray();
      $data['subtotal'] = $penaltySubtotal;
      $penaltyTotal += $penaltySubtotal;

      $container[] = $data;
    }

    return [
      'total' => $penaltyTotal,
      'data' => $container
    ];
  }
}