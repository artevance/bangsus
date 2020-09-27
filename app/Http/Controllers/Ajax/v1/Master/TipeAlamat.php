<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeAlamat as TipeAlamatModel;

class TipeAlamat extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeAlamatModel::where('tipe_alamat', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeAlamatModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeAlamatModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_alamat'
    ), [
      'tipe_alamat' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeAlamatModel;
    $model->tipe_alamat = strtoupper($request->input('tipe_alamat'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_alamat'
    ), [
      'id' => 'required|exists:tipe_alamat,id',
      'tipe_alamat' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeAlamatModel::find($request->input('id'));
    $model->tipe_alamat = strtoupper($request->input('tipe_alamat'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}