<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormGeneralCleaning;
use App\Http\Models\KegiatanGeneralCleaning;
use App\Http\Models\Cabang;

class FormC5 extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormGeneralCleaning::with([
        'tugas_karyawan',
        'kegiatan_general_cleaning',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormGeneralCleaning::find($id)->exists()) return $this->response(404);

    return $this->data(FormGeneralCleaning::with([
      'tugas_karyawan',
      'kegiatan_general_cleaning',
      'user'
    ])->find($id))->response(200);
  }

  public function dailyBranchType(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        KegiatanGeneralCleaning::with([
          'form_general_cleaning' => function ($q) use ($query) {
            $q->byCabang($query['cabang_id'])
              ->where('tanggal_form', $query['tanggal_form'])
              ->orderBy('jam');
          },
          'area_general_cleaning'
        ])
        ->get()
      )->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kegiatan_general_cleaning_id',
      'skor',
      'keterangan'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'kegiatan_general_cleaning_id' => 'required|exists:kegiatan_general_cleaning,id',
      'skor' => 'required|numeric',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formGeneralCleaningModel = new FormGeneralCleaning;
    $formGeneralCleaningModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formGeneralCleaningModel->tanggal_form = $request->input('tanggal_form');
    $formGeneralCleaningModel->jam = $request->input('jam');
    $formGeneralCleaningModel->kegiatan_general_cleaning_id = $request->input('kegiatan_general_cleaning_id');
    $formGeneralCleaningModel->skor = $request->input('skor');
    $formGeneralCleaningModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formGeneralCleaningModel->user_id = $request->user()->id;
    $formGeneralCleaningModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kegiatan_general_cleaning_id',
      'skor',
      'keterangan'
    ), [
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'kegiatan_general_cleaning_id' => 'nullable|exists:kegiatan_general_cleaning,id',
      'skor' => 'nullable|numeric',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formGeneralCleaningModel = FormGeneralCleaning::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formGeneralCleaningModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formGeneralCleaningModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formGeneralCleaningModel->jam = $request->input('jam');
    if ($request->has('kegiatan_general_cleaning_id')) $formGeneralCleaningModel->kegiatan_general_cleaning_id = $request->input('kegiatan_general_cleaning_id');
    if ($request->has('skor')) $formGeneralCleaningModel->skor = $request->input('skor');
    if ($request->has('keterangan')) $formGeneralCleaningModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formGeneralCleaningModel->user_id = $request->user()->id;
    $formGeneralCleaningModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_goreng,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formGeneralCleaningModel = FormGeneralCleaning::find($request->input('id'));
    $formGeneralCleaningModel->user_id = $request->user()->id;
    $formGeneralCleaningModel->save();
    $formGeneralCleaningModel->delete();
  }
}