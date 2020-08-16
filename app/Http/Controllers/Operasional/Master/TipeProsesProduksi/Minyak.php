<?php

namespace App\Http\Controllers\Operasional\Master\TipeProsesProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeProsesMinyak as TipeProsesMinyakModel;

class Minyak extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Proses Minyak | BangsusSys')->role($request->user()->role->role_code)->nav('minyak');
    return view('operasional.master.tipe_proses_produksi.minyak.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_minyak,id'
    ]);

    return ['data' => TipeProsesMinyakModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => TipeProsesMinyakModel::with([])
                  ->where('tipe_proses_minyak', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_proses_minyak' => 'required|max:200'
    ]);

    $model = new TipeProsesMinyakModel;
    $model->tipe_proses_minyak = strtoupper($request->input('tipe_proses_minyak'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_minyak,id',
      'tipe_proses_minyak' => 'required|max:200'
    ]);

    $model = TipeProsesMinyakModel::find($request->input('id'));
    if ($request->has('tipe_proses_minyak')) $model->tipe_proses_minyak = strtoupper($request->input('tipe_proses_minyak'));
    $model->save();
  }
}