<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\JenisKelamin as JenisKelaminModel;

class JenisKelamin extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(JenisKelaminModel::where('jenis_kelamin', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! JenisKelaminModel::find($id)->exists()) return $this->response(404);

    return $this->data(JenisKelaminModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'jenis_kelamin'
    ), [
      'jenis_kelamin' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new JenisKelaminModel;
    $model->jenis_kelamin = strtoupper($request->input('jenis_kelamin'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'jenis_kelamin'
    ), [
      'id' => 'required|exists:jenis_kelamin,id',
      'jenis_kelamin' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = JenisKelaminModel::find($request->input('id'));
    $model->jenis_kelamin = strtoupper($request->input('jenis_kelamin'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}