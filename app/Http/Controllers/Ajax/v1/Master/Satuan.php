<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Satuan as SatuanModel;

class Satuan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(SatuanModel::where('satuan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! SatuanModel::find($id)->exists()) return $this->response(404);

    return $this->data(SatuanModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'satuan'
    ), [
      'satuan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new SatuanModel;
    $model->satuan = strtoupper($request->input('satuan'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'satuan'
    ), [
      'id' => 'required|exists:satuan,id',
      'satuan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = SatuanModel::find($request->input('id'));
    $model->satuan = strtoupper($request->input('satuan'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}