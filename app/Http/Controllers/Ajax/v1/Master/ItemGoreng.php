<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\ItemGoreng as ItemGorengModel;

class ItemGoreng extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(ItemGorengModel::where('item_goreng', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! ItemGorengModel::find($id)->exists()) return $this->response(404);

    return $this->data(ItemGorengModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'item_goreng'
    ), [
      'item_goreng' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new ItemGorengModel;
    $model->item_goreng = strtoupper($request->input('item_goreng'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'item_goreng'
    ), [
      'id' => 'required|exists:item_goreng,id',
      'item_goreng' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = ItemGorengModel::find($request->input('id'));
    $model->item_goreng = strtoupper($request->input('item_goreng'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}