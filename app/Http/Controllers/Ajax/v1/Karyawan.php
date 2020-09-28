<?php

namespace App\Http\Controllers\Ajax\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Karyawan as KaryawanModel;
use App\Http\Models\Cabang;
use App\Http\Models\TugasKaryawan;

class Karyawan extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(KaryawanModel::with([
        'golongan_darah',
        'jenis_kelamin'
      ])->where('nama_karyawan', 'like', '%' . $request->input('q') . '%') ->get())
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! KaryawanModel::find($id)->exists()) return $this->response(404);

    return $this->data(KaryawanModel::with([
      'golongan_darah',
      'jenis_kelamin'
    ])->find($id))->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'nik',
      'nama_karyawan',
      'tempat_lahir',
      'tanggal_lahir',
      'golongan_darah_id',
      'jenis_kelamin_id',
      'cabang_id',
      'divisi_id',
      'jabatan_id',
      'tanggal_mulai',
      'no_finger'
    ), [
      'nik' => 'nullable|integer|digits:16|unique:karyawan,nik',
      'nama_karyawan' => 'required|max:200',
      'tempat_lahir' => 'nullable|max:200',
      'tanggal_lahir' => 'nullable|date_format:Y-m-d',
      'golongan_darah_id' => 'required|exists:golongan_darah,id',
      'jenis_kelamin_id' => 'required|exists:jenis_kelamin,id',
      'cabang_id' => 'required|exists:cabang,id',
      'divisi_id' => 'required|exists:divisi,id',
      'jabatan_id' => 'required|exists:jabatan,id',
      'tanggal_mulai' => 'required|date_format:Y-m-d',
      'no_finger' => 'nullable|integer'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = new KaryawanModel;
    $model->nik = $request->input('nik', null);
    $model->nip = $model->getNip(
      Cabang::find($request->input('cabang_id'))['kode_cabang'],
      $request->input('tanggal_mulai')
    );
    $model->nama_karyawan = strtoupper($request->input('nama_karyawan'));
    $model->tempat_lahir = strtoupper($request->input('tempat_lahir'));
    $model->tanggal_lahir = $request->input('tanggal_lahir');
    $model->golongan_darah_id = $request->input('golongan_darah_id');
    $model->jenis_kelamin_id = $request->input('jenis_kelamin_id');
    $model->save();

    $tugasKaryawanModel = new TugasKaryawan;
    $tugasKaryawanModel->cabang_id = $request->input('cabang_id');
    $tugasKaryawanModel->divisi_id = $request->input('divisi_id');
    $tugasKaryawanModel->jabatan_id = $request->input('jabatan_id');
    $tugasKaryawanModel->karyawan_id = $model->id;
    $tugasKaryawanModel->tanggal_mulai = $request->input('tanggal_mulai');
    $tugasKaryawanModel->no_finger = $request->input('no_finger');
    $tugasKaryawanModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'nik',
      'nama_karyawan',
      'tempat_lahir',
      'tanggal_lahir',
      'golongan_darah_id',
      'jenis_kelamin_id'
    ), [
      'id' => 'required|exists:karyawan,id',
      'nik' => [
        'required',
        'integer',
        'digits:16',
        Rule::unique('karyawan')
          ->ignore(
            $request->input('nik'),
            'nik'
          )
      ],
      'nama_karyawan' => 'nullable|max:200',
      'tempat_lahir' => 'nullable|max:200',
      'tanggal_lahir' => 'nullable|date',
      'golongan_darah_id' => 'nullable|exists:golongan_darah,id',
      'jenis_kelamin_id' => 'nullable|exists:jenis_kelamin,id',
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $model = KaryawanModel::find($request->input('id'));
    $model->nik = $request->input('nik');
    $model->nama_karyawan = strtoupper($request->input('nama_karyawan'));
    $model->tempat_lahir = strtoupper($request->input('tempat_lahir'));
    $model->tanggal_lahir = $request->input('tanggal_lahir');
    $model->golongan_darah_id = $request->input('golongan_darah_id');
    $model->jenis_kelamin_id = $request->input('jenis_kelamin_id');
    $model->save();
  }
}