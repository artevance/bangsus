<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormAtributKaryawan;
use App\Http\Models\FormAtributKaryawanD;
use App\Http\Models\Cabang;

class FormC3 extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormAtributKaryawan::with([
        'd',
        'tugas_karyawan',
        'aktivitas_karyawan',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if (is_null(FormAtributKaryawan::find($id))) return $this->response(404);

    return $this->data(FormAtributKaryawan::with([
      'd',
      'tugas_karyawan',
      'aktivitas_karyawan',
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
        FormAtributKaryawan::with([
          'd',
          'tugas_karyawan',
          'aktivitas_karyawan',
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
      'aktivitas_karyawan_id',
      'keterangan',
      'parameter_atribut_karyawan_id'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'aktivitas_karyawan_id' => 'required|exists:aktivitas_karyawan,id',
      'keterangan' => 'nullable|max:200',
      'parameter_atribut_karyawan_id.*' => 'required|exists:parameter_atribut_karyawan,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formAtributKaryawanModel = new FormAtributKaryawan;
    $formAtributKaryawanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAtributKaryawanModel->tanggal_form = $request->input('tanggal_form');
    $formAtributKaryawanModel->jam = $request->input('jam');
    $formAtributKaryawanModel->aktivitas_karyawan_id = $request->input('aktivitas_karyawan_id');
    $formAtributKaryawanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formAtributKaryawanModel->user_id = $request->user()->id;
    $formAtributKaryawanModel->save();

    foreach ($request->input('parameter_atribut_karyawan_id') as $parameter_atribut_karyawan_id) {
      $formAtributKaryawanDModel = new FormAtributKaryawanD;
      $formAtributKaryawanDModel->form_atribut_karyawan_id = $formAtributKaryawanModel->id;
      $formAtributKaryawanDModel->parameter_atribut_karyawan_id = $parameter_atribut_karyawan_id;
      $formAtributKaryawanDModel->user_id = $request->user()->id;
      $formAtributKaryawanDModel->keterangan = '';
      $formAtributKaryawanDModel->save();
    }
  }

  public function amed(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'aktivitas_karyawan_id',
      'keterangan',
      'form_atribut_karyawan_d_id',
      'parameter_atribut_karyawan_id'
    ), [
      'id' => 'required|exists:form_atribut_karyawan,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'aktivitas_karyawan_id' => 'nullable|exists:aktivitas_karyawan,id',
      'keterangan' => 'nullable|max:200',
      'form_atribut_karyawan_d_id.*' => 'required|exists:form_atribut_karyawan_d,id',
      'parameter_atribut_karyawan_id.*' => 'required|exists:parameter_atribut_karyawan,id'
    ]);

    $formAtributKaryawanModel = FormAtributKaryawan::find($request->input('id'));
    $formAtributKaryawanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAtributKaryawanModel->tanggal_form = $request->input('tanggal_form');
    $formAtributKaryawanModel->jam = $request->input('jam');
    $formAtributKaryawanModel->aktivitas_karyawan_id = $request->input('aktivitas_karyawan_id');
    $formAtributKaryawanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formAtributKaryawanModel->user_id = $request->user()->id;
    $formAtributKaryawanModel->save();

    foreach ($request->input('parameter_atribut_karyawan_id') as $i => $parameter_atribut_karyawan_id) {
      $formAtributKaryawanDModel = FormAtributKaryawanD::find($request->input('form_atribut_karyawan_d_id')[$i]);
      $formAtributKaryawanDModel->form_atribut_karyawan_id = $formAtributKaryawanModel->id;
      $formAtributKaryawanDModel->parameter_atribut_karyawan_id = $parameter_atribut_karyawan_id;
      $formAtributKaryawanDModel->keterangan = '';
      $formAtributKaryawanDModel->save();
    }
  }

  public function delete(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_atribut_karyawan,id'
    ]);

    $formAtributKaryawanDModel = FormAtributKaryawanD::where('form_atribut_karyawan_id', $request->input('id'));
    $formAtributKaryawanDModel->delete();

    $formAtributKaryawanModel = FormAtributKaryawan::find($request->input('id'));
    $formAtributKaryawanModel->user_id = $request->user()->id;
    $formAtributKaryawanModel->save();
    $formAtributKaryawanModel->delete();
  }
}