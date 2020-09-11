<?php

namespace App\Http\Controllers\Operasional\Master\FormLaporanFoto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\KelompokLaporanFoto as KelompokLaporanFotoModel;

class KelompokLaporanFoto extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kelompok_laporan_foto,id'
    ]);

    return [
      'data' =>
        KelompokLaporanFotoModel::find($request->input('id'))
    ];
  }

  public function search(Request $request)
  {
    $model = KelompokLaporanFotoModel::where('kelompok_laporan_foto', 'LIKE', '%' . $request->input('q') . '%');

    return [
      'data' => $model->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'kelompok_laporan_foto' => 'required|max:200'
    ]);

    $kelompokLaporanFotoModel = new KelompokLaporanFotoModel;
    $kelompokLaporanFotoModel->kelompok_laporan_foto = strtoupper($request->input('kelompok_laporan_foto'));
    $kelompokLaporanFotoModel->master = 0;
    $kelompokLaporanFotoModel->denda_tidak_kirim = 0;
    $kelompokLaporanFotoModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kelompok_laporan_foto,id',
      'kelompok_laporan_foto' => 'required|max:200'
    ]);

    $kelompokLaporanFotoModel = KelompokLaporanFotoModel::find($request->input('id'));
    if ($kelompokLaporanFotoModel->master == 1) {
      return;
    }
    if ($request->has('kelompok_laporan_foto')) $kelompokLaporanFotoModel->kelompok_laporan_foto = strtoupper($request->input('kelompok_laporan_foto'));
    $kelompokLaporanFotoModel->save();
  }
}