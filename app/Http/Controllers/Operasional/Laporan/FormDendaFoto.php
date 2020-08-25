<?php

namespace App\Http\Controllers\Operasional\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\KelompokFoto as KelompokFotoModel;
use App\Http\Models\FormDendaFoto as FormDendaFotoModel;

class FormDendaFoto extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'cabang_id' => $request->query('cabang_id', 1),
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d')),
      'tipe_laporan' => $request->query('tipe_laporan')
    ];

    $this->title('Form Denda Foto | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.laporan.form_denda_foto.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'formDendaFotoModels' =>
          FormDendaFotoModel::with([
            'd',
            'd.denda_foto',
            'form_foto',
            'form_foto.kelompok_foto'
          ])
          ->whereHas('form_foto', function ($q) use ($request) {
            $q->where('tanggal_form', $request->query('tanggal_form'))
              ->where(function ($query) use ($request) {
                $query->whereHas('tugas_karyawan', function ($q) use ($request) {
                  $q->where('cabang_id', '=', $request->input('cabang_id'));
                });
                $query->orWhereHas('cabang', function ($q) use ($request) {
                  $q->where('cabang_id', $request->input('cabang_id'))
                    ->orWhere('cabang_id', null);
                });
              });
          })
          ->get(),
        'kelompokFotoModels' =>
          KelompokFotoModel::with([
            'denda_foto',
              'denda_foto.form_denda_foto_d' => function ($q) use ($request) {
                $q->whereHas('form_denda_foto.form_foto', function ($q) use ($request) {
                  $q->where('tanggal_form', $request->query('tanggal_form'))
                    ->where(function ($query) use ($request) {
                      $query->whereHas('tugas_karyawan', function ($q) use ($request) {
                        $q->where('cabang_id', '=', $request->input('cabang_id'));
                      });
                      $query->orWhereHas('cabang', function ($q) use ($request) {
                        $q->where('cabang_id', $request->input('cabang_id'))
                          ->orWhere('cabang_id', null);
                      });
                    });
                });
              }
          ])
          ->get()
      ]
    ));
  }
}