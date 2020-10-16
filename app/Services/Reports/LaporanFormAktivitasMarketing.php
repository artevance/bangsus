<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\FormAktivitasMarketing;
use App\Http\Models\Cabang;

class LaporanFormAktivitasMarketing
{
  public static function recap($request)
  {
    $container = [];

    $cabangs = ! is_null(Cabang::find($request->input('cabang_id')))
      ? Cabang::where('id', $request->input('cabang_id'))->get()
      : Cabang::all();

    foreach ($cabangs as $cabang) {
      $data = [];
      $data['cabang']['kode_cabang'] = $cabang->kode_cabang;
      $data['cabang']['cabang'] = $cabang->cabang;

      $data['form_aktivitas_marketing'] = FormAktivitasMarketing::with(['aktivitas_marketing', 'item_marketing', 'satuan', 'tugas_karyawan'])
        ->whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
          $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
            $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
              ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
          });
        })->get()->toArray();

      $container[] = $data;
    }

    return [
      'data' => $container
    ];
  }
}