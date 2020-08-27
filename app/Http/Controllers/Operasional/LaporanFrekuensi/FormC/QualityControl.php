<?php

namespace App\Http\Controllers\Operasional\LaporanFrekuensi\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\QualityControl as QualityControlModel;
use App\Http\Models\FormQualityControl as FormQualityControlModel;

class QualityControl extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d')),
      'frekuensi_ideal' => $request->query('frekuensi_ideal', 6),
      'submit' => $request->has('submit')
    ];

    $this->title('Laporan Frekuensi Form C2 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);

    return view('operasional.laporan_frekuensi.form_c.quality_control.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'qualityControlModels' => QualityControlModel::all(),
        'formQualityControlModels' => FormQualityControlModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form'))
      ])
    );
  }
}