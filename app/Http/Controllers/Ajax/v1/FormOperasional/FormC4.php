<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormKebersihan;
use App\Http\Models\Cabang;

class FormC4 extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormKebersihan::with([
        'tugas_karyawan',
        'kegiatan_kebersihan',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormKebersihan::find($id)->exists()) return $this->response(404);

    return $this->data(FormKebersihan::with([
      'tugas_karyawan',
      'kegiatan_kebersihan',
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
        FormKebersihan::with([
          'tugas_karyawan',
          'kegiatan_kebersihan',
          'user'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kegiatan_kebersihan_id',
      'skor',
      'keterangan'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'kegiatan_kebersihan_id' => 'required|exists:kegiatan_kebersihan,id',
      'skor' => 'required|numeric',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formKebersihanModel = new FormKebersihan;
    $formKebersihanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formKebersihanModel->tanggal_form = $request->input('tanggal_form');
    $formKebersihanModel->jam = $request->input('jam');
    $formKebersihanModel->kegiatan_kebersihan_id = $request->input('kegiatan_kebersihan_id');
    $formKebersihanModel->skor = $request->input('skor');
    $formKebersihanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formKebersihanModel->user_id = $request->user()->id;
    $formKebersihanModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kegiatan_kebersihan_id',
      'skor',
      'keterangan'
    ), [
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'kegiatan_kebersihan_id' => 'nullable|exists:kegiatan_kebersihan,id',
      'skor' => 'nullable|numeric',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formKebersihanModel = FormKebersihan::find($request->input('id'));
    $formKebersihanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formKebersihanModel->tanggal_form = $request->input('tanggal_form');
    $formKebersihanModel->jam = $request->input('jam');
    $formKebersihanModel->kegiatan_kebersihan_id = $request->input('kegiatan_kebersihan_id');
    $formKebersihanModel->skor = $request->input('skor');
    $formKebersihanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formKebersihanModel->user_id = $request->user()->id;
    $formKebersihanModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_goreng,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formKebersihanModel = FormKebersihan::find($request->input('id'));
    $formKebersihanModel->user_id = $request->user()->id;
    $formKebersihanModel->save();
    $formKebersihanModel->delete();
  }
}