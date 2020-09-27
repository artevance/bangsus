<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\DendaFoto as DendaFotoModel;

class DendaFoto extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(DendaFotoModel::with(['kelompok_foto'])->where('denda_foto', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function parent(Request $request, $id)
  {
    return $this
      ->data(
        DendaFotoModel::with(['kelompok_foto'])
          ->where('kelompok_foto_id', $id)
          ->where('denda_foto', 'like', '%' . $request->input('q') . '%') ->get()
        )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! DendaFotoModel::with(['kelompok_foto'])->find($id)->exists()) return $this->response(404);

    return $this->data(DendaFotoModel::with(['kelompok_foto'])->find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'denda_foto',
      'kelompok_foto_id',
      'nominal'
    ), [
      'denda_foto' => 'required|max:200',
      'kelompok_foto_id' => 'required|exists:kelompok_foto,id',
      'nominal' => 'required|numeric'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new DendaFotoModel;
    $model->denda_foto = strtoupper($request->input('denda_foto'));
    $model->kelompok_foto_id = $request->input('kelompok_foto_id');
    $model->nominal = $request->input('nominal');
    $model->master = 0;
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'denda_foto',
      'nominal'
    ), [
      'id' => 'required|exists:denda_foto,id',
      'denda_foto' => 'required|max:200',
      'nominal' => 'required|numeric'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = DendaFotoModel::find($request->input('id'));
    $model->denda_foto = strtoupper($request->input('denda_foto'));
    $model->nominal = $request->input('nominal');
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}