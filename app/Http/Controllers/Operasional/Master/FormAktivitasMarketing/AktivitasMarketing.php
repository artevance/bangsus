<?php

namespace App\Http\Controllers\Operasional\Master\FormAktivitasMarketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\AktivitasMarketing as AktivitasMarketingModel;

class AktivitasMarketing extends Controller
{
  public function index(Request $request)
  {
    $this->title('Form Aktivitas Marketing | BangsusSys')->role($request->user()->role->role_code)->nav('aktivitasMarketing');
    return view('operasional.master.form_aktivitas_marketing.aktivitas_marketing.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:aktivitas_marketing,id'
    ]);

    return ['data' => AktivitasMarketingModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        AktivitasMarketingModel::with([])
          ->where('aktivitas_marketing', 'LIKE', '%' . $request->input('q') . '%')
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'aktivitas_marketing' => 'required|max:200'
    ]);

    $model = new AktivitasMarketingModel;
    $model->aktivitas_marketing = strtoupper($request->input('aktivitas_marketing'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:aktivitas_marketing,id',
      'aktivitas_marketing' => 'required|max:200'
    ]);

    $model = AktivitasMarketingModel::find($request->input('id'));
    if ($request->has('aktivitas_marketing')) $model->aktivitas_marketing = strtoupper($request->input('aktivitas_marketing'));
    $model->save();
  }
}