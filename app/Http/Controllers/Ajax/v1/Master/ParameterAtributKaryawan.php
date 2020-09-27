<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\ParameterAtributKaryawan as ParameterAtributKaryawanModel;

class ParameterAtributKaryawan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(ParameterAtributKaryawanModel::with(['atribut_karyawan'])->where('parameter_atribut_karyawan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function parent(Request $request, $id)
  {
    return $this
      ->data(
        ParameterAtributKaryawanModel::with(['atribut_karyawan'])
          ->where('atribut_karyawan_id', $id)
          ->where('parameter_atribut_karyawan', 'like', '%' . $request->input('q') . '%') ->get()
        )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! ParameterAtributKaryawanModel::with(['atribut_karyawan'])->find($id)->exists()) return $this->response(404);

    return $this->data(ParameterAtributKaryawanModel::with(['atribut_karyawan'])->find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'parameter_atribut_karyawan',
      'atribut_karyawan_id'
    ), [
      'parameter_atribut_karyawan' => 'required|max:200',
      'atribut_karyawan_id' => 'required|exists:atribut_karyawan,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new ParameterAtributKaryawanModel;
    $model->parameter_atribut_karyawan = strtoupper($request->input('parameter_atribut_karyawan'));
    $model->atribut_karyawan_id = $request->input('atribut_karyawan_id');
    $model->pelanggaran = $request->boolean('pelanggaran');
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'parameter_atribut_karyawan'
    ), [
      'id' => 'required|exists:parameter_atribut_karyawan,id',
      'parameter_atribut_karyawan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = ParameterAtributKaryawanModel::find($request->input('id'));
    $model->parameter_atribut_karyawan = strtoupper($request->input('parameter_atribut_karyawan'));
    $model->pelanggaran = $request->boolean('pelanggaran');
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}