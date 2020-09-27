<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\AktivitasMargarin as AktivitasMargarinModel;

class AktivitasMargarin extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(AktivitasMargarinModel::where('aktivitas_margarin', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! AktivitasMargarinModel::find($id)->exists()) return $this->response(404);

    return $this->data(AktivitasMargarinModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'aktivitas_margarin'
    ), [
      'aktivitas_margarin' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new AktivitasMargarinModel;
    $model->aktivitas_margarin = strtoupper($request->input('aktivitas_margarin'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'aktivitas_margarin'
    ), [
      'id' => 'required|exists:aktivitas_margarin,id',
      'aktivitas_margarin' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = AktivitasMargarinModel::find($request->input('id'));
    $model->aktivitas_margarin = strtoupper($request->input('aktivitas_margarin'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}