<?php

namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Cabang as CabangModel;
use App\Http\Models\FormFoto as FormFotoModel;
use App\Http\Models\Gambar as GambarModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FormFoto extends Controller
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

    $this->title('Form Foto | BangsusSys')
      ->role($request->user()->role->role_code)
      ->query($query);
    return view('operasional.form_foto.wrapper', 
      $this->passParams([
        'cabangs' => CabangModel::all()
      ]
    ));
  }

  public function get(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_foto,id'
    ]);

    return ['data' => FormFotoModel::find($request->input('id'))];
  }

  public function search(Request $request)
  {
    return [
      'data' =>
        FormFotoModel::with(['tugas_karyawan', 'user', 'form_denda_foto', 'form_denda_foto.d', 'form_denda_foto.total'])
          ->get()
    ];
  }

  public function cabangHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_form' => 'required|date',
    ]);

    return [
      'data' => 
        FormFotoModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'kelompok_foto',
            'user',
            'form_denda_foto',
              'form_denda_foto.d'
          ])
          ->whereHas('tugas_karyawan', function ($q) use ($request) {
              $q->where('cabang_id', '=', $request->input('cabang_id'));
            })
          ->where('tanggal_form', $request->input('tanggal_form'))
          ->get()
    ];
  }

  public function cabangKelompokHarian(Request $request)
  {
    $request->validate([
      'cabang_id' => 'required|exists:cabang,id',
      'tanggal_form' => 'required|date',
      'kelompok_foto_id' => 'required|exists:kelompok_foto,id',
    ]);

    return [
      'data' => 
        FormFotoModel::with([
            'tugas_karyawan', 
              'tugas_karyawan.cabang',
                'tugas_karyawan.cabang.tipe_cabang',
              'tugas_karyawan.divisi',
              'tugas_karyawan.jabatan',
              'tugas_karyawan.karyawan',
                'tugas_karyawan.karyawan.golongan_darah',
                'tugas_karyawan.karyawan.jenis_kelamin',
            'kelompok_foto',
            'user',
            'form_denda_foto',
              'form_denda_foto.d'
          ])
          ->whereHas('tugas_karyawan', function ($q) use ($request) {
              $q->where('cabang_id', '=', $request->input('cabang_id'));
            })
          ->where('kelompok_foto_id', $request->input('kelompok_foto_id'))
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
      'kelompok_foto_id' => [
        'required',
        Rule::exists('kelompok_foto', 'id')->where(function ($query) {
          $query->where('master', 0);
        })
      ],
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'required'
    ]);

    $gambarModel = new GambarModel;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formFotoModel = new FormFotoModel;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = $request->input('kelompok_foto_id');
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->user_id = $request->input('user_id');
    $formFotoModel->save();
  }

  public function put(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_foto,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'kelompok_foto_id' => [
        'nullable',
        Rule::exists('kelompok_foto', 'id')->where(function ($query) {
          $query->where('master', 0);
        })
      ],
      'keterangan' => 'nullable|max:200',
      'user_id' => 'required|exists:user,id',
      'gambar' => 'nullable'
    ]);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formFotoModel = FormFotoModel::find($request->input('id'));
    if ($request->has('tugas_karyawan_id')) $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    if ($request->has('tanggal_form')) $formFotoModel->tanggal_form = $request->input('tanggal_form');
    if ($request->has('jam')) $formFotoModel->jam = $request->input('jam');
    if ($request->has('kelompok_foto_id')) $formFotoModel->kelompok_foto_id = $request->input('kelompok_foto_id');
    if ($request->has('keterangan')) $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->user_id = $request->input('user_id');
    $formFotoModel->save();
  }

  public function delete(Request $request)
  {
    $request->validate([
      'id' => 'required|exists:form_foto,id',
      'user_id' => 'required|exists:user,id'
    ]);

    $formFotoModel = FormFotoModel::find($request->input('id'));
    if ($formFotoModel->kelompok_foto->master == 1) {
      return;
    }
    $formFotoModel->user_id = $request->input('user_id');
    $formFotoModel->save();
    $formFotoModel->delete();
  }
}