<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormTugas as FormTugasModel;
use App\Http\Models\KelompokTugas;
use App\Http\Models\Cabang;
use App\Http\Models\Gambar;

class FormTugas extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormTugasModel::with([
        'tugas_karyawan',
        'kelompok_foto'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormTugasModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormTugasModel::with([
      'tugas_karyawan',
      'kelompok_foto'
    ])->find($id))->response(200);
  }

  public function branch(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id)
    ];

    return $this
      ->data([
        'cabang_aktif' => FormTugasModel::with([
            'cabang',
            'form_tugas_file'
          ])
          ->byCabang($query['cabang_id'])
          ->stillActive()
          ->whereDate('waktu_deadline', '>=', date('Y-m-d H:i:s'))
          ->orderBy('waktu_deadline')
          ->get(),
        'cabang_lewat_deadline' => FormTugasModel::with([
            'cabang',
            'form_tugas_file'
          ])
          ->byCabang($query['cabang_id'])
          ->stillActive()
          ->whereDate('waktu_deadline', '<', date('Y-m-d H:i:s'))
          ->orderBy('waktu_deadline')
          ->get(),
        'cabang_belum_diperiksa' => FormTugasModel::with([
            'cabang',
            'form_tugas_file'
          ])
          ->byCabang($query['cabang_id'])
          ->waitingChecker()
          ->orderBy('waktu_pengumpulan', 'DESC')
          ->get(),
        'cabang_sudah_diperiksa' => FormTugasModel::with([
            'cabang',
            'form_tugas_file'
          ])
          ->byCabang($query['cabang_id'])
          ->isChecked()
          ->orderBy('waktu_diperiksa', 'DESC')
          ->get()
      ])->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kelompok_foto_id',
      'keterangan',
      'gambar',
    ), [
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
      'gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $gambarModel = new Gambar;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formTugasModel = new FormTugasModel;
    $formTugasModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formTugasModel->tanggal_form = $request->input('tanggal_form');
    $formTugasModel->jam = $request->input('jam');
    $formTugasModel->kelompok_foto_id = $request->input('kelompok_foto_id');
    $formTugasModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formTugasModel->gambar_id = $gambarModel->id;
    $formTugasModel->tidak_kirim = 0;
    $formTugasModel->user_id = $request->user()->id;
    $formTugasModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kelompok_foto_id',
      'keterangan',
      'gambar',
    ), [
      'id' => 'required|exists:form_foto,id',
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
      'gambar' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->filled('gambar')) {
      $gambarModel = new Gambar;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formTugasModel = FormTugasModel::find($request->input('id'));
    $formTugasModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formTugasModel->tanggal_form = $request->input('tanggal_form');
    $formTugasModel->jam = $request->input('jam');
    $formTugasModel->kelompok_foto_id = $request->input('kelompok_foto_id');
    $formTugasModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formTugasModel->gambar_id = $gambarModel->id;
    $formTugasModel->user_id = $request->user()->id;
    $formTugasModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_foto,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formTugasModel = FormTugasModel::find($request->input('id'));
    if ($formTugasModel->kelompok_foto->master == 1) {
      return;
    }
    $formTugasModel->user_id = $request->user()->id;
    $formTugasModel->save();
    $formTugasModel->delete();
  }
}