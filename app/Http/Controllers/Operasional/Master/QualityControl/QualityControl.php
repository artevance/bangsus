<?php

namespace App\Http\Controllers\Operasional\Master\QualityControl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\QualityControl as QualityControlModel;

class QualityControl extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:quality_control,id'
    ]);

    return ['data' => QualityControlModel::with(['parameter_quality_control', 'parameter_quality_control.opsi_parameter_quality_control'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => QualityControlModel::with(['parameter_quality_control', 'parameter_quality_control.opsi_parameter_quality_control'])
                  ->where('quality_control', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'quality_control' => 'required|max:200'
    ]);

    $model = new QualityControlModel;
    $model->quality_control = strtoupper($request->input('quality_control'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:quality_control,id',
      'quality_control' => 'required|max:200'
    ]);

    $model = QualityControlModel::find($request->input('id'));
    if ($request->has('quality_control')) $model->quality_control = strtoupper($request->input('quality_control'));
    $model->save();
  }
}