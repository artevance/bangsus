<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\PengajuanJadwalAbsensi as PengajuanJadwalAbsensiModel;
use App\Http\Models\Cabang;
use App\Http\Models\TipePengajuanJadwalAbsensi;
use App\Http\Models\TugasKaryawan;

class PengajuanJadwalPengajuanJadwalAbsensi extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(PengajuanJadwalAbsensiModel::with([
        'golongan_darah',
        'jenis_kelamin'
      ])->where('nama_karyawan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! PengajuanJadwalAbsensiModel::find($id)->exists()) return $this->response(404);

    return $this->data(PengajuanJadwalAbsensiModel::with([
      'tugas_karyawan',
      'tipe_absensi'
    ])->find($id))->response(200);
  }
}