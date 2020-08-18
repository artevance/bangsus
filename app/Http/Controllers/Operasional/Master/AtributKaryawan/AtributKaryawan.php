<?php

namespace App\Http\Controllers\Operasional\Master\AtributKaryawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AtributKaryawan as AtributKaryawanModel;

class AtributKaryawan extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:atribut_karyawan,id'
    ]);

    return ['data' => AtributKaryawanModel::with(['parameter_atribut_karyawan'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => AtributKaryawanModel::with(['parameter_atribut_karyawan'])
                  ->where('atribut_karyawan', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'atribut_karyawan' => 'required|max:200'
    ]);

    $model = new AtributKaryawanModel;
    $model->atribut_karyawan = strtoupper($request->input('atribut_karyawan'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:atribut_karyawan,id',
      'atribut_karyawan' => 'required|max:200'
    ]);

    $model = AtributKaryawanModel::find($request->input('id'));
    if ($request->has('atribut_karyawan')) $model->atribut_karyawan = strtoupper($request->input('atribut_karyawan'));
    $model->save();
  }
}