<?php

namespace App\Http\Controllers\Operasional\Master\FormFoto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\KelompokFoto as KelompokFotoModel;

class KelompokFoto extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kelompok_foto,id'
    ]);

    return [
      'data' =>
        KelompokFotoModel::with(['denda_foto'])
          ->find($request->input('id'))
    ];
  }

  public function search(Request $request)
  {
    return [
      'data' => 
        KelompokFotoModel::with(['denda_foto'])
          ->where('kelompok_foto', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'kelompok_foto' => 'required|max:200'
    ]);

    $model = new KelompokFotoModel;
    $model->kelompok_foto = strtoupper($request->input('kelompok_foto'));
    $model->master = 0;
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kelompok_foto,id',
      'kelompok_foto' => 'required|max:200'
    ]);

    $model = KelompokFotoModel::find($request->input('id'));
    if ($model->master == 1) {
      return;
    }
    if ($request->has('kelompok_foto')) $model->kelompok_foto = strtoupper($request->input('kelompok_foto'));
    $model->save();
  }
}