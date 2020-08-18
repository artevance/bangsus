<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\ItemGoreng as ItemGorengModel;

class ItemGoreng extends Controller
{
  public function index(Request $request)
  {
    $this->title('Item Goreng | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.item_goreng.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:item_goreng,id'
    ]);

    return ['data' => ItemGorengModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => ItemGorengModel::with([])
                  ->where('item_goreng', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'item_goreng' => 'required|max:200'
    ]);

    $model = new ItemGorengModel;
    $model->item_goreng = strtoupper($request->input('item_goreng'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:item_goreng,id',
      'item_goreng' => 'required|max:200'
    ]);

    $model = ItemGorengModel::find($request->input('id'));
    if ($request->has('item_goreng')) $model->item_goreng = strtoupper($request->input('item_goreng'));
    $model->save();
  }
}