<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use App\Http\Models\FormThawingAyam;
use App\Http\Models\FormGoreng;
use App\Http\Models\FormMasakNasi;
use App\Http\Models\FormSambal;
use App\Http\Models\FormTepung;
use App\Http\Models\FormMinyak;
use App\Http\Models\FormMargarin;
use App\Http\Models\FormLPG;
use App\Http\Models\Cabang;

class LaporanFormC1
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

      $data['form_thawing_ayam'] = FormThawingAyam::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();
      $data['form_goreng'] = FormGoreng::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();
      $data['form_masak_nasi'] = FormMasakNasi::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();
      $data['form_sambal'] = FormSambal::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();
      $data['form_tepung'] = FormTepung::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();
      $data['form_minyak'] = FormMinyak::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();
      $data['form_margarin'] = FormMargarin::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();
      $data['form_lpg'] = FormLPG::whereHas('tugas_karyawan', function ($q) use ($cabang, $request) {
        $q->where('cabang_id', $cabang->id)->where(function ($q) use ($request) {
          $q->whereDate('tanggal_form', '>=', $request->input('tanggal_awal'))
            ->WhereDate('tanggal_form', '<=', $request->input('tanggal_akhir'));
        });
      })->count();

      $container[] = $data;
    }

    return [
      'data' => $container
    ];
  }
}