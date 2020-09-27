<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\AreaGeneralCleaning as AreaGeneralCleaningModel;

class AreaGeneralCleaning extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(AreaGeneralCleaningModel::with('kegiatan_general_cleaning')->where('area_general_cleaning', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! AreaGeneralCleaningModel::with('kegiatan_general_cleaning')->find($id)->exists()) return $this->response(404);

    return $this->data(AreaGeneralCleaningModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'area_general_cleaning'
    ), [
      'area_general_cleaning' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new AreaGeneralCleaningModel;
    $model->area_general_cleaning = $request->input('area_general_cleaning');
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'area_general_cleaning'
    ), [
      'id' => 'required|exists:area_general_cleaning,id',
      'area_general_cleaning' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = AreaGeneralCleaningModel::find($request->input('id'));
    $model->area_general_cleaning = $request->input('area_general_cleaning');
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}