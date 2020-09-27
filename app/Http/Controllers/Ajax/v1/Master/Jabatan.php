<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Jabatan as JabatanModel;

class Jabatan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(JabatanModel::where('jabatan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! JabatanModel::find($id)->exists()) return $this->response(404);

    return $this->data(JabatanModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'jabatan'
    ), [
      'jabatan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new JabatanModel;
    $model->jabatan = strtoupper($request->input('jabatan'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'jabatan'
    ), [
      'id' => 'required|exists:jabatan,id',
      'jabatan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = JabatanModel::find($request->input('id'));
    $model->jabatan = strtoupper($request->input('jabatan'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}