<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\ParameterQualityControl as ParameterQualityControlModel;

class ParameterQualityControl extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(ParameterQualityControlModel::with(['opsi_parameter_quality_control', 'quality_control'])->where('parameter_quality_control', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function parent(Request $request, $id)
  {
    return $this
      ->data(
        ParameterQualityControlModel::with(['opsi_parameter_quality_control', 'quality_control'])
          ->where('quality_control_id', $id)
          ->where('parameter_quality_control', 'like', '%' . $request->input('q') . '%') ->get()
        )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! ParameterQualityControlModel::with(['opsi_parameter_quality_control', 'quality_control'])->find($id)->exists()) return $this->response(404);

    return $this->data(ParameterQualityControlModel::with(['opsi_parameter_quality_control', 'quality_control'])->find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'parameter_quality_control',
      'quality_control_id'
    ), [
      'parameter_quality_control' => 'required|max:200',
      'quality_control_id' => 'required|exists:quality_control,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new ParameterQualityControlModel;
    $model->parameter_quality_control = strtoupper($request->input('parameter_quality_control'));
    $model->quality_control_id = $request->input('quality_control_id');
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'parameter_quality_control'
    ), [
      'id' => 'required|exists:parameter_quality_control,id',
      'parameter_quality_control' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = ParameterQualityControlModel::find($request->input('id'));
    $model->parameter_quality_control = strtoupper($request->input('parameter_quality_control'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}