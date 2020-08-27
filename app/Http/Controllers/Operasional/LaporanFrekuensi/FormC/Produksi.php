<?php

namespace App\Http\Controllers\Operasional\LaporanFrekuensi\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\FormThawingAyam as FormThawingAyamModel;
use App\Http\Models\ItemGoreng as ItemGorengModel;
use App\Http\Models\TipeProsesSambal as TipeProsesSambalModel;
use App\Http\Models\TipeProsesTepung as TipeProsesTepungModel;
use App\Http\Models\TipeProsesMinyak as TipeProsesMinyakModel;
use App\Http\Models\TipeProsesMargarin as TipeProsesMargarinModel;
use App\Http\Models\TipeProsesLPG as TipeProsesLPGModel;
use App\Http\Models\FormGoreng as FormGorengModel;
use App\Http\Models\FormMasakNasi as FormMasakNasiModel;
use App\Http\Models\FormSambal as FormSambalModel;
use App\Http\Models\FormTepung as FormTepungModel;
use App\Http\Models\FormMinyak as FormMinyakModel;
use App\Http\Models\FormMargarin as FormMargarinModel;
use App\Http\Models\FormLPG as FormLPGModel;

class Produksi extends Controller
{
  public function index(Request $request)
  {
    $query = [
      'tanggal_form' => $request->query('tanggal_form', date('Y-m-d')),
      'submit' => $request->has('submit')
    ];

    $this->title('Laporan Frekuensi Form C1 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);

    return view('operasional.laporan_frekuensi.form_c.produksi.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'itemGorengModels' => ItemGorengModel::all(),
        'tipeProsesSambalModels' => TipeProsesSambalModel::all(),
        'tipeProsesTepungModels' => TipeProsesTepungModel::all(),
        'tipeProsesMinyakModels' => TipeProsesMinyakModel::all(),
        'tipeProsesMargarinModels' => TipeProsesMargarinModel::all(),
        'tipeProsesLPGModels' => TipeProsesLPGModel::all(),
        'formThawingAyamModels' => FormThawingAyamModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form')),
        'formGorengModels' => FormGorengModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form')),
        'formMasakNasiModels' => FormMasakNasiModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form')),
        'formSambalModels' => FormSambalModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form')),
        'formTepungModels' => FormTepungModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form')),
        'formMinyakModels' => FormMinyakModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form')),
        'formMargarinModels' => FormMargarinModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form')),
        'formLPGModels' => FormLPGModel::with(['tugas_karyawan'])->where('tanggal_form', $request->query('tanggal_form'))
      ])
    );
  }
}