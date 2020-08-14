<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Karyawan as KaryawanModel;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\TugasKaryawan as TugasKaryawanModel;

class Karyawan extends Controller
{
  public function index(Request $request)
  {
    $this->title('Karyawan | BangsusSys')->role($request->user()->role->role_code);
    return view('hrd.karyawan.wrapper', $this->passParams());
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:karyawan,id'
    ]);

    return ['data' => KaryawanModel::with(['golongan_darah', 'jenis_kelamin'])->find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => KaryawanModel::with(['golongan_darah', 'jenis_kelamin'])
                  ->where('nip', 'LIKE', $request->input('q'))
                  ->orWhere('nik', 'LIKE', $request->input('q'))
                  ->orWhere('nama_karyawan', 'LIKE', $request->input('q'))
                  ->orWhere('tempat_lahir', 'LIKE', $request->input('q'))
                  ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'nik' => 'nullable|size:16|unique:karyawan,nik',
      'nama_karyawan' => 'required|max:200',
      'tempat_lahir' => 'nullable|max:200',
      'tanggal_lahir' => 'nullable|date',
      'golongan_darah_id' => 'required|exists:golongan_darah,id',
      'jenis_kelamin_id' => 'required|exists:jenis_kelamin,id',

      'cabang_id' => 'required|exists:cabang,id',
      'divisi_id' => 'required|exists:divisi,id',
      'jabatan_id' => 'required|exists:jabatan,id',
      'tanggal_mulai' => 'required|date',
      'no_finger' => 'nullable|integer'
    ]);

    $karyawanModel = new KaryawanModel;
    $karyawanModel->nik = $request->input('nik', null);
    $karyawanModel->nip = $karyawanModel->getNip(
      CabangModel::find($request->input('cabang_id'))['kode_cabang'],
      $request->input('tanggal_mulai')
    );
    $karyawanModel->nama_karyawan = strtoupper($request->input('nama_karyawan'));
    $karyawanModel->tempat_lahir = strtoupper($request->input('tempat_lahir'));
    $karyawanModel->tanggal_lahir = $request->input('tanggal_lahir');
    $karyawanModel->golongan_darah_id = $request->input('golongan_darah_id');
    $karyawanModel->jenis_kelamin_id = $request->input('jenis_kelamin_id');
    $karyawanModel->save();

    $tugasKaryawanModel = new TugasKaryawanModel;
    $tugasKaryawanModel->cabang_id = $request->input('cabang_id');
    $tugasKaryawanModel->divisi_id = $request->input('divisi_id');
    $tugasKaryawanModel->jabatan_id = $request->input('jabatan_id');
    $tugasKaryawanModel->karyawan_id = $karyawanModel->id;
    $tugasKaryawanModel->tanggal_mulai = $request->input('tanggal_mulai');
    $tugasKaryawanModel->no_finger = $request->input('no_finger');
    $tugasKaryawanModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:karyawan,id',
      'karyawan' => 'required|max:200'
    ]);

    $model = KaryawanModel::find($request->input('id'));
    $model->karyawan = strtoupper($request->input('karyawan'));
    $model->save();
  }
}