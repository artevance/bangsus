<?php

namespace App\Http\Controllers\Operasional\Master\FormFoto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\DendaFoto as DendaFotoModel;

class DendaFoto extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:denda_foto,id'
    ]);

    return ['data' => DendaFotoModel::with(['kelompok_foto'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    $model = DendaFotoModel::with(['kelompok_foto']);

    if ($request->has('kelompok_foto_id')) $model = $model->where('kelompok_foto_id', $request->query('kelompok_foto_id'));

    return [
      'data' => $model->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'kelompok_foto_id' => 'required|exists:kelompok_foto,id',
      'denda_foto' => 'required|max:200',
      'nominal' => 'required|numeric'
    ]);

    $model = new DendaFotoModel;
    $model->kelompok_foto_id = $request->input('kelompok_foto_id');
    $model->denda_foto = strtoupper($request->input('denda_foto'));
    $model->nominal = strtoupper($request->input('nominal'));
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:denda_foto,id',
      'denda_foto' => 'nullable|max:200',
      'nominal' => 'nullable|numeric'
    ]);

    $model = DendaFotoModel::find($request->input('id'));
    if ($request->has('denda_foto')) $model->denda_foto = strtoupper($request->input('denda_foto'));
    if ($request->has('nominal')) $model->nominal = strtoupper($request->input('nominal'));
    $model->save();
  }
}