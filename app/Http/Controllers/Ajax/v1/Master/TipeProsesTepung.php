<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\TipeProsesTepung as TipeProsesTepungModel;

class TipeProsesTepung extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(TipeProsesTepungModel::where('tipe_proses_tepung', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! TipeProsesTepungModel::find($id)->exists()) return $this->response(404);

    return $this->data(TipeProsesTepungModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tipe_proses_tepung'
    ), [
      'tipe_proses_tepung' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new TipeProsesTepungModel;
    $model->tipe_proses_tepung = strtoupper($request->input('tipe_proses_tepung'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tipe_proses_tepung'
    ), [
      'id' => 'required|exists:tipe_proses_tepung,id',
      'tipe_proses_tepung' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = TipeProsesTepungModel::find($request->input('id'));
    $model->tipe_proses_tepung = strtoupper($request->input('tipe_proses_tepung'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}