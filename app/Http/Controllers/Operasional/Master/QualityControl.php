<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\QualityControl as QualityControlModel;
use App\Http\Models\ParameterQualityControl as ParameterQualityControlModel;

class QualityControl extends Controller
{
  public function qualityControl(Request $request)
  {
    $this->title('Quality Control | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.quality_control.quality_control.wrapper', $this->passParams());
  }

  public function parameterQualityControl(QualityControlModel $qualityControl, Request $request)
  {
    $this->title('Quality Control | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.quality_control.parameter_quality_control.wrapper', $this->passParams());
  }

  public function opsiParameterQualityControl(QualityControlModel $qualityControl, ParameterQualityControlModel $parameterQualityControl, Request $request)
  {
    $this->title('Quality Control | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.quality_control.opsi_parameter_quality_control.wrapper', $this->passParams());
  }
}