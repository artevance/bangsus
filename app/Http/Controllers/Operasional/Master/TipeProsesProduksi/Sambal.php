<?php

namespace App\Http\Controllers\Operasional\Master\TipeProsesProduksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\TipeProsesSambal as TipeProsesSambalModel;

class Sambal extends Controller
{
  public function index(Request $request)
  {
    $this->title('Tipe Proses Sambal | BangsusSys')->role($request->user()->role->role_code)->nav('sambal');
    return view('operasional.master.tipe_proses_produksi.sambal.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_sambal,id'
    ]);

    return ['data' => TipeProsesSambalModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        TipeProsesSambalModel::with([])
          ->where('tipe_proses_sambal', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_proses_sambal' => 'required|max:200'
    ]);

    $model = new TipeProsesSambalModel;
    $model->tipe_proses_sambal = strtoupper($request->input('tipe_proses_sambal'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tipe_proses_sambal,id',
      'tipe_proses_sambal' => 'required|max:200'
    ]);

    $model = TipeProsesSambalModel::find($request->input('id'));
    if ($request->has('tipe_proses_sambal')) $model->tipe_proses_sambal = strtoupper($request->input('tipe_proses_sambal'));
    $model->save();
  }
}