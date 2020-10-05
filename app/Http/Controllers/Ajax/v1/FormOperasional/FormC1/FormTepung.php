<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional\FormC1;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\Gambar;
use App\Http\Models\FormFoto;
use App\Http\Models\FormTepung as FormTepungModel;
use App\Http\Models\Cabang;

class FormTepung extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormTepungModel::with([
        'tugas_karyawan',
        'tipe_proses_tepung',
        'satuan',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormTepungModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormTepungModel::with([
      'tugas_karyawan',
      'tipe_proses_tepung',
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
        FormTepungModel::with([
          'tugas_karyawan',
          'tipe_proses_tepung',
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
      'tipe_proses_tepung_id',
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'tipe_proses_tepung_id' => 'required|exists:tipe_proses_tepung,id',
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

    $formTepungModel = new FormTepungModel;
    $formTepungModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formTepungModel->tanggal_form = $request->input('tanggal_form');
    $formTepungModel->jam = $request->input('jam');
    $formTepungModel->form_foto_id = $formFotoModel->id;
    $formTepungModel->tipe_proses_tepung_id = $request->input('tipe_proses_tepung_id');
    $formTepungModel->qty = $request->input('qty');
    $formTepungModel->satuan_id = $request->input('satuan_id');
    $formTepungModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formTepungModel->gambar_id = $gambarModel->id;
    $formTepungModel->user_id = $request->user()->id;
    $formTepungModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'tipe_proses_tepung_id',
      'qty',
      'satuan_id',
      'gambar',
      'keterangan'
    ), [
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'tipe_proses_tepung_id' => 'required|exists:tipe_proses_tepung,id',
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

    $formTepungModel = FormTepungModel::find($request->input('id'));
    $formTepungModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formTepungModel->tanggal_form = $request->input('tanggal_form');
    $formTepungModel->jam = $request->input('jam');
    $formTepungModel->tipe_proses_tepung_id = $request->input('tipe_proses_tepung_id');
    $formTepungModel->qty = $request->input('qty');
    $formTepungModel->satuan_id = $request->input('satuan_id');
    $formTepungModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formTepungModel->user_id = $request->user()->id;
    $formTepungModel->save();

    $formFotoModel = FormFoto::find($formTepungModel->form_foto_id);
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

    $formTepungModel = FormTepungModel::find($request->input('id'));
    $formTepungModel->user_id = $request->user()->id;
    $formTepungModel->save();

    $formFotoModel = FormFoto::find($formTepungModel->form_foto_id);
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

    $formTepungModel->delete();
  }
}