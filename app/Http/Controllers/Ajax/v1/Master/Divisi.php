<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Divisi as DivisiModel;

class Divisi extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(DivisiModel::where('divisi', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! DivisiModel::find($id)->exists()) return $this->response(404);

    return $this->data(DivisiModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'divisi'
    ), [
      'divisi' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new DivisiModel;
    $model->divisi = strtoupper($request->input('divisi'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'divisi'
    ), [
      'id' => 'required|exists:divisi,id',
      'divisi' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = DivisiModel::find($request->input('id'));
    $model->divisi = strtoupper($request->input('divisi'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}