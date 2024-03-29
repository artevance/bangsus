<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Jabatan as JabatanModel;

class Jabatan extends Controller
{
  public function index(Request $request)
  {
    $this->title('Jabatan | BangsusSys')->role($request->user()->role->role_code)->query($request->query());
    return view('hrd.master.jabatan.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:jabatan,id'
    ]);

    return ['data' => JabatanModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => 
        JabatanModel::with([])
          ->where('jabatan', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'jabatan' => 'required|max:200'
    ]);

    $model = new JabatanModel;
    $model->jabatan = strtoupper($request->input('jabatan'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:jabatan,id',
      'jabatan' => 'required|max:200'
    ]);

    $model = JabatanModel::find($request->input('id'));
    if ($request->has('jabatan')) $model->jabatan = strtoupper($request->input('jabatan'));
    $model->save();
  }
}