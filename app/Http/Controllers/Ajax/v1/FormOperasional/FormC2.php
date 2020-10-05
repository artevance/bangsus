<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormQualityControl;
use App\Http\Models\FormQualityControlD;
use App\Http\Models\Cabang;
use App\Http\Models\QualityControl;

class FormC2 extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormQualityControl::with([
        'd',
        'tugas_karyawan',
        'quality_control',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormQualityControl::find($id)->exists()) return $this->response(404);

    return $this->data(FormQualityControl::with([
      'd',
      'tugas_karyawan',
      'quality_control',
      'user'
    ])->find($id))->response(200);
  }

  public function dailyBranchType(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d')),
      'quality_control_id' => $request->input('quality_control_id', QualityControl::first()->id)
    ];

    return $this
      ->data(
        FormQualityControl::with([
          'd',
          'tugas_karyawan',
          'quality_control',
          'user'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->byQualityControl($query['quality_control_id'])
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
      'quality_control_id',
      'keterangan',
      'user_id',
      'opsi_parameter_quality_control_id'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'quality_control_id' => 'required|exists:quality_control,id',
      'keterangan' => 'nullable|max:200',
      'opsi_parameter_quality_control_id.*' => 'required|exists:opsi_parameter_quality_control,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formQualityControlModel = new FormQualityControl;
    $formQualityControlModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formQualityControlModel->tanggal_form = $request->input('tanggal_form');
    $formQualityControlModel->jam = $request->input('jam');
    $formQualityControlModel->quality_control_id = $request->input('quality_control_id');
    $formQualityControlModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formQualityControlModel->user_id = $request->user()->id;
    $formQualityControlModel->save();

    foreach ($request->input('opsi_parameter_quality_control_id') as $opsi_parameter_quality_control_id) {
      $formQualityControlDModel = new FormQualityControlD;
      $formQualityControlDModel->form_quality_control_id = $formQualityControlModel->id;
      $formQualityControlDModel->opsi_parameter_quality_control_id = $opsi_parameter_quality_control_id;
      $formQualityControlDModel->user_id = $request->user()->id;
      $formQualityControlDModel->save();
    }
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'quality_control_id',
      'keterangan',
      'user_id',
      'form_quality_control_d_id',
      'opsi_parameter_quality_control_id'
    ), [
      'id' => 'required|exists:form_quality_control,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'quality_control_id' => 'required|exists:quality_control,id',
      'keterangan' => 'nullable|max:200',
      'form_quality_control_d_id.*' => 'required|exists:form_quality_control_d,id',
      'opsi_parameter_quality_control_id.*' => 'required|exists:opsi_parameter_quality_control,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formQualityControlModel = FormQualityControl::find($request->input('id'));
    $formQualityControlModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formQualityControlModel->tanggal_form = $request->input('tanggal_form');
    $formQualityControlModel->jam = $request->input('jam');
    $formQualityControlModel->quality_control_id = $request->input('quality_control_id');
    $formQualityControlModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formQualityControlModel->user_id = $request->user()->id;
    $formQualityControlModel->save();

    foreach ($request->input('opsi_parameter_quality_control_id') as $i => $opsi_parameter_quality_control_id) {
      $formQualityControlDModel = FormQualityControlD::find($request->input('form_quality_control_d_id')[$i]);
      $formQualityControlDModel->form_quality_control_id = $formQualityControlModel->id;
      $formQualityControlDModel->opsi_parameter_quality_control_id = $opsi_parameter_quality_control_id;
      $formQualityControlDModel->user_id = $request->user()->id;
      $formQualityControlDModel->save();
    }
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_quality_control,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formQualityControlDModel = FormQualityControlD::where('form_quality_control_id', $request->input('id'));
    $formQualityControlDModel->delete();

    $formQualityControlModel = FormQualityControl::find($request->input('id'));
    $formQualityControlModel->user_id = $request->user()->id;
    $formQualityControlModel->save();
    $formQualityControlModel->delete();
  }
}