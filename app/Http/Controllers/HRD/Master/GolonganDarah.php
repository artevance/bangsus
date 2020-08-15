<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\GolonganDarah as GolonganDarahModel;

class GolonganDarah extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:golongan_darah,id'
    ]);

    return ['data' => GolonganDarahModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return ['data' => GolonganDarahModel::all()];
  }

  public function post(Request $request)
  {
    $request->validate([
      'golongan_darah' => 'required|max:200'
    ]);

    $model = new GolonganDarahModel;
    $model->golongan_darah = strtoupper($request->input('golongan_darah'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:golongan_darah,id',
      'golongan_darah' => 'required|max:200'
    ]);

    $model = GolonganDarahModel::find($request->input('id'));
    if ($request->has('golongan_darah')) $model->golongan_darah = strtoupper($request->input('golongan_darah'));
    $model->save();
  }
}