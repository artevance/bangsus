<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\FormAktivitasMarketing as FormAktivitasMarketingModel;
use App\Http\Models\FormFoto;
use App\Http\Models\Gambar;
use App\Http\Models\Cabang;

use Intervention\Image\Facades\Image;

class FormAktivitasMarketing extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormAktivitasMarketingModel::with([
        'tugas_karyawan',
        'aktivitas_marketing',
        'item_marketing',
        'satuan'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormAktivitasMarketingModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormAktivitasMarketingModel::with([
      'tugas_karyawan',
      'aktivitas_marketing',
      'item_marketing',
      'satuan'
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
        FormAktivitasMarketingModel::with([
          'tugas_karyawan',
          'aktivitas_marketing',
          'item_marketing',
          'satuan'
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
      'aktivitas_marketing_id',
      'qty',
      'satuan_id',
      'item_marketing_id',
      'lokasi',
      'keterangan',
      'gambar'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'aktivitas_marketing_id' => 'required|exists:aktivitas_marketing,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'item_marketing_id' => 'nullable|exists:item_marketing,id',
      'lokasi' => 'required|max:200',
      'keterangan' => 'nullable|max:200',
      'gambar' => 'required'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $dir = public_path('img/form_aktivitas_marketing/' . uniqid() . uniqid() . uniqid() . '.jpg');
    Image::make(file_get_contents($request->input('gambar')))->save($dir);

    $gambarModel = new Gambar;
    $gambarModel->dir = $dir;
    $gambarModel->save();

    $formFotoModel = new FormFoto;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = 9;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();

    $formAktivitasMarketingModel = new FormAktivitasMarketingModel;
    $formAktivitasMarketingModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAktivitasMarketingModel->tanggal_form = $request->input('tanggal_form');
    $formAktivitasMarketingModel->jam = $request->input('jam');
    $formAktivitasMarketingModel->form_foto_id = $formFotoModel->id;
    $formAktivitasMarketingModel->aktivitas_marketing_id = $request->input('aktivitas_marketing_id');
    $formAktivitasMarketingModel->qty = $request->input('qty');
    $formAktivitasMarketingModel->satuan_id = $request->input('satuan_id');
    $formAktivitasMarketingModel->item_marketing_id = $request->input('item_marketing_id');
    $formAktivitasMarketingModel->lokasi = $request->input('lokasi');
    $formAktivitasMarketingModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formAktivitasMarketingModel->gambar_id = $gambarModel->id;
    $formAktivitasMarketingModel->user_id = $request->user()->id;
    $formAktivitasMarketingModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'aktivitas_marketing_id',
      'qty',
      'satuan_id',
      'item_marketing_id',
      'lokasi',
      'keterangan',
      'gambar'
    ), [
      'id' => 'required|exists:form_aktivitas_marketing,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'aktivitas_marketing_id' => 'nullable|exists:aktivitas_marketing,id',
      'qty' => 'nullable|numeric',
      'satuan_id' => 'nullable|exists:satuan,id',
      'item_marketing_id' => 'nullable|exists:item_marketing,id',
      'lokasi' => 'nullable|max:200',
      'keterangan' => 'nullable|max:200',
      'gambar' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->filled('gambar')) {
      $dir = public_path('img/form_aktivitas_marketing/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($request->input('gambar')))->save($dir);

      $gambarModel = new Gambar;
      $gambarModel->dir = $dir;
      $gambarModel->save();
    }

    $formAktivitasMarketingModel = FormAktivitasMarketingModel::find($request->input('id'));
    $formAktivitasMarketingModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAktivitasMarketingModel->tanggal_form = $request->input('tanggal_form');
    $formAktivitasMarketingModel->jam = $request->input('jam');
    $formAktivitasMarketingModel->aktivitas_marketing_id = $request->input('aktivitas_marketing_id');
    $formAktivitasMarketingModel->qty = $request->input('qty');
    $formAktivitasMarketingModel->satuan_id = $request->input('satuan_id');
    $formAktivitasMarketingModel->item_marketing_id = $request->input('item_marketing_id');
    $formAktivitasMarketingModel->lokasi = $request->input('lokasi');
    $formAktivitasMarketingModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    if ($request->filled('gambar')) $formAktivitasMarketingModel->gambar_id = $gambarModel->id;
    $formAktivitasMarketingModel->user_id = $request->user()->id;
    $formAktivitasMarketingModel->save();

    $formFotoModel = FormFoto::find($formAktivitasMarketingModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      if ($request->has('tugas_karyawan_id')) $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
      if ($request->has('tanggal_form')) $formFotoModel->tanggal_form = $request->input('tanggal_form');
      if ($request->has('jam')) $formFotoModel->jam = $request->input('jam');
      if ($request->has('keterangan')) $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
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

    $formAktivitasMarketingModel = FormAktivitasMarketingModel::find($request->input('id'));
    $formAktivitasMarketingModel->user_id = $request->user()->id;
    $formAktivitasMarketingModel->save();

    $formFotoModel = FormFoto::find($formAktivitasMarketingModel->form_foto_id);
    if ( ! is_null($formFotoModel)) {
      $formFotoModel->user_id = $request->user()->id;
      $formFotoModel->save();
      $formFotoModel->delete();
    }

    $formAktivitasMarketingModel->delete();
  }
}