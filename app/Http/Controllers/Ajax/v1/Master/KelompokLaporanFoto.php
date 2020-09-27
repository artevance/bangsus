<?php

namespace App\Http\Controllers\Ajax\v1\Master;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\KelompokLaporanFoto as KelompokLaporanFotoModel;

class KelompokLaporanFoto extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(KelompokLaporanFotoModel::where('kelompok_laporan_foto', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! KelompokLaporanFotoModel::find($id)->exists()) return $this->response(404);

    return $this->data(KelompokLaporanFotoModel::find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'kelompok_laporan_foto'
    ), [
      'kelompok_laporan_foto' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new KelompokLaporanFotoModel;
    $model->kelompok_laporan_foto = strtoupper($request->input('kelompok_laporan_foto'));
    $model->save();

    return $this->data(['insert_id' => $model->id])->response(200);
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'kelompok_laporan_foto'
    ), [
      'id' => 'required|exists:kelompok_laporan_foto,id',
      'kelompok_laporan_foto' => 'required|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = KelompokLaporanFotoModel::find($request->input('id'));
    $model->kelompok_laporan_foto = strtoupper($request->input('kelompok_laporan_foto'));
    $model->save();

    return $this->data(['update_id' => $model->id])->response(200);
  }
}