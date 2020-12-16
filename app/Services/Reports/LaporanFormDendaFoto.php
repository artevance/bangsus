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

    $cabang = Cabang::find($request->input('cabang_id'));
    $kelompokFotos = KelompokFoto::all();
    $penaltyTotal = 0;

    foreach ($kelompokFotos as $kelompokFoto) {
      $formData = FormFoto::with('form_denda_foto')
        ->where(function ($q) use ($cabang, $request) {
          $q->whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
              $q->where('cabang_id', $cabang->id);
            })    
            ->orWhere(function ($q) use ($cabang) {
              $q->whereNull('tugas_karyawan_id')
                ->where('cabang_id', $cabang->id);
            });
        })
        ->where('kelompok_foto_id', $kelompokFoto->id)
        ->whereBetween('tanggal_form', [$request->input('tanggal_awal'), $request->input('tanggal_akhir')])
        ->get();

      $d = $kelompokFoto->toArray();
      $d['subtotal'] = $formData->sum('total');
      $penaltyTotal += $d['subtotal'];

      $container[] = $d;
    }

    return [
      'total' => $penaltyTotal,
      'data' => $container
    ];
  }
}