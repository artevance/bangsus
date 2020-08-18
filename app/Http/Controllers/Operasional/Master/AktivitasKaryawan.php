<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AktivitasKaryawan as AktivitasKaryawanModel;

class AktivitasKaryawan extends Controller
{
  public function index(Request $request)
  {
    $this->title('Aktivitas Karyawan | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.aktivitas_karyawan.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:aktivitas_karyawan,id'
    ]);

    return ['data' => AktivitasKaryawanModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => AktivitasKaryawanModel::with([])
                  ->where('aktivitas_karyawan', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'aktivitas_karyawan' => 'required|max:200'
    ]);

    $model = new AktivitasKaryawanModel;
    $model->aktivitas_karyawan = strtoupper($request->input('aktivitas_karyawan'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:aktivitas_karyawan,id',
      'aktivitas_karyawan' => 'required|max:200'
    ]);

    $model = AktivitasKaryawanModel::find($request->input('id'));
    if ($request->has('aktivitas_karyawan')) $model->aktivitas_karyawan = strtoupper($request->input('aktivitas_karyawan'));
    $model->save();
  }
}