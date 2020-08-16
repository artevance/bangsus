<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Supplier as SupplierModel;

class Supplier extends Controller
{
  public function index(Request $request)
  {
    $this->title('Supplier | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.supplier.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:supplier,id'
    ]);

    return ['data' => SupplierModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => SupplierModel::with([])
                  ->where('supplier', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'supplier' => 'required|max:200'
    ]);

    $model = new SupplierModel;
    $model->supplier = strtoupper($request->input('supplier'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:supplier,id',
      'supplier' => 'required|max:200'
    ]);

    $model = SupplierModel::find($request->input('id'));
    if ($request->has('supplier')) $model->supplier = strtoupper($request->input('supplier'));
    $model->save();
  }
}