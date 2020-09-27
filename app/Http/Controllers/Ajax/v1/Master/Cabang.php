<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Cabang as CabangModel;

class Cabang extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(CabangModel::with(['tipe_cabang'])
        ->where('kode_cabang', 'like', '%' . $request->input('q') . '%')
        ->orWhere('cabang', 'like', '%' . $request->input('q') . '%')
        ->orWhereHas('tipe_cabang', function ($q) use ($request) {
          $q->where('tipe_cabang', '%' . $request->input('q') . '%');
        })
        ->get()
      )
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! CabangModel::with(['tipe_cabang'])->find($id)->exists()) return $this->response(404);

    return $this->data(CabangModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'kode_cabang',
      'cabang',
      'tipe_cabang_id'
    ), [
      'kode_cabang' => 'required|unique:cabang,kode_cabang',
      'cabang' => 'required|max:200',
      'tipe_cabang_id' => 'required|exists:tipe_cabang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new CabangModel;
    $model->kode_cabang = $request->input('kode_cabang');
    $model->cabang = strtoupper($request->input('cabang'));
    $model->tipe_cabang_id = $request->input('tipe_cabang_id');
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'kode_cabang',
      'cabang'
    ), [
      'id' => 'required|exists:cabang,id',
      'kode_cabang' => [
        'required',
        Rule::unique('cabang', 'kode_cabang')->ignore($request->input('id'))
      ],
      'cabang' => 'required|max:200',
      'tipe_cabang_id' => 'required|exists:tipe_cabang,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = CabangModel::find($request->input('id'));
    $model->kode_cabang = $request->input('kode_cabang');
    $model->cabang = strtoupper($request->input('cabang'));
    $model->tipe_cabang_id = $request->input('tipe_cabang_id');
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}