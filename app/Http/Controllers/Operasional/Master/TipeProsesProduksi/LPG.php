<?php

namespace App\Http\Controllers\Operasional\Master\TipeProsesProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeProsesLPG as TipeProsesLPGModel;

class LPG extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Proses LPG | BangsusSys')->role($request->user()->role->role_code)->nav('lpg');
    return view('operasional.master.tipe_proses_produksi.lpg.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_lpg,id'
    ]);

    return ['data' => TipeProsesLPGModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        TipeProsesLPGModel::with([])
          ->where('tipe_proses_lpg', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_proses_lpg' => 'required|max:200'
    ]);

    $model = new TipeProsesLPGModel;
    $model->tipe_proses_lpg = strtoupper($request->input('tipe_proses_lpg'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_lpg,id',
      'tipe_proses_lpg' => 'required|max:200'
    ]);

    $model = TipeProsesLPGModel::find($request->input('id'));
    if ($request->has('tipe_proses_lpg')) $model->tipe_proses_lpg = strtoupper($request->input('tipe_proses_lpg'));
    $model->save();
  }
}