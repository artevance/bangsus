<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeFotoKaryawan as TipeFotoKaryawanModel;

class TipeFotoKaryawan extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Foto Karyawan | BangsusSys')->role($request->user()->role->role_code);
    return view('hrd.master.tipe_foto_karyawan.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_foto_karyawan,id'
    ]);

    return ['data' => TipeFotoKaryawanModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        TipeFotoKaryawanModel::with([])
          ->where('tipe_foto_karyawan', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_foto_karyawan' => 'required|max:200'
    ]);

    $model = new TipeFotoKaryawanModel;
    $model->tipe_foto_karyawan = strtoupper($request->input('tipe_foto_karyawan'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_foto_karyawan,id',
      'tipe_foto_karyawan' => 'required|max:200'
    ]);

    $model = TipeFotoKaryawanModel::find($request->input('id'));
    if ($request->has('tipe_foto_karyawan')) $model->tipe_foto_karyawan = strtoupper($request->input('tipe_foto_karyawan'));
    $model->save();
  }
}