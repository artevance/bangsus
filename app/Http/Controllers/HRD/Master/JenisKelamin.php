<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\JenisKelamin as JenisKelaminModel;

class JenisKelamin extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:jenis_kelamin,id'
    ]);

    return ['data' => JenisKelaminModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return ['data' => JenisKelaminModel::all()];
  }

  public function post(Request $request)
  {
    $request->validate([
      'jenis_kelamin' => 'required|max:200'
    ]);

    $model = new JenisKelaminModel;
    $model->jenis_kelamin = strtoupper($request->input('jenis_kelamin'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:jenis_kelamin,id',
      'jenis_kelamin' => 'required|max:200'
    ]);

    $model = JenisKelaminModel::find($request->input('id'));
    if ($request->has('jenis_kelamin')) $model->jenis_kelamin = strtoupper($request->input('jenis_kelamin'));
    $model->save();
  }
}