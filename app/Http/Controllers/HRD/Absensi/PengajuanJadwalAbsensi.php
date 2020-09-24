<?php

namespace App\Http\Controllers\HRD\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TipeAbsensi as TipeAbsensiModel;
use App\Http\Models\PengajuanJadwalAbsensi as PengajuanJadwalAbsensiModel;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;
use App\Http\Models\Absensi as AbsensiModel;

class PengajuanJadwalAbsensi extends Controller
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

    $this->title('Pengajuan Jadwal Absensi | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('hrd.absensi.pengajuan_jadwal.wrapper', $this->passParams(['cabangs' => CabangModel::all(), 'tipeAbsensis' => TipeAbsensiModel::all()]));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:pengajuan_jadwal_absensi,id'
    ]);

    return ['data' => PengajuanJadwalAbsensiModel::with(['tugas_karyawan', 'user'])->find($request->input('id'))];
  }

  public function cabangTipeHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'tanggal_absensi' => 'required|date'
    ]);

    return [
      'data' => 
        TugasKaryawanModel::with([
            'absensi' => function ($q) use ($request) {
              $q->where('tanggal_absensi', '=', $request->input('tanggal_absensi'))
                ->where('tipe_absensi_id', '=', $request->input('tipe_absensi_id'));
            },
              'absensi.tipe_absensi',
            'pengajuan_jadwal_absensi' => function ($q) use ($request) {
              $q->where('tanggal_absensi', '=', $request->input('tanggal_absensi'))
                ->where('tipe_absensi_id', '=', $request->input('tipe_absensi_id'));
            },
              'pengajuan_jadwal_absensi.tipe_absensi',
            'cabang',
            'jabatan',
            'divisi',
            'karyawan'
          ])
        ->where('cabang_id', $request->query('cabang_id'))
        ->where('tanggal_mulai', '<=', $request->input('tanggal_absensi'))
        ->where(function ($query) use ($request) {
            $query->where('tanggal_selesai', '>=', $request->input('tanggal_absensi'))
              ->orWhere('tanggal_selesai', null);
          })
        ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_absensi' => 'required|date_format:Y-m-d',
      'tipe_absensi_id' => 'required|exists:tipe_absensi,id',
      'jam_jadwal' => [
        'nullable',
        function ($attr, $v, $f) use ($request) {
          $target = AbsensiModel::where('tanggal_absensi', $request->input('tanggal_absensi'))
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

          if ($max >= $v) $f('Perubahan jam jadwal tidak diizinkan');
        }
      ],
      'user_id' => 'required|exists:user,id'
    ]);

    $model = new PengajuanJadwalAbsensiModel;
    $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $model->tanggal_absensi = $request->input('tanggal_absensi');
    $model->tipe_absensi_id = $request->input('tipe_absensi_id');
    $model->jam_jadwal = $request->input('jam_jadwal');
    $model->user_id = $request->input('user_id');
    $model->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:pengajuan_jadwal_absensi,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_absensi' => 'nullable|date_format:Y-m-d',
      'tipe_absensi_id' => 'nullable|exists:tipe_absensi,id',
      'jam_jadwal' => [
        'nullable',
        function ($attr, $v, $f) use ($request) {
          $target = AbsensiModel::where('tanggal_absensi', $request->input('tanggal_absensi'))
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

          if ($max >= $v) $f('Perubahan jam jadwal tidak diizinkan');
        }
      ],
      'user_id' => 'required|exists:user,id'
    ]);

    $model = PengajuanJadwalAbsensiModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $model->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_absensi')) $model->tanggal_absensi = $request->input('tanggal_absensi');
    if ($request->has('tipe_absensi_id')) $model->tipe_absensi_id = $request->input('tipe_absensi_id');
    $model->jam_jadwal = $request->input('jam_jadwal');
    $model->user_id = $request->input('user_id');
    $model->save();
  }

  public function approve(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:pengajuan_jadwal_absensi,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $model = PengajuanJadwalAbsensiModel::find($request->input('id'));

    AbsensiModel::updateOrCreate([
      'tugas_karyawan_id' => $model->tugas_karyawan_id,
      'tipe_absensi_id' => $model->tipe_absensi_id,
      'tanggal_absensi' => $model->tanggal_absensi
    ], [
      'jam_jadwal' => $model->jam_jadwal,
      'user_id' => $request->input('user_id')
    ]);

    $model->delete();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:pengajuan_jadwal_absensi,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $model = PengajuanJadwalAbsensiModel::find($request->input('id'));
    $model->delete();
  }
}