<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\PengajuanJadwalAbsensi as PengajuanJadwalAbsensiModel;
use App\Http\Models\Absensi;
use App\Http\Models\Cabang;
use App\Http\Models\TugasKaryawan;

class PengajuanJadwalAbsensi extends Controller
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

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tugas_karyawan_id',
      'tanggal_absensi',
      'tipe_absensi_id',
      'jam_jadwal'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_absensi' => 'required|date_format:Y-m-d',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'jam_jadwal' => [
        'nullable',
        function ($attr, $v, $f) use ($request) {
          $target = Absensi::where('tanggal_absensi', $request->input('tanggal_absensi'))
            ->where('tugas_karyawan_id', $request->input('tugas_karyawan_id'))
            ->where('tipe_absensi_id', $request->input('tipe_absensi_id'))
            ->first();

          $max = is_null($target)
            ? strtotime($v)
            : (
              is_null($target->jam_jadwal)
                ? strtotime($v)
                : strtotime($target->jam_jadwal) + 7200
            );

          if ($max > strtotime($v)) $f('Perubahan jam jadwal tidak diizinkan');
        }
      ]
    ]);

    $model = new PengajuanJadwalAbsensiModel;
    $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $model->tanggal_absensi = $request->input('tanggal_absensi');
    $model->tipe_absensi_id = $request->input('tipe_absensi_id');
    $model->jam_jadwal = $request->input('jam_jadwal');
    $model->user_id = $request->user()->id;
    $model->save();
  }

  public function approve(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:pengajuan_jadwal_absensi,id'
    ]);

    $model = PengajuanJadwalAbsensiModel::find($request->input('id'));

    Absensi::updateOrCreate([
      'tugas_karyawan_id' => $model->tugas_karyawan_id,
      'tipe_absensi_id' => $model->tipe_absensi_id,
      'tanggal_absensi' => $model->tanggal_absensi
    ], [
      'jam_jadwal' => $model->jam_jadwal,
      'user_id' => $request->user()->id
    ]);

    $model->delete();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:pengajuan_jadwal_absensi,id'
    ]);

    $model = PengajuanJadwalAbsensiModel::find($request->input('id'));
    $model->delete();
  }
}