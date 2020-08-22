<?php

namespace App\Http\Controllers\Operasional\Laporan\FormC\Produksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\FormMasakNasi as FormMasakNasiModel;

class MasakNasi extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'cabang_id' => $request->query('cabang_id', 1),
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d'))
    ];

    $this->title('Laporan Form C1 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->nav('masakNasi')
      ->query($query);
    return view('operasional.laporan.form_c.produksi.masak_nasi.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'results' =>
          FormMasakNasiModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'satuan',
            'user'
          ])
          ->whereHas('tugas_karyawan', function ($q) use ($request) {
              $q->where('cabang_id', '=', $request->input('cabang_id'));
            })
          ->where('tanggal_form', $request->input('tanggal_form'))
          ->get()
      ]
    ));
  }
}