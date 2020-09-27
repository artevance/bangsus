<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\AktivitasKaryawan as AktivitasKaryawanModel;

class AktivitasKaryawan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(AktivitasKaryawanModel::where('aktivitas_karyawan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! AktivitasKaryawanModel::find($id)->exists()) return $this->response(404);

    return $this->data(AktivitasKaryawanModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'aktivitas_karyawan'
    ), [
      'aktivitas_karyawan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new AktivitasKaryawanModel;
    $model->aktivitas_karyawan = strtoupper($request->input('aktivitas_karyawan'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'aktivitas_karyawan'
    ), [
      'id' => 'required|exists:aktivitas_karyawan,id',
      'aktivitas_karyawan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = AktivitasKaryawanModel::find($request->input('id'));
    $model->aktivitas_karyawan = strtoupper($request->input('aktivitas_karyawan'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}