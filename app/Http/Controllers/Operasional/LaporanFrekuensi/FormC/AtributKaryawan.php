<?php

namespace App\Http\Controllers\Operasional\LaporanFrekuensi\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\AtributKaryawan as AtributKaryawanModel;
use App\Http\Models\FormAtributKaryawan as FormAtributKaryawanModel;

class AtributKaryawan extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d')),
      'frekuensi_ideal' => $request->query('frekuensi_ideal', 12),
      'submit' => $request->has('submit')
    ];

    $this->title('Laporan Rekap Form C3 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);

    return view('operasional.laporan_frekuensi.form_c.atribut_karyawan.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'atributKaryawanModels' => AtributKaryawanModel::all(),
        'formAtributKaryawanModels' => FormAtributKaryawanModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form'))
      ])
    );
  }
}