<?php

namespace App\Http\Controllers\Operasional\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\FormDendaFoto as FormDendaFotoModel;

class FormDendaFoto extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'cabang_id' => $request->query('cabang_id', 1),
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d'))
    ];

    $this->title('Form Denda Foto | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.laporan.form_denda_foto.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'results' =>
          FormDendaFotoModel::with([
            'd',
            'd.denda_foto',
            'form_foto',
            'form_foto.kelompok_foto'
          ])
          ->whereHas('form_foto', function ($q) use ($request) {
            $q->where('tanggal_form', $request->query('tanggal_form'));
          })
          ->get()
      ]
    ));
  }
}