<?php

namespace App\Http\Controllers\Operasional\LaporanFrekuensi\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\KegiatanKebersihan as KegiatanKebersihanModel;
use App\Http\Models\FormKebersihan as FormKebersihanModel;

class Kebersihan extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d')),
      'frekuensi_ideal' => $request->query('frekuensi_ideal', 100),
      'submit' => $request->has('submit')
    ];

    $this->title('Laporan Frekuensi Form C4 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);

    return view('operasional.laporan_frekuensi.form_c.kebersihan.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'kegiatanKebersihanModels' => KegiatanKebersihanModel::all(),
        'formKebersihanModels' => FormKebersihanModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form'))
      ])
    );
  }
}