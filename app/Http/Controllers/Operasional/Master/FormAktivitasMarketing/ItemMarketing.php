<?php

namespace App\Http\Controllers\Operasional\Master\FormAktivitasMarketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\ItemMarketing as ItemMarketingModel;

class ItemMarketing extends Controller
{
  public function index(Request $request)
  {
    $this->title('Form Aktivitas Marketing | BangsusSys')->role($request->user()->role->role_code)->nav('itemMarketing');
    return view('operasional.master.form_aktivitas_marketing.item_marketing.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:item_marketing,id'
    ]);

    return ['data' => ItemMarketingModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        ItemMarketingModel::with([])
          ->where('item_marketing', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'item_marketing' => 'required|max:200'
    ]);

    $model = new ItemMarketingModel;
    $model->item_marketing = strtoupper($request->input('item_marketing'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:item_marketing,id',
      'item_marketing' => 'required|max:200'
    ]);

    $model = ItemMarketingModel::find($request->input('id'));
    if ($request->has('item_marketing')) $model->item_marketing = strtoupper($request->input('item_marketing'));
    $model->save();
  }
}