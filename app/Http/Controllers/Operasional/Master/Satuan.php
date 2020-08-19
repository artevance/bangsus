<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Satuan as SatuanModel;

class Satuan extends Controller
{
  public function index(Request $request)
  {
    $this->title('Satuan | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.satuan.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:satuan,id'
    ]);

    return ['data' => SatuanModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => 
        SatuanModel::with([])
          ->where('satuan', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'satuan' => 'required|max:200'
    ]);

    $model = new SatuanModel;
    $model->satuan = strtoupper($request->input('satuan'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:satuan,id',
      'satuan' => 'required|max:200'
    ]);

    $model = SatuanModel::find($request->input('id'));
    if ($request->has('satuan')) $model->satuan = strtoupper($request->input('satuan'));
    $model->save();
  }
}