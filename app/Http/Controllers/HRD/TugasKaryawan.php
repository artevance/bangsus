<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Karyawan as KaryawanModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;

class TugasKaryawan extends Controller
{
  public function index(Request $request)
  {
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tanggal_absensi' => $request->query('tanggal_absensi', date('Y-m-d')),
          'tipe_absensi_id' => $request->query('tipe_absensi_id', 1)
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
          'tanggal_absensi' => date('Y-m-d'),
          'tipe_absensi_id' => $request->query('tipe_absensi_id', 1)
        ];
      break;
      default :
    }

    $this->title('Tugas Karyawan | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('hrd.tugas_karyawan.wrapper',
      $this->passParams([
        'cabangs' => CabangModel::all()
      ])
    );
  }

  public function karyawan(KaryawanModel $karyawan, Request $request)
  {
    $this->title('Detail Karyawan | BangsusSys')->role($request->user()->role->role_code);
    return view('hrd.tugas_karyawan.karyawan.wrapper', $this->passParams(['karyawan' => $karyawan]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:tugas_karyawan,id'
    ]);

    return [
      'data' =>
        TugasKaryawanModel::with([
            'karyawan',
            'cabang',
            'jabatan',
            'divisi'
          ])
          ->find($request->input('id'))
    ];
  }

  public function search(Request $request)
  {
    $model = TugasKaryawanModel::with(['karyawan', 'cabang', 'jabatan', 'divisi']);

    if ($request->has('karyawan_id')) $model = $model->where('karyawan_id', $request->query('karyawan_id'));
    if ($request->has('cabang_id')) $model = $model->where('cabang_id', $request->query('cabang_id'));

    return [
      'data' => $model->get()
    ];
  }

  public function cabangHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_tugas' => 'required|date'
    ]);

    return [
      'data' =>
        TugasKaryawanModel::with([
            'karyawan',
            'cabang',
            'jabatan',
            'divisi'
          ])
          ->where('cabang_id', $request->input('cabang_id'))
          ->where('tanggal_mulai', '<=', $request->input('tanggal_tugas'))
          ->where(function ($query) use ($request) {
            $query->where('tanggal_selesai', '>=', $request->input('tanggal_tugas'))
              ->orWhere('tanggal_selesai', null);
          })
          ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'divisi_id' => 'required|exists:divisi,id',
      'jabatan_id' => 'required|exists:jabatan,id',
      'karyawan_id' => 'required|exists:karyawan,id',
      'tanggal_mulai' => 'required|date',
      'tanggal_selesai' => 'nullable|date',
      'no_finger' => 'nullable|integer'
    ]);

    $model = new TugasKaryawanModel;
    $model->cabang_id = $request->input('cabang_id');
    $model->divisi_id = $request->input('divisi_id');
    $model->jabatan_id = $request->input('jabatan_id');
    $model->karyawan_id = $request->input('karyawan_id');
    $model->tanggal_mulai = $request->input('tanggal_mulai');
    $model->tanggal_selesai = $request->input('tanggal_selesai');
    $model->no_finger = $request->input('no_finger');
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:karyawan,id',
      'cabang_id' => 'nullable|exists:cabang,id',
      'divisi_id' => 'nullable|exists:divisi,id',
      'jabatan_id' => 'nullable|exists:jabatan,id',
      'karyawan_id' => 'nullable|exists:karyawan,id',
      'tanggal_mulai' => 'nullable|date',
      'tanggal_selesai' => 'nullable|date',
      'no_finger' => 'nullable|integer'
    ]);

    $model = TugasKaryawanModel::find($request->input('id'));
    if ($request->has('cabang_id')) $model->cabang_id = $request->input('cabang_id');
    if ($request->has('divisi_id')) $model->divisi_id = $request->input('divisi_id');
    if ($request->has('jabatan_id')) $model->jabatan_id = $request->input('jabatan_id');
    if ($request->has('karyawan_id')) $model->karyawan_id = $request->input('karyawan_id');
    if ($request->has('tanggal_mulai')) $model->tanggal_mulai = $request->input('tanggal_mulai');
    if ($request->has('tanggal_selesai')) $model->tanggal_selesai = $request->input('tanggal_selesai');
    if ($request->has('no_finger')) $model->no_finger = $request->input('no_finger');
    $model->save();
  }
}