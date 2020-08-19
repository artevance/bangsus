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

  public function dendaFoto(KelompokFotoModel $kelompokFoto, Request $request)
  {
    $this->title('Denda Foto | BangsusSys')->role($request->user()->role->role_code);
    return view(
      'operasional.master.form_foto.denda_foto.wrapper',
      $this->passParams([
        'kelompokFoto' => $kelompokFoto,
        'kelompokFotos' => KelompokFotoModel::all()
      ])
    );
  }
}