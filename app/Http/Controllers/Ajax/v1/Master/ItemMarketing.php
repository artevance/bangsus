<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\ItemMarketing as ItemMarketingModel;

class ItemMarketing extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(ItemMarketingModel::where('item_marketing', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! ItemMarketingModel::find($id)->exists()) return $this->response(404);

    return $this->data(ItemMarketingModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'item_marketing'
    ), [
      'item_marketing' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new ItemMarketingModel;
    $model->item_marketing = strtoupper($request->input('item_marketing'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'item_marketing'
    ), [
      'id' => 'required|exists:item_marketing,id',
      'item_marketing' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = ItemMarketingModel::find($request->input('id'));
    $model->item_marketing = strtoupper($request->input('item_marketing'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}