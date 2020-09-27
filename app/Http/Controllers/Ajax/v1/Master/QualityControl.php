<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\QualityControl as QualityControlModel;

class QualityControl extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(QualityControlModel::with('parameter_quality_control')->where('quality_control', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! QualityControlModel::with('parameter_quality_control')->find($id)->exists()) return $this->response(404);

    return $this->data(QualityControlModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'quality_control'
    ), [
      'quality_control' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new QualityControlModel;
    $model->quality_control = strtoupper($request->input('quality_control'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'quality_control'
    ), [
      'id' => 'required|exists:quality_control,id',
      'quality_control' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = QualityControlModel::find($request->input('id'));
    $model->quality_control = strtoupper($request->input('quality_control'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}