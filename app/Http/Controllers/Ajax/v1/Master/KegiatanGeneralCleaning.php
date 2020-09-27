<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\KegiatanGeneralCleaning as KegiatanGeneralCleaningModel;

class KegiatanGeneralCleaning extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(KegiatanGeneralCleaningModel::with(['area_general_cleaning'])->where('kegiatan_general_cleaning', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function parent(Request $request, $id)
  {
    return $this
      ->data(
        KegiatanGeneralCleaningModel::with(['area_general_cleaning'])
          ->where('area_general_cleaning_id', $id)
          ->where('kegiatan_general_cleaning', 'like', '%' . $request->input('q') . '%') ->get()
        )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! KegiatanGeneralCleaningModel::with(['area_general_cleaning'])->find($id)->exists()) return $this->response(404);

    return $this->data(KegiatanGeneralCleaningModel::with(['area_general_cleaning'])->find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'kegiatan_general_cleaning',
      'area_general_cleaning_id'
    ), [
      'kegiatan_general_cleaning' => 'required|max:200',
      'area_general_cleaning_id' => 'required|exists:area_general_cleaning,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new KegiatanGeneralCleaningModel;
    $model->kegiatan_general_cleaning = $request->input('kegiatan_general_cleaning');
    $model->area_general_cleaning_id = $request->input('area_general_cleaning_id');
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'kegiatan_general_cleaning'
    ), [
      'id' => 'required|exists:kegiatan_general_cleaning,id',
      'kegiatan_general_cleaning' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = KegiatanGeneralCleaningModel::find($request->input('id'));
    $model->kegiatan_general_cleaning = $request->input('kegiatan_general_cleaning');
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}