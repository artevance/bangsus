<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeStokOpname as TipeStokOpnameModel;

class TipeStokOpname extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeStokOpnameModel::where('tipe_stok_opname', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeStokOpnameModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeStokOpnameModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_stok_opname'
    ), [
      'tipe_stok_opname' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeStokOpnameModel;
    $model->tipe_stok_opname = strtoupper($request->input('tipe_stok_opname'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_stok_opname'
    ), [
      'id' => 'required|exists:tipe_stok_opname,id',
      'tipe_stok_opname' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeStokOpnameModel::find($request->input('id'));
    $model->tipe_stok_opname = strtoupper($request->input('tipe_stok_opname'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}