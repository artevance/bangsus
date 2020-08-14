<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeCabang as TipeCabangModel;

class TipeCabang extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Cabang | BangsusSys')->role($request->user()->role->role_code);
    return view('master.tipe_cabang.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_cabang,id'
    ]);

    return ['data' => TipeCabangModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return ['data' => TipeCabangModel::all()];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_cabang' => 'required|max:200'
    ]);

    $model = new TipeCabangModel;
    $model->tipe_cabang = strtoupper($request->input('tipe_cabang'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_cabang,id',
      'tipe_cabang' => 'required|max:200'
    ]);

    $model = TipeCabangModel::find($request->input('id'));
    $model->tipe_cabang = strtoupper($request->input('tipe_cabang'));
    $model->save();
  }
}