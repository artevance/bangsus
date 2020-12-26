<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional\FormC1;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\Gambar;
use App\Http\Models\FormFoto;
use App\Http\Models\FormMasakNasi as FormMasakNasiModel;
use App\Http\Models\Cabang;

use Intervention\Image\Facades\Image;

class FormMasakNasi extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormMasakNasiModel::with([
        'tugas_karyawan',
        'satuan',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormMasakNasiModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormMasakNasiModel::with([
      'tugas_karyawan',
      'satuan',
      'user'
    ])->find($id))->response(200);
  }

  public function dailyBranch(Request $request)
  {
    $query = [
      'cabang_id' => $request->input('cabang_id', Cabang::first()->id),
      'tanggal_form' => $request->input('tanggal_form', date('Y-m-d'))
    ];

    return $this
      ->data(
        FormMasakNasiModel::with([
          'tugas_karyawan',
          'satuan',
          'user'
        ])
        ->where('tanggal_form', $query['tanggal_form'])
        ->byCabang($query['cabang_id'])
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
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'gambar' => 'required',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $dir = public_path('img/form_masak_nasi/' . uniqid() . uniqid() . uniqid() . '.jpg');
    Image::make(file_get_contents($request->input('gambar')))->save($dir);

    $gambarModel = new Gambar;
    $gambarModel->dir = $dir;
    $gambarModel->save();

    $formFotoModel = new FormFoto;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = 3;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();

    $formMasakNasiModel = new FormMasakNasiModel;
    $formMasakNasiModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formMasakNasiModel->tanggal_form = $request->input('tanggal_form');
    $formMasakNasiModel->jam = $request->input('jam');
    $formMasakNasiModel->form_foto_id = $formFotoModel->id;
    $formMasakNasiModel->qty = $request->input('qty');
    $formMasakNasiModel->satuan_id = $request->input('satuan_id');
    $formMasakNasiModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formMasakNasiModel->gambar_id = $gambarModel->id;
    $formMasakNasiModel->user_id = $request->user()->id;
    $formMasakNasiModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'id' => 'required|exists:form_masak_nasi,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'gambar' => 'nullable',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->filled('gambar')) {
      $dir = public_path('img/form_masak_nasi/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($request->input('gambar')))->save($dir);

      $gambarModel = new Gambar;
      $gambarModel->dir = $dir;
      $gambarModel->save();
    }

    $formMasakNasiModel = FormMasakNasiModel::find($request->input('id'));
    $formMasakNasiModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formMasakNasiModel->tanggal_form = $request->input('tanggal_form');
    $formMasakNasiModel->jam = $request->input('jam');
    $formMasakNasiModel->qty = $request->input('qty');
    $formMasakNasiModel->satuan_id = $request->input('satuan_id');
    $formMasakNasiModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formMasakNasiModel->user_id = $request->user()->id;
    $formMasakNasiModel->save();

    $formFotoModel = FormFoto::find($formMasakNasiModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
      $formFotoModel->tanggal_form = $request->input('tanggal_form');
      $formFotoModel->jam = $request->input('jam');
      $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
      if ($request->filled('gambar')) $formFotoModel->gambar_id = $gambarModel->id;
      $formFotoModel->user_id = $request->user()->id;
      $formFotoModel->save();
    }
  }

  public function destroy(Request $request)
  {
    $v = Validator::make($request->only('id'), [
      'id' => 'required|exists:form_masak_nasi,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formMasakNasiModel = FormMasakNasiModel::find($request->input('id'));
    $formMasakNasiModel->user_id = $request->user()->id;
    $formMasakNasiModel->save();

    $formFotoModel = FormFoto::find($formMasakNasiModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      $formFotoModel->user_id = $request->user()->id;
      $formFotoModel->save(); 

      $gambarModel = Gambar::find($formFotoModel->gambar_id);
      if ( ! is_null($gambarModel)) {
        $gambarModel->konten = '';
        $gambarModel->save();
      }

      $formFotoModel->delete();
    }

    $formMasakNasiModel->delete();
  }
}