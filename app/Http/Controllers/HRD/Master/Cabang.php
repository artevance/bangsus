<?php

namespace App\Http\Controllers\HRD\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use Illuminate\Validation\Rule;

class Cabang extends Controller
{
  public function index(Request $request)
  {
    $this->title('Cabang | BangsusSys')->role($request->user()->role->role_code)->query($request->query());
    return view('hrd.master.cabang.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:cabang,id'
    ]);

    return ['data' => CabangModel::with(['tipe_cabang'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => CabangModel::with(['tipe_cabang'])
                  ->where('kode_cabang', 'LIKE', '%' . $request->query('q', '') . '%')
                  ->orWhere('cabang', 'LIKE', '%' . $request->query('q', '') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tipe_cabang_id' => 'required|exists:tipe_cabang,id',
      'kode_cabang' => 'required|integer|min:100|max:999|unique:cabang,kode_cabang',
      'cabang' => 'required|max:200'
    ]);

    $model = new CabangModel;
    $model->kode_cabang = $request->input('kode_cabang');
    $model->cabang = strtoupper($request->input('cabang'));
    $model->tipe_cabang_id = $request->input('tipe_cabang_id');
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'tipe_cabang_id' => 'required|exists:tipe_cabang,id',
      'id' => 'required|exists:cabang,id',
      'kode_cabang' => [
        'required', 'integer', 'min:100', 'max:999',
        Rule::unique('cabang')->ignore($request->input('id'))
      ],
      'cabang' => 'required|max:200'
    ]);

    $model = CabangModel::find($request->input('id'));
    $model->kode_cabang = $request->input('kode_cabang');
    $model->cabang = strtoupper($request->input('cabang'));
    $model->tipe_cabang_id = $request->input('tipe_cabang_id'); 
    $model->save();
  }
}