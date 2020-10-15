<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\FormAtributKaryawan;
use App\Http\Models\Cabang;

class LaporanFormC3
{
  public static function frequency($request)
  {
    $container = [];

    $cabangs = ! is_null(Cabang::find($request->input('cabang_id')))
      ? Cabang::where('id', $request->input('cabang_id'))->get()
      : Cabang::all();

    foreach ($cabangs as $cabang) {
      $data = [];
      $data['cabang']['kode_cabang'] = $cabang->kode_cabang;
      $data['cabang']['cabang'] = $cabang->cabang;

      $data['employee_count'] = FormAtributKaryawan::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
            $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
              ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
          });
      })->distinct('tugas_karyawan_id')->count();

      $data['count'] = FormAtributKaryawan::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
            $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
              ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
          });
      })->count();

      $data['foul'] = FormAtributKaryawan::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
            $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
              ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
          });
      })->whereHas('d.parameter_atribut_karyawan', function ($q) {$q->where('pelanggaran', 1);})->count();

      $data['frequency'] = (($data['count'] / 12) * 100) . '%';

      $container[] = $data;
    }

    return [
      'data' => $container
    ];
  }
}