<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\KegiatanKebersihan as KegiatanKebersihanModel;

class KegiatanKebersihan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(KegiatanKebersihanModel::where('kegiatan_kebersihan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! KegiatanKebersihanModel::find($id)->exists()) return $this->response(404);

    return $this->data(KegiatanKebersihanModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'kegiatan_kebersihan'
    ), [
      'kegiatan_kebersihan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new KegiatanKebersihanModel;
    $model->kegiatan_kebersihan = strtoupper($request->input('kegiatan_kebersihan'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'kegiatan_kebersihan'
    ), [
      'id' => 'required|exists:kegiatan_kebersihan,id',
      'kegiatan_kebersihan' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = KegiatanKebersihanModel::find($request->input('id'));
    $model->kegiatan_kebersihan = strtoupper($request->input('kegiatan_kebersihan'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}