<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\AktivitasMarketing as AktivitasMarketingModel;

class AktivitasMarketing extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(AktivitasMarketingModel::where('aktivitas_marketing', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! AktivitasMarketingModel::find($id)->exists()) return $this->response(404);

    return $this->data(AktivitasMarketingModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'aktivitas_marketing'
    ), [
      'aktivitas_marketing' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new AktivitasMarketingModel;
    $model->aktivitas_marketing = strtoupper($request->input('aktivitas_marketing'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'aktivitas_marketing'
    ), [
      'id' => 'required|exists:aktivitas_marketing,id',
      'aktivitas_marketing' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = AktivitasMarketingModel::find($request->input('id'));
    $model->aktivitas_marketing = strtoupper($request->input('aktivitas_marketing'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}