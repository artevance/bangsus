<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormLaporanFoto as FormLaporanFotoModel;
use App\Http\Models\KelompokLaporanFoto;
use App\Http\Models\Cabang;
use App\Http\Models\Gambar;

class FormLaporanFoto extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormLaporanFotoModel::with([
        'tugas_karyawan',
        'kelompok_laporan_foto'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormLaporanFotoModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormLaporanFotoModel::with([
      'tugas_karyawan',
      'kelompok_laporan_foto'
    ])->find($id))->response(200);
  }

  public function dailyBranchType(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d')),
      'kelompok_laporan_foto_id' => $request->input('kelompok_laporan_foto_id', KelompokLaporanFoto::first()->id)
    ];

    return $this
      ->data(
        FormLaporanFotoModel::with([
          'tugas_karyawan',
          'kelompok_laporan_foto'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->where('kelompok_laporan_foto_id', $query['kelompok_laporan_foto_id'])
        ->orderBy('jam')
        ->get()
      )->response(200);
  }

  public function store(Request $request)
  {
    $v = Validator::make($request->only(
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kelompok_laporan_foto_id',
      'keterangan',
      'gambar',
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'kelompok_laporan_foto_id' => [
        'required',
        Rule::exists('kelompok_laporan_foto', 'id')->where(function ($query) {
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

    $formLaporanFotoModel = new FormLaporanFotoModel;
    $formLaporanFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formLaporanFotoModel->tanggal_form = $request->input('tanggal_form');
    $formLaporanFotoModel->jam = $request->input('jam');
    $formLaporanFotoModel->kelompok_laporan_foto_id = $request->input('kelompok_laporan_foto_id');
    $formLaporanFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formLaporanFotoModel->gambar_id = $gambarModel->id;
    $formLaporanFotoModel->tidak_kirim = 0;
    $formLaporanFotoModel->user_id = $request->user()->id;
    $formLaporanFotoModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'kelompok_laporan_foto_id',
      'keterangan',
      'gambar',
    ), [
      'id' => 'required|exists:form_foto,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'kelompok_laporan_foto_id' => [
        'required',
        Rule::exists('kelompok_laporan_foto', 'id')->where(function ($query) {
          $query->where('master', 0);
        })
      ],
      'keterangan' => 'nullable|max:200',
      'gambar' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $gambarModel = new Gambar;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formLaporanFotoModel = FormLaporanFotoModel::find($request->input('id'));
    $formLaporanFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formLaporanFotoModel->tanggal_form = $request->input('tanggal_form');
    $formLaporanFotoModel->jam = $request->input('jam');
    $formLaporanFotoModel->kelompok_laporan_foto_id = $request->input('kelompok_laporan_foto_id');
    $formLaporanFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formLaporanFotoModel->gambar_id = $gambarModel->id;
    $formLaporanFotoModel->user_id = $request->user()->id;
    $formLaporanFotoModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_foto,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formLaporanFotoModel = FormLaporanFotoModel::find($request->input('id'));
    if ($formLaporanFotoModel->kelompok_laporan_foto->master == 1) {
      return;
    }
    $formLaporanFotoModel->user_id = $request->user()->id;
    $formLaporanFotoModel->save();
    $formLaporanFotoModel->delete();
  }
}