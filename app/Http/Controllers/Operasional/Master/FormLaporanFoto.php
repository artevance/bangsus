<?php

namespace App\Http\Controllers\Operasional\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\KelompokLaporanFoto as KelompokLaporanFotoModel;

class FormLaporanFoto extends Controller
{
  public function kelompokLaporanFoto(Request $request)
  {
    $this->title('Kelompok Laporan Foto | BangsusSys')->role($request->user()->role->role_code);
    return view(
      'operasional.master.form_laporan_foto.kelompok_laporan_foto.wrapper',
      $this->passParams()
    );
  }
}