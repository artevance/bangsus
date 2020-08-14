<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeKontak as TipeKontakModel;

class TipeKontak extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Kontak | BangsusSys')->role($request->user()->role->role_code);
    return view('master.tipe_kontak.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_kontak,id'
    ]);

    return ['data' => TipeKontakModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return ['data' => TipeKontakModel::all()];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_kontak' => 'required|max:200'
    ]);

    $model = new TipeKontakModel;
    $model->tipe_kontak = strtoupper($request->input('tipe_kontak'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_kontak,id',
      'tipe_kontak' => 'required|max:200'
    ]);

    $model = TipeKontakModel::find($request->input('id'));
    $model->tipe_kontak = strtoupper($request->input('tipe_kontak'));
    $model->save();
  }
}