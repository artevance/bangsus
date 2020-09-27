<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeCabang as TipeCabangModel;

class TipeCabang extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeCabangModel::where('tipe_cabang', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeCabangModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeCabangModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_cabang'
    ), [
      'tipe_cabang' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeCabangModel;
    $model->tipe_cabang = strtoupper($request->input('tipe_cabang'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_cabang'
    ), [
      'id' => 'required|exists:tipe_cabang,id',
      'tipe_cabang' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeCabangModel::find($request->input('id'));
    $model->tipe_cabang = strtoupper($request->input('tipe_cabang'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }
}