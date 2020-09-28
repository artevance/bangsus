<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\GolonganDarah as GolonganDarahModel;

class GolonganDarah extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(GolonganDarahModel::where('golongan_darah', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! GolonganDarahModel::find($id)->exists()) return $this->response(404);

    return $this->data(GolonganDarahModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'golongan_darah'
    ), [
      'golongan_darah' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new GolonganDarahModel;
    $model->golongan_darah = strtoupper($request->input('golongan_darah'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'golongan_darah'
    ), [
      'id' => 'required|exists:golongan_darah,id',
      'golongan_darah' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = GolonganDarahModel::find($request->input('id'));
    $model->golongan_darah = strtoupper($request->input('golongan_darah'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}