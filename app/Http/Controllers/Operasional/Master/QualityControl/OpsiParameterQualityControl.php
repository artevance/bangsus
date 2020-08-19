<?php

namespace App\Http\Controllers\Operasional\Master\QualityControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\OpsiParameterQualityControl as OpsiParameterQualityControlModel;

class OpsiParameterQualityControl extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:opsi_parameter_quality_control,id'
    ]);

    return [
      'data' =>
        OpsiParameterQualityControlModel::with([
            'parameter_quality_control',
            'parameter_quality_control.quality_control'
          ])
          ->find($request->input('id'))
    ];
  }

  public function search(Request $request)
  {
    $model = OpsiParameterQualityControlModel::with([
      'parameter_quality_control', 
      'parameter_quality_control.quality_control'
    ]);

    if ($request->has('parameter_quality_control_id')) $model = $model->where('parameter_quality_control_id', $request->query('parameter_quality_control_id'));

    return [
      'data' => $model->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'parameter_quality_control_id' => 'required|exists:parameter_quality_control,id',
      'opsi_parameter_quality_control' => 'required|max:200'
    ]);

    $model = new OpsiParameterQualityControlModel;
    $model->parameter_quality_control_id = $request->input('parameter_quality_control_id');
    $model->opsi_parameter_quality_control = strtoupper($request->input('opsi_parameter_quality_control'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:opsi_parameter_quality_control,id',
      'opsi_parameter_quality_control' => 'required|max:200'
    ]);

    $model = OpsiParameterQualityControlModel::find($request->input('id'));
    if ($request->has('opsi_parameter_quality_control')) $model->opsi_parameter_quality_control = strtoupper($request->input('opsi_parameter_quality_control'));
    $model->save();
  }
}