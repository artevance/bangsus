<?php

namespace App\Http\Controllers\Operasional\Master\QualityControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\ParameterQualityControl as ParameterQualityControlModel;

class ParameterQualityControl extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:parameter_quality_control,id'
    ]);

    return ['data' => ParameterQualityControlModel::with(['quality_control', 'opsi_parameter_quality_control'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    $model = ParameterQualityControlModel::with(['quality_control', 'opsi_parameter_quality_control']);

    if ($request->has('quality_control_id')) $model = $model->where('quality_control_id', $request->query('quality_control_id'));

    return [
      'data' => $model->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'quality_control_id' => 'required|exists:quality_control,id',
      'parameter_quality_control' => 'required|max:200'
    ]);

    $model = new ParameterQualityControlModel;
    $model->quality_control_id = $request->input('quality_control_id');
    $model->parameter_quality_control = strtoupper($request->input('parameter_quality_control'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:parameter_quality_control,id',
      'parameter_quality_control' => 'required|max:200'
    ]);

    $model = ParameterQualityControlModel::find($request->input('id'));
    if ($request->has('parameter_quality_control')) $model->parameter_quality_control = strtoupper($request->input('parameter_quality_control'));
    $model->save();
  }
}