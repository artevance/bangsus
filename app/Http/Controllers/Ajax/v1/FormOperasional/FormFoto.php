<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormFoto as FormFotoModel;

class FormFoto extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormGorengModel::with([
        'tugas_karyawan',
        'kelompok_foto'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormGorengModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormGorengModel::with([
      'tugas_karyawan',
      'kelompok_foto',
      'supplier',
      'satuan',
      'user'
    ])->find($id))->response(200);
  }

  public function dailyBranch(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        FormGorengModel::with([
          'tugas_karyawan',
          'kelompok_foto',
          'supplier',
          'satuan',
          'user'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }
}