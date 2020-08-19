<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Absensi as AbsensiModel;
use Illuminate\Validation\Rule;

class Absensi extends Controller
{
  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:absensi,id'
    ]);

    return ['data' => AbsensiModel::with(['tugas_karyawan', 'user'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        AbsensiModel::with(['tugas_karyawan', 'user'])
           ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_absensi' => 'required|date_format:Y-m-d',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'jam_jadwal' => 'required',
      'jam_absen' => 'nullable',
      'user_id' => 'required|exists:user,id'
    ]);

    $model = new AbsensiModel;
    $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $model->tanggal_absensi = $request->input('tanggal_absensi');
    $model->tipe_absensi_id = $request->input('tipe_absensi_id');
    $model->jam_jadwal = $request->input('jam_jadwal');
    $model->jam_absen = $request->input('jam_absen');
    $model->user_id = $request->input('user_id');
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:absensi,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_absensi' => 'nullable|date_format:Y-m-d',
      'tipe_absensi_id' => 'nullable|exists:tipe_absensi,id',
      'jam_jadwal' => 'nullable',
      'jam_absen' => 'nullable',
      'user_id' => 'required|exists:user,id'
    ]);

    $model = AbsensiModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_absensi')) $model->tanggal_absensi = $request->input('tanggal_absensi');
    if ($request->has('tipe_absensi_id')) $model->tipe_absensi_id = $request->input('tipe_absensi_id');
    if ($request->has('jam_jadwal')) $model->jam_jadwal = $request->input('jam_jadwal');
    if ($request->has('jam_absen')) $model->jam_absen = $request->input('jam_absen');
    $model->user_id = $request->input('user_id');
    $model->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:absensi,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $model = AbsensiModel::find($request->input('id'));
    $model->delete();
  }
}