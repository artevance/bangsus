<?php

namespace App\Http\Controllers\Ajax\v1\FormOperasional;

use App\Http\Controllers\Ajax\v1\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Http\Models\PurchaseOrder as PurchaseOrderModel;
use App\Http\Models\FormFoto;
use App\Http\Models\Gambar;
use App\Http\Models\Cabang;

class PurchaseOrder extends Controller
{
  public function index(Request $request)
  {
    return $this
      ->data(PurchaseOrderModel::with([
        'cabang',
        'supplier',
        'd'
      ]))
      ->response(200);
  }

  public function get(Request $request, $id)
  {
    if ( ! is_null(PurchaseOrderModel::find($id))) return $this->response(404);

    return $this->data(PurchaseOrderModel::with([
      'cabang',
      'supplier',
      'd'
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
        PurchaseOrderModel::with([
          'cabang',
          'supplier',
          'd'
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
      'tanggal_form',
      'jam',
      'cabang_id',
      'supplier_id'
      'd'
    ), [
      'tanggal_form' => 'required|date_format:Y-m-d',
      'jam' => 'required',
      'cabang_id' => 'required|exists:cabang,id',
      'supplier_id' => 'required|exists:supplier,id',
      'd.*.barang_id' => 'required|exists:barang,id',
      'd.*.qty_asli' => 'required|numeric|max:10000000000',
      'd.*.satuan_id' => 'required|exists:satuan,id'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    $gambarModel = new Gambar;
    $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
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

    $formAktivitasMarketingModel = new PurchaseOrderModel;
    $formAktivitasMarketingModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAktivitasMarketingModel->tanggal_form = $request->input('tanggal_form');
    $formAktivitasMarketingModel->jam = $request->input('jam');
    $formAktivitasMarketingModel->form_foto_id = $formFotoModel->id;
    $formAktivitasMarketingModel->cabang_id = $request->input('cabang_id');
    $formAktivitasMarketingModel->qty = $request->input('qty');
    $formAktivitasMarketingModel->supplier_id = $request->input('supplier_id');
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
      'cabang_id',
      'qty',
      'supplier_id',
      'item_marketing_id',
      'lokasi',
      'keterangan',
      'gambar'
    ), [
      'id' => 'required|exists:form_cabang,id',
      'tugas_karyawan_id' => 'nullable|exists:tugas_karyawan,id',
      'tanggal_form' => 'nullable|date_format:Y-m-d',
      'jam' => 'nullable',
      'cabang_id' => 'nullable|exists:cabang,id',
      'qty' => 'nullable|numeric',
      'supplier_id' => 'nullable|exists:supplier,id',
      'item_marketing_id' => 'nullable|exists:item_marketing,id',
      'lokasi' => 'nullable|max:200',
      'keterangan' => 'nullable|max:200',
      'gambar' => 'nullable'
    ]);
    if ($v->fails()) return $this->errors($v->errors())->response(422);

    if ($request->filled('gambar')) {
      $gambarModel = new GambarModel;
      $gambarModel->konten = base64_decode(str_replace(' ', '+', explode(',', $request->input('gambar'))[1]));
      $gambarModel->save();
    }

    $formAktivitasMarketingModel = PurchaseOrderModel::find($request->input('id'));
    $formAktivitasMarketingModel->tugas_karyawan_id = $request->input('tugas_karyawan_id');
    $formAktivitasMarketingModel->tanggal_form = $request->input('tanggal_form');
    $formAktivitasMarketingModel->jam = $request->input('jam');
    $formAktivitasMarketingModel->cabang_id = $request->input('cabang_id');
    $formAktivitasMarketingModel->qty = $request->input('qty');
    $formAktivitasMarketingModel->supplier_id = $request->input('supplier_id');
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

    $formAktivitasMarketingModel = PurchaseOrderModel::find($request->input('id'));
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