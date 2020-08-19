<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeAlamat as TipeAlamatModel;

class TipeAlamat extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Alamat | BangsusSys')->role($request->user()->role->role_code)->query($request->query());
    return view('hrd.master.tipe_alamat.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_alamat,id'
    ]);

    return ['data' => TipeAlamatModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        TipeAlamatModel::with([])
          ->where('tipe_alamat', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_alamat' => 'required|max:200'
    ]);

    $model = new TipeAlamatModel;
    $model->tipe_alamat = strtoupper($request->input('tipe_alamat'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_alamat,id',
      'tipe_alamat' => 'required|max:200'
    ]);

    $model = TipeAlamatModel::find($request->input('id'));
    if ($request->has('tipe_alamat')) $model->tipe_alamat = strtoupper($request->input('tipe_alamat'));
    $model->save();
  }
}