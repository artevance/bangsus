<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\KelompokFoto as KelompokFotoModel;
use App\Http\Models\DendaFoto as DendaFotoModel;

class FormFoto extends Controller
{
  public function kelompokFoto(Request $request)
  {
    $this->title('Kelompok Foto | BangsusSys')->role($request->user()->role->role_code);
    return view(
      'operasional.master.form_foto.kelompok_foto.wrapper',
      $this->passParams()
    );
  }

  public function dendaFoto(Request $request)
  {
    $request->validate([
      'kelompok_foto_id' => 'required|exists:kelompok_foto,id'
    ]);

    $kelompokFoto = KelompokFotoModel::find($request->query('kelompok_foto_id'));

    $this->title('Denda Foto | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query([
        'kelompok_foto_id' => $request->query('kelompok_foto_id', 1),
        'q' => $request->query('q', '')
      ]);
    return view(
      'operasional.master.form_foto.denda_foto.wrapper',
      $this->passParams([
        'kelompokFoto' => $kelompokFoto,
        'kelompokFotos' => KelompokFotoModel::all()
      ])
    );
  }
}