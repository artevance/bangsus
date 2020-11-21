<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Absensi as AbsensiModel;
use App\Http\Models\Cabang;
use App\Http\Models\TipeAbsensi;
use App\Http\Models\TugasKaryawan;

use Intervention\Image\Facades\Image;

class Absensi extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(AbsensiModel::with([
        'golongan_darah',
        'jenis_kelamin'
      ])->where('nama_karyawan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! AbsensiModel::find($id)->exists()) return $this->response(404);

    return $this->data(AbsensiModel::with([
      'tugas_karyawan',
      'tipe_absensi'
    ])->find($id))->response(200);
  }

  public function manual(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tipe_absensi_id' => $request->input('tipe_absensi_id', TipeAbsensi::first()->id),
      'tanggal_absensi' => $request->input('tanggal_absensi', date('Y-m-d'))
    ];

    return $this
      ->data(
        TugasKaryawan::with([
          'absensi' => function ($q) use ($query) {
            $q->where('tanggal_absensi', '=', $query['tanggal_absensi'])
              ->where('tipe_absensi_id', '=', $query['tipe_absensi_id']);
          },
          'cabang',
          'jabatan',
          'divisi',
          'karyawan',
          'pengajuan_jadwal_absensi' => function ($q) use ($query) {
            $q->where('tanggal_absensi', '=', $query['tanggal_absensi'])
              ->where('tipe_absensi_id', '=', $query['tipe_absensi_id']);
          }
        ])
        ->where('cabang_id', $query['cabang_id'])
        ->where('tanggal_mulai', '<=', $query['tanggal_absensi'])
        ->where(function ($q) use ($query) {
            $q->where('tanggal_selesai', '>=', $query['tanggal_absensi'])
              ->orWhere('tanggal_selesai', null);
          })
        ->get()
      )->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tugas_karyawan_id',
      'tanggal_absensi',
      'tipe_absensi_id',
      'jam_jadwal',
      'jam_absen'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_absensi' => 'required|date_format:Y-m-d',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'jam_jadwal' => 'nullable',
      'jam_absen' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new AbsensiModel;
    $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $model->tanggal_absensi = $request->input('tanggal_absensi');
    $model->tipe_absensi_id = $request->input('tipe_absensi_id');
    $model->jam_jadwal = $request->input('jam_jadwal');
    $model->jam_absen = $request->input('jam_absen');
    $model->user_id = $request->user()->id;
    $model->save();
  }

  public function storePhoto(Request $request)
  {
    $v = Validator::make($request->only(
      'tugas_karyawan_id',
      'tipe_absensi_id',
      'gambar'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'gambar' => 'required',
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $dir = public_path('img/absen/' . uniqid() . uniqid() . uniqid() . '.jpg');
    Image::make(file_get_contents($request->input('gambar')))->save($dir);

    $model = AbsensiModel::where('tugas_karyawan_id', $request->input('tugas_karyawan_id'))
      ->where('tanggal_absensi', date('Y-m-d'))
      ->where('tipe_absensi_id', $request->input('tipe_absensi_id'))
      ->first();
    $model = $model ?? new AbsensiModel;
    $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $model->tanggal_absensi = date('Y-m-d');
    $model->tipe_absensi_id = $request->input('tipe_absensi_id');
    $model->jam_absen = date('H:i:s');
    $model->dir = $dir;
    $model->user_id = $request->user()->id;
    $model->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'jam_jadwal',
      'jam_absen'
    ), [
      'id' => 'required|exists:absensi,id',
      'jam_jadwal' => 'nullable',
      'jam_absen' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = AbsensiModel::find($request->input('id'));
    $model->jam_jadwal = $request->input('jam_jadwal');
    $model->jam_absen = $request->input('jam_absen');
    $model->user_id = $request->user()->id;
    $model->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:absensi,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = AbsensiModel::find($request->input('id'));
    $model->delete();
  }

  public function image(Request $request, $id)
  {
    return Image::make(AbsensiModel::find($id)->dir)->response('jpeg');
  }
}