<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional\FormC1;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\Gambar;
use App\Http\Models\FormFoto;
use App\Http\Models\FormGoreng as FormGorengModel;
use App\Http\Models\Cabang;

use Intervention\Image\Facades\Image;

class FormGoreng extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(FormGorengModel::with([
        'tugas_karyawan',
        'item_goreng',
        'supplier',
        'satuan',
        'user'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! FormGorengModel::find($id)->exists()) return $this->response(404);

    return $this->data(FormGorengModel::with([
      'tugas_karyawan',
      'item_goreng',
      'supplier',
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
        FormGorengModel::with([
          'tugas_karyawan',
          'item_goreng',
          'supplier',
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
      'item_goreng_id',
      'qty',
      'satuan_id',
      'supplier_id',
      'gambar',
      'keterangan'
    ), [
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'item_goreng_id' => 'required|exists:item_goreng,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'supplier_id' => 'required|exists:supplier,id',
      'gambar' => 'required',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $dir = public_path('img/form_goreng/' . uniqid() . uniqid() . uniqid() . '.jpg');
    Image::make(file_get_contents($request->input('gambar')))->save($dir);

    $gambarModel = new Gambar;
    $gambarModel->dir = $dir;
    $gambarModel->save();

    $formFotoModel = new FormFoto;
    $formFotoModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formFotoModel->tanggal_form = $request->input('tanggal_form');
    $formFotoModel->jam = $request->input('jam');
    $formFotoModel->kelompok_foto_id = 2;
    $formFotoModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formFotoModel->gambar_id = $gambarModel->id;
    $formFotoModel->tidak_kirim = 0;
    $formFotoModel->user_id = $request->user()->id;
    $formFotoModel->save();

    $formGorengModel = new FormGorengModel;
    $formGorengModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formGorengModel->tanggal_form = $request->input('tanggal_form');
    $formGorengModel->jam = $request->input('jam');
    $formGorengModel->form_foto_id = $formFotoModel->id;
    $formGorengModel->item_goreng_id = $request->input('item_goreng_id');
    $formGorengModel->qty = $request->input('qty');
    $formGorengModel->satuan_id = $request->input('satuan_id');
    $formGorengModel->supplier_id = $request->input('supplier_id');
    $formGorengModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formGorengModel->gambar_id = $gambarModel->id;
    $formGorengModel->user_id = $request->user()->id;
    $formGorengModel->save();
  }

  public function amend(Request $request)
  {
    $v = Validator::make($request->only(
      'id',
      'tugas_karyawan_id',
      'tanggal_form',
      'jam',
      'item_goreng_id',
      'qty',
      'satuan_id',
      'supplier_id',
      'gambar',
      'keterangan'
    ), [
      'id' => 'required|exists:form_goreng,id',
      'tugas_karyawan_id' => 'required|exists:tugas_karyawan,id',
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'item_goreng_id' => 'required|exists:item_goreng,id',
      'qty' => 'required|numeric',
      'satuan_id' => 'required|exists:satuan,id',
      'supplier_id' => 'required|exists:supplier,id',
      'gambar' => 'nullable',
      'keterangan' => 'nullable|max:200'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->filled('gambar')) {
      $dir = public_path('img/form_goreng/' . uniqid() . uniqid() . uniqid() . '.jpg');
      Image::make(file_get_contents($request->input('gambar')))->save($dir);

      $gambarModel = new Gambar;
      $gambarModel->dir = $dir;
      $gambarModel->save();
    }

    $formGorengModel = FormGorengModel::find($request->input('id'));
    $formGorengModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formGorengModel->tanggal_form = $request->input('tanggal_form');
    $formGorengModel->jam = $request->input('jam');
    $formGorengModel->item_goreng_id = $request->input('item_goreng_id');
    $formGorengModel->qty = $request->input('qty');
    $formGorengModel->satuan_id = $request->input('satuan_id');
    $formGorengModel->supplier_id = $request->input('supplier_id');
    $formGorengModel->keterangan = $request->filled('keterangan') ? $request->input('keterangan') : '';
    $formGorengModel->user_id = $request->user()->id;
    $formGorengModel->save();

    $formFotoModel = FormFoto::find($formGorengModel->form_foto_id);
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

    $formGorengModel = FormGorengModel::find($request->input('id'));
    $formGorengModel->user_id = $request->user()->id;
    $formGorengModel->save();

    $formFotoModel = FormFoto::find($formGorengModel->form_foto_id);
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

    $formGorengModel->delete();
  }
}