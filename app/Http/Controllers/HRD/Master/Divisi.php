<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Divisi as DivisiModel;

class Divisi extends Controller
{
  public function index(Request $request)
  {
    $this->title('Divisi | BangsusSys')->role($request->user()->role->role_code);
    return view('hrd.master.divisi.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:divisi,id'
    ]);

    return ['data' => DivisiModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return ['data' => DivisiModel::all()];
  }

  public function post(Request $request)
  {
    $request->validate([
      'divisi' => 'required|max:200'
    ]);

    $model = new DivisiModel;
    $model->divisi = strtoupper($request->input('divisi'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:divisi,id',
      'divisi' => 'required|max:200'
    ]);

    $model = DivisiModel::find($request->input('id'));
    $model->divisi = strtoupper($request->input('divisi'));
    $model->save();
  }
}