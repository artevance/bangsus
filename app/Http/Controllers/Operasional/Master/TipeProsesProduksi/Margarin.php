<?php

namespace App\Http\Controllers\Operasional\Master\TipeProsesProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeProsesMargarin as TipeProsesMargarinModel;

class Margarin extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Proses Margarin | BangsusSys')->role($request->user()->role->role_code)->nav('margarin');
    return view('operasional.master.tipe_proses_produksi.margarin.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_margarin,id'
    ]);

    return ['data' => TipeProsesMargarinModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        TipeProsesMargarinModel::with([])
          ->where('tipe_proses_margarin', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_proses_margarin' => 'required|max:200'
    ]);

    $model = new TipeProsesMargarinModel;
    $model->tipe_proses_margarin = strtoupper($request->input('tipe_proses_margarin'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_margarin,id',
      'tipe_proses_margarin' => 'required|max:200'
    ]);

    $model = TipeProsesMargarinModel::find($request->input('id'));
    if ($request->has('tipe_proses_margarin')) $model->tipe_proses_margarin = strtoupper($request->input('tipe_proses_margarin'));
    $model->save();
  }
}