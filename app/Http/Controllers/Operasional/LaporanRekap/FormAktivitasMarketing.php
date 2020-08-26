<?php

namespace App\Http\Controllers\Operasional\LaporanRekap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\FormAktivitasMarketing as FormAktivitasMarketingModel;

class FormAktivitasMarketing extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d')),
      'submit' => $request->has('submit')
    ];

    $this->title('Form Denda Foto | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);

    return view('operasional.laporan_rekap.form_aktivitas_marketing.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'formAktivitasMarketingModels' =>
          FormAktivitasMarketingModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'aktivitas_marketing',
            'satuan',
            'item_marketing',
            'user'
          ])
          ->where('tanggal_form', $request->query('tanggal_form'))
      ])
    );
  }
}