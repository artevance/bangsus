<?php

namespace App\Http\Controllers\Operasional\Master\AtributKaryawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\ParameterAtributKaryawan as ParameterAtributKaryawanModel;

class ParameterAtributKaryawan extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:parameter_atribut_karyawan,id'
    ]);

    return ['data' => ParameterAtributKaryawanModel::with(['atribut_karyawan'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    $model = ParameterAtributKaryawanModel::with(['atribut_karyawan']);

    if ($request->has('atribut_karyawan_id')) $model = $model->where('atribut_karyawan_id', $request->query('atribut_karyawan_id'));

    return [
      'data' => $model->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'atribut_karyawan_id' => 'required|exists:atribut_karyawan,id',
      'parameter_atribut_karyawan' => 'required|max:200'
    ]);

    $model = new ParameterAtributKaryawanModel;
    $model->atribut_karyawan_id = $request->input('atribut_karyawan_id');
    $model->parameter_atribut_karyawan = strtoupper($request->input('parameter_atribut_karyawan'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:parameter_atribut_karyawan,id',
      'parameter_atribut_karyawan' => 'required|max:200'
    ]);

    $model = ParameterAtributKaryawanModel::find($request->input('id'));
    if ($request->has('parameter_atribut_karyawan')) $model->parameter_atribut_karyawan = strtoupper($request->input('parameter_atribut_karyawan'));
    $model->save();
  }
}