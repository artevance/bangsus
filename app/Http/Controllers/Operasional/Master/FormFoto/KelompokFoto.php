<?php

namespace App\Http\Controllers\Operasional\Master\FormFoto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\KelompokFoto as KelompokFotoModel;
use App\Http\Models\DendaFoto as DendaFotoModel;
use App\Http\Models\PengaturanKelompokFoto as PengaturanKelompokFotoModel;

class KelompokFoto extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kelompok_foto,id'
    ]);

    return [
      'data' =>
        KelompokFotoModel::with(['denda_foto', 'pengaturan_kelompok_foto', 'pengaturan_kelompok_foto.denda_foto'])
          ->find($request->input('id'))
    ];
  }

  public function search(Request $request)
  {
    $model = KelompokFotoModel::with(['denda_foto', 'pengaturan_kelompok_foto', 'pengaturan_kelompok_foto.denda_foto'])
      ->where('kelompok_foto', 'LIKE', '%' . $request->input('q') . '%');

    if ($request->has('denda_tidak_kirim')) $model = $model->where('denda_tidak_kirim', $request->query('denda_tidak_kirim'));

    return [
      'data' => $model->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'kelompok_foto' => 'required|max:200',
      'denda_tidak_kirim' => 'nullable|accepted',
      'nominal' => 'required_if:denda_tidak_kirim,1,true|numeric',
      'qty_minimum_form' => 'required_if:denda_tidak_kirim,1,true|numeric'
    ]);

    $kelompokFotoModel = new KelompokFotoModel;
    $kelompokFotoModel->kelompok_foto = strtoupper($request->input('kelompok_foto'));
    $kelompokFotoModel->master = 0;
    $kelompokFotoModel->denda_tidak_kirim = $request->boolean('denda_tidak_kirim');
    $kelompokFotoModel->save();

    if ($request->input('denda_tidak_kirim')) {
      $dendaFotoModel = new DendaFotoModel;
      $dendaFotoModel->kelompok_foto_id = $kelompokFotoModel->id;
      $dendaFotoModel->denda_foto = 'TIDAK KIRIM';
      $dendaFotoModel->nominal = $request->input('nominal');
      $dendaFotoModel->master = 1;
      $dendaFotoModel->save();

      $pengaturanKelompokFotoModel = new PengaturanKelompokFotoModel;
      $pengaturanKelompokFotoModel->kelompok_foto_id = $kelompokFotoModel->id;
      $pengaturanKelompokFotoModel->denda_foto_id = $dendaFotoModel->id;
      $pengaturanKelompokFotoModel->qty_minimum_form = $request->input('qty_minimum_form');
      $pengaturanKelompokFotoModel->save();
    }
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:kelompok_foto,id',
      'kelompok_foto' => 'required|max:200',
      'qty_minimum_form' => 'nullable|numeric'
    ]);

    $kelompokFotoModel = KelompokFotoModel::find($request->input('id'));
    if ($kelompokFotoModel->master == 1) {
      return;
    }
    if ($request->has('kelompok_foto')) $kelompokFotoModel->kelompok_foto = strtoupper($request->input('kelompok_foto'));
    $kelompokFotoModel->save();

    if ($kelompokFotoModel->denda_tidak_kirim == 1) {
      $pengaturanKelompokFotoModel = PengaturanKelompokFotoModel::where('kelompok_foto_id', $request->input('id'))->first();
      $pengaturanKelompokFotoModel->qty_minimum_form = $request->input('qty_minimum_form');
      $pengaturanKelompokFotoModel->save();
    }
  }
}