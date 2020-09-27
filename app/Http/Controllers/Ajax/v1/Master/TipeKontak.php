<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeKontak as TipeKontakModel;

class TipeKontak extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeKontakModel::where('tipe_kontak', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeKontakModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeKontakModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_kontak'
    ), [
      'tipe_kontak' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeKontakModel;
    $model->tipe_kontak = strtoupper($request->input('tipe_kontak'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_kontak'
    ), [
      'id' => 'required|exists:tipe_kontak,id',
      'tipe_kontak' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeKontakModel::find($request->input('id'));
    $model->tipe_kontak = strtoupper($request->input('tipe_kontak'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}