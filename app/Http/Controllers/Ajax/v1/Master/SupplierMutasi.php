<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\SupplierMutasi as SupplierMutasiModel;

class SupplierMutasi extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(SupplierMutasiModel::where('supplier_mutasi', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! SupplierMutasiModel::find($id)->exists()) return $this->response(404);

    return $this->data(SupplierMutasiModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'supplier_mutasi'
    ), [
      'supplier_mutasi' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new SupplierMutasiModel;
    $model->supplier_mutasi = strtoupper($request->input('supplier_mutasi'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'supplier_mutasi'
    ), [
      'id' => 'required|exists:supplier_mutasi,id',
      'supplier_mutasi' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = SupplierMutasiModel::find($request->input('id'));
    $model->supplier_mutasi = strtoupper($request->input('supplier_mutasi'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}