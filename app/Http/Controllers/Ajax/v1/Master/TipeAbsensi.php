<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;

class TipeAbsensi extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeAbsensiModel::where('tipe_absensi', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeAbsensiModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeAbsensiModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_absensi'
    ), [
      'tipe_absensi' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeAbsensiModel;
    $model->tipe_absensi = strtoupper($request->input('tipe_absensi'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_absensi'
    ), [
      'id' => 'required|exists:tipe_absensi,id',
      'tipe_absensi' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeAbsensiModel::find($request->input('id'));
    $model->tipe_absensi = strtoupper($request->input('tipe_absensi'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}