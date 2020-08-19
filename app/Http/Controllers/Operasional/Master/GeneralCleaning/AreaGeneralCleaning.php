<?php

namespace App\Http\Controllers\Operasional\Master\GeneralCleaning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AreaGeneralCleaning as AreaGeneralCleaningModel;

class AreaGeneralCleaning extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:area_general_cleaning,id'
    ]);

    return [
      'data' =>
        AreaGeneralCleaningModel::with(['kegiatan_general_cleaning'])
          ->find($request->input('id'))
    ];
  }

  public function search(Request $request)
  {
    return [
      'data' => AreaGeneralCleaningModel::with(['kegiatan_general_cleaning'])
                  ->where('area_general_cleaning', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'area_general_cleaning' => 'required|max:200'
    ]);

    $model = new AreaGeneralCleaningModel;
    $model->area_general_cleaning = strtoupper($request->input('area_general_cleaning'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:area_general_cleaning,id',
      'area_general_cleaning' => 'required|max:200'
    ]);

    $model = AreaGeneralCleaningModel::find($request->input('id'));
    if ($request->has('area_general_cleaning')) $model->area_general_cleaning = strtoupper($request->input('area_general_cleaning'));
    $model->save();
  }
}