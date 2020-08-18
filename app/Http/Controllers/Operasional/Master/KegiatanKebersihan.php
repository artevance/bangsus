<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\KegiatanKebersihan as KegiatanKebersihanModel;

class KegiatanKebersihan extends Controller
{
  public function index(Request $request)
  {
    $this->title('Kegiatan Kebersihan | BangsusSys')->role($request->user()->role->role_code);
    return view('operasional.master.kegiatan_kebersihan.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kegiatan_kebersihan,id'
    ]);

    return ['data' => KegiatanKebersihanModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => KegiatanKebersihanModel::with([])
                  ->where('kegiatan_kebersihan', 'LIKE', '%' . $request->input('q') . '%')
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'kegiatan_kebersihan' => 'required|max:200'
    ]);

    $model = new KegiatanKebersihanModel;
    $model->kegiatan_kebersihan = strtoupper($request->input('kegiatan_kebersihan'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kegiatan_kebersihan,id',
      'kegiatan_kebersihan' => 'required|max:200'
    ]);

    $model = KegiatanKebersihanModel::find($request->input('id'));
    if ($request->has('kegiatan_kebersihan')) $model->kegiatan_kebersihan = strtoupper($request->input('kegiatan_kebersihan'));
    $model->save();
  }
}