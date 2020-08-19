<?php

namespace App\Http\Controllers\Operasional\Master\TipeProsesProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeProsesTepung as TipeProsesTepungModel;

class Tepung extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Proses Tepung | BangsusSys')->role($request->user()->role->role_code)->nav('tepung');
    return view('operasional.master.tipe_proses_produksi.tepung.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_tepung,id'
    ]);

    return ['data' => TipeProsesTepungModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        TipeProsesTepungModel::with([])
          ->where('tipe_proses_tepung', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_proses_tepung' => 'required|max:200'
    ]);

    $model = new TipeProsesTepungModel;
    $model->tipe_proses_tepung = strtoupper($request->input('tipe_proses_tepung'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_tepung,id',
      'tipe_proses_tepung' => 'required|max:200'
    ]);

    $model = TipeProsesTepungModel::find($request->input('id'));
    if ($request->has('tipe_proses_tepung')) $model->tipe_proses_tepung = strtoupper($request->input('tipe_proses_tepung'));
    $model->save();
  }
}