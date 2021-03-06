<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\AtributKaryawan as AtributKaryawanModel;

class AtributKaryawan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(AtributKaryawanModel::with('parameter_atribut_karyawan')->where('atribut_karyawan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! AtributKaryawanModel::with('parameter_atribut_karyawan')->find($id)->exists()) return $this->response(404);

    return $this->data(AtributKaryawanModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'atribut_karyawan'
    ), [
      'atribut_karyawan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new AtributKaryawanModel;
    $model->atribut_karyawan = strtoupper($request->input('atribut_karyawan'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'atribut_karyawan'
    ), [
      'id' => 'required|exists:atribut_karyawan,id',
      'atribut_karyawan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = AtributKaryawanModel::find($request->input('id'));
    $model->atribut_karyawan = strtoupper($request->input('atribut_karyawan'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}