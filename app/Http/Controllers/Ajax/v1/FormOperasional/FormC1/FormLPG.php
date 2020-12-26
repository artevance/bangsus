<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional\FormC1;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\Gambar;
use App\Http\Models\FormFoto;
use App\Http\Models\FormLPG as FormLPGModel;
use App\Http\Models\Cabang;

use Intervention\Image\Facades\Image;

class FormLPG extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormLPGModel::with([
        'tugas_karyawan',
        'tipe_proses_lpg',
        'satuan',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormLPGModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormLPGModel::with([
      'tugas_karyawan',
      'tipe_proses_lpg',
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
        FormLPGModel::with([
          'tugas_karyawan',
          'tipe_proses_lpg',
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
      'tipe_proses_lpg_id',
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'tipe_proses_lpg_id' => 'required|exists:tipe_proses_lpg,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'gambar' => 'required',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $dir = public_path('img/form_lpg/' . uniqid() . uniqid() . uniqid() . '.jpg');
    Image::make(file_get_contents($request->input('gambar')))->save($dir);

    $gambarModel = new Gambar;
    $gambarModel->dir = $dir;
    $gambarModel->save();

    $formFotoModel = new FormFoto;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = 8;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();

    $formLPGModel = new FormLPGModel;
    $formLPGModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formLPGModel->tanggal_form = $request->input('tanggal_form');
    $formLPGModel->jam = $request->input('jam');
    $formLPGModel->form_foto_id = $formFotoModel->id;
    $formLPGModel->tipe_proses_lpg_id = $request->input('tipe_proses_lpg_id');
    $formLPGModel->qty = $request->input('qty');
    $formLPGModel->satuan_id = $request->input('satuan_id');
    $formLPGModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formLPGModel->gambar_id = $gambarModel->id;
    $formLPGModel->user_id = $request->user()->id;
    $formLPGModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'tipe_proses_lpg_id',
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'tipe_proses_lpg_id' => 'required|exists:tipe_proses_lpg,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'gambar' => 'nullable',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->filled('gambar')) {
      $dir = public_path('img/form_lpg/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($request->input('gambar')))->save($dir);

      $gambarModel = new Gambar;
      $gambarModel->dir = $dir;
      $gambarModel->save();
    }

    $formLPGModel = FormLPGModel::find($request->input('id'));
    $formLPGModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formLPGModel->tanggal_form = $request->input('tanggal_form');
    $formLPGModel->jam = $request->input('jam');
    $formLPGModel->tipe_proses_lpg_id = $request->input('tipe_proses_lpg_id');
    $formLPGModel->qty = $request->input('qty');
    $formLPGModel->satuan_id = $request->input('satuan_id');
    $formLPGModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formLPGModel->user_id = $request->user()->id;
    $formLPGModel->save();

    $formFotoModel = FormFoto::find($formLPGModel->form_foto_id);
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
      'id' => 'required|exists:form_goreng,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $formLPGModel = FormLPGModel::find($request->input('id'));
    $formLPGModel->user_id = $request->user()->id;
    $formLPGModel->save();

    $formFotoModel = FormFoto::find($formLPGModel->form_foto_id);
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

    $formLPGModel->delete();
  }
}