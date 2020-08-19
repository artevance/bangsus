<?php

namespace App\Http\Controllers\Operasional\Master\GeneralCleaning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\KegiatanGeneralCleaning as KegiatanGeneralCleaningModel;

class KegiatanGeneralCleaning extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kegiatan_general_cleaning,id'
    ]);

    return [
      'data' =>
        KegiatanGeneralCleaningModel::with(['area_general_cleaning'])
          ->find($request->input('id'))
    ];
  }

  public function search(Request $request)
  {
    $model = KegiatanGeneralCleaningModel::with(['area_general_cleaning']);

    if ($request->has('area_general_cleaning_id')) $model = $model->where('area_general_cleaning_id', $request->query('area_general_cleaning_id'));

    return [
      'data' => $model->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'area_general_cleaning_id' => 'required|exists:area_general_cleaning,id',
      'kegiatan_general_cleaning' => 'required|max:200'
    ]);

    $model = new KegiatanGeneralCleaningModel;
    $model->area_general_cleaning_id = $request->input('area_general_cleaning_id');
    $model->kegiatan_general_cleaning = $request->input('kegiatan_general_cleaning');
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kegiatan_general_cleaning,id',
      'kegiatan_general_cleaning' => 'required|max:200'
    ]);

    $model = KegiatanGeneralCleaningModel::find($request->input('id'));
    if ($request->has('kegiatan_general_cleaning')) $model->kegiatan_general_cleaning = $request->input('kegiatan_general_cleaning');
    $model->save();
  }
}