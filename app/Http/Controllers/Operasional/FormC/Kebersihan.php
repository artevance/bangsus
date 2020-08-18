<?php

namespace App\Http\Controllers\Operasional\FormC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\FormKebersihan as FormKebersihanModel;
use App\Http\Models\KegiatanKebersihan as KegiatanKebersihanModel;
use App\Http\Models\Cabang as CabangModel;

class Kebersihan extends Controller
{
  public function index(Request $request)
  {
    switch ($request->user()->role->role_code) {
      case 'admin' :
        $query = [
          'cabang_id' => $request->query('cabang_id', 1),
          'tanggal_form' => $request->query('tanggal_form', date('Y-m-d'))
        ];
      break;
      case 'leader' :
        $query = [
          'cabang_id' => $request->user()->tugas_karyawan->cabang_id,
          'tanggal_form' => date('Y-m-d')
        ];
      break;
      default :
    }

    $this->title('Form C4 | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_c.kebersihan.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all(),
        'kegiatanKebersihans' => KegiatanKebersihanModel::all()
      ]
    ));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id'
    ]);

    return ['data' => FormKebersihanModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' => FormKebersihanModel::with([])
                  ->get()
    ];
  }

  public function cabangHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_form' => 'required|date'
    ]);

    return [
      'data' => FormKebersihanModel::with([
                  'tugas_karyawan', 
                    'tugas_karyawan.cabang',
                      'tugas_karyawan.cabang.tipe_cabang',
                    'tugas_karyawan.divisi',
                    'tugas_karyawan.jabatan',
                    'tugas_karyawan.karyawan',
                      'tugas_karyawan.karyawan.golongan_darah',
                      'tugas_karyawan.karyawan.jenis_kelamin',
                  'kegiatan_kebersihan',
                  'user'
                ])
                ->whereHas('tugas_karyawan', function ($q) use ($request) {
                    $q->where('cabang_id', '=', $request->input('cabang_id'));
                  })
                ->where('tanggal_form', $request->input('tanggal_form'))
                ->get()
    ];
  }

  public function post(Request $request)
  {
    $request->validate([
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'kegiatan_kebersihan_id' => 'required|exists:kegiatan_kebersihan,id',
      'skor' => 'required|numeric',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id'
    ]);

    $formKebersihanModel = new FormKebersihanModel;
    $formKebersihanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formKebersihanModel->tanggal_form = $request->input('tanggal_form');
    $formKebersihanModel->jam = $request->input('jam');
    $formKebersihanModel->kegiatan_kebersihan_id = $request->input('kegiatan_kebersihan_id');
    $formKebersihanModel->skor = $request->input('skor');
    $formKebersihanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formKebersihanModel->user_id = $request->input('user_id');
    $formKebersihanModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'kegiatan_kebersihan_id' => 'nullable|exists:kegiatan_kebersihan,id',
      'skor' => 'nullable|numeric',
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
    ]);

    $formKebersihanModel = FormKebersihanModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formKebersihanModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formKebersihanModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formKebersihanModel->jam = $request->input('jam');
    if ($request->has('kegiatan_kebersihan_id')) $formKebersihanModel->kegiatan_kebersihan_id = $request->input('kegiatan_kebersihan_id');
    if ($request->has('skor')) $formKebersihanModel->skor = $request->input('skor');
    if ($request->has('keterangan')) $formKebersihanModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formKebersihanModel->user_id = $request->input('user_id');
    $formKebersihanModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_goreng,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formKebersihanModel = FormKebersihanModel::find($request->input('id'));
    $formKebersihanModel->user_id = $request->input('user_id');
    $formKebersihanModel->save();
    $formKebersihanModel->delete();
  }
}