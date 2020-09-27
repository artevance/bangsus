<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeProsesMargarin as TipeProsesMargarinModel;

class TipeProsesMargarin extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeProsesMargarinModel::where('tipe_proses_margarin', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeProsesMargarinModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeProsesMargarinModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_proses_margarin'
    ), [
      'tipe_proses_margarin' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeProsesMargarinModel;
    $model->tipe_proses_margarin = strtoupper($request->input('tipe_proses_margarin'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_proses_margarin'
    ), [
      'id' => 'required|exists:tipe_proses_margarin,id',
      'tipe_proses_margarin' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeProsesMargarinModel::find($request->input('id'));
    $model->tipe_proses_margarin = strtoupper($request->input('tipe_proses_margarin'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}