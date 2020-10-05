<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional\FormC1;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\Gambar;
use App\Http\Models\FormFoto;
use App\Http\Models\FormMinyak as FormMinyakModel;
use App\Http\Models\Cabang;

class FormMinyak extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormMinyakModel::with([
        'tugas_karyawan',
        'tipe_proses_minyak',
        'satuan',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormMinyakModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormMinyakModel::with([
      'tugas_karyawan',
      'tipe_proses_minyak',
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
        FormMinyakModel::with([
          'tugas_karyawan',
          'tipe_proses_minyak',
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
      'tipe_proses_minyak_id',
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'tipe_proses_minyak_id' => 'required|exists:tipe_proses_minyak,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'gambar' => 'required',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $gambarModel = new Gambar;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
    $gambarModel->save();

    $formFotoModel = new FormFoto;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = 4;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();

    $formMinyakModel = new FormMinyakModel;
    $formMinyakModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formMinyakModel->tanggal_form = $request->input('tanggal_form');
    $formMinyakModel->jam = $request->input('jam');
    $formMinyakModel->form_foto_id = $formFotoModel->id;
    $formMinyakModel->tipe_proses_minyak_id = $request->input('tipe_proses_minyak_id');
    $formMinyakModel->qty = $request->input('qty');
    $formMinyakModel->satuan_id = $request->input('satuan_id');
    $formMinyakModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formMinyakModel->gambar_id = $gambarModel->id;
    $formMinyakModel->user_id = $request->user()->id;
    $formMinyakModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'tipe_proses_minyak_id',
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'tipe_proses_minyak_id' => 'required|exists:tipe_proses_minyak,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'gambar' => 'nullable',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->filled('gambar')) {
      $gambarModel = new Gambar;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formMinyakModel = FormMinyakModel::find($request->input('id'));
    $formMinyakModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formMinyakModel->tanggal_form = $request->input('tanggal_form');
    $formMinyakModel->jam = $request->input('jam');
    $formMinyakModel->tipe_proses_minyak_id = $request->input('tipe_proses_minyak_id');
    $formMinyakModel->qty = $request->input('qty');
    $formMinyakModel->satuan_id = $request->input('satuan_id');
    $formMinyakModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formMinyakModel->user_id = $request->user()->id;
    $formMinyakModel->save();

    $formFotoModel = FormFoto::find($formMinyakModel->form_foto_id);
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

    $formMinyakModel = FormMinyakModel::find($request->input('id'));
    $formMinyakModel->user_id = $request->user()->id;
    $formMinyakModel->save();

    $formFotoModel = FormFoto::find($formMinyakModel->form_foto_id);
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

    $formMinyakModel->delete();
  }
}