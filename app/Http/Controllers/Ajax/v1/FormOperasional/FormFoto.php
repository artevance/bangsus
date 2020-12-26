<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormFoto as FormFotoModel;
use App\Http\Models\KelompokFoto;
use App\Http\Models\Cabang;
use App\Http\Models\Gambar;

use Intervention\Image\Facades\Image;

class FormFoto extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormFotoModel::with([
        'tugas_karyawan',
        'kelompok_foto'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormFotoModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormFotoModel::with([
      'tugas_karyawan',
      'kelompok_foto',
      'form_denda_foto'
    ])->find($id))->response(200);
  }

  public function dailyBranchType(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d')),
      'kelompok_foto_id' => $request->input('kelompok_foto_id', KelompokFoto::first()->id)
    ];

    return $this
      ->data(
        FormFotoModel::with([
          'tugas_karyawan',
          'kelompok_foto',
          'form_denda_foto'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
        ->where('kelompok_foto_id', $query['kelompok_foto_id'])
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

    $dir = public_path('img/form_foto/' . uniqid() . uniqid() . uniqid() . '.jpg');
    Image::make(file_get_contents($request->input('gambar')))->save($dir);

    $gambarModel = new Gambar;
    $gambarModel->dir = $dir;
    $gambarModel->save();

    $formFotoModel = new FormFotoModel;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = $request->input('kelompok_foto_id');
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();
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
      $dir = public_path('img/form_foto/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($request->input('gambar')))->save($dir);

      $gambarModel = new Gambar;
      $gambarModel->dir = $dir;
      $gambarModel->save();
    }

    $formFotoModel = FormFotoModel::find($request->input('id'));
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = $request->input('kelompok_foto_id');
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only(
      'id'
    ), [
      'id' => 'required|exists:form_foto,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formFotoModel = FormFotoModel::find($request->input('id'));
    if ($formFotoModel->kelompok_foto->master == 1) {
      return;
    }
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();
    $formFotoModel->delete();
  }
}