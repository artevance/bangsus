<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Supplier as SupplierModel;

class Supplier extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(SupplierModel::where('supplier', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! SupplierModel::find($id)->exists()) return $this->response(404);

    return $this->data(SupplierModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'supplier'
    ), [
      'supplier' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new SupplierModel;
    $model->supplier = strtoupper($request->input('supplier'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'supplier'
    ), [
      'id' => 'required|exists:supplier,id',
      'supplier' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = SupplierModel::find($request->input('id'));
    $model->supplier = strtoupper($request->input('supplier'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}