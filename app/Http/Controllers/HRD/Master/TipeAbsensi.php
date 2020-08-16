<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;

class TipeAbsensi extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Absensi | BangsusSys')->role($request->user()->role->role_code)->query($request->query());
    return view('hrd.master.tipe_absensi.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_absensi,id'
    ]);

    return ['data' => TipeAbsensiModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => TipeAbsensiModel::with([])
                  ->where('tipe_absensi', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_absensi' => 'required|max:200'
    ]);

    $model = new TipeAbsensiModel;
    $model->tipe_absensi = strtoupper($request->input('tipe_absensi'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_absensi,id',
      'tipe_absensi' => 'required|max:200'
    ]);

    $model = TipeAbsensiModel::find($request->input('id'));
    if ($request->has('tipe_absensi')) $model->tipe_absensi = strtoupper($request->input('tipe_absensi'));
    $model->save();
  }
}