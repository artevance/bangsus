<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeFotoKaryawan as TipeFotoKaryawanModel;

class TipeFotoKaryawan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeFotoKaryawanModel::where('tipe_foto_karyawan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeFotoKaryawanModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeFotoKaryawanModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_foto_karyawan'
    ), [
      'tipe_foto_karyawan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeFotoKaryawanModel;
    $model->tipe_foto_karyawan = strtoupper($request->input('tipe_foto_karyawan'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_foto_karyawan'
    ), [
      'id' => 'required|exists:tipe_foto_karyawan,id',
      'tipe_foto_karyawan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeFotoKaryawanModel::find($request->input('id'));
    $model->tipe_foto_karyawan = strtoupper($request->input('tipe_foto_karyawan'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}